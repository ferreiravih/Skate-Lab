<?php

namespace App\Frete;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use InvalidArgumentException;
use RuntimeException;

class FreteService
{
    private const VIA_CEP_ENDPOINTS = [
        'https://viacep.com.br/ws/%s/json/',
        'http://viacep.com.br/ws/%s/json/',
    ];
    private const ORIGIN_CEP = '01001000';
    private const ORIGIN_LABEL = 'Skate-Lab - Sao Paulo/SP';
    private const DEFAULT_ITEM_WEIGHT = 1.8;

    private const REGIAO_ESTADOS = [
        'sudeste' => ['SP', 'RJ', 'MG', 'ES'],
        'sul' => ['PR', 'SC', 'RS'],
        'centro-oeste' => ['DF', 'GO', 'MS', 'MT'],
        'nordeste' => ['AL', 'BA', 'CE', 'MA', 'PB', 'PE', 'PI', 'RN', 'SE'],
        'norte' => ['AC', 'AM', 'AP', 'PA', 'RO', 'RR', 'TO'],
    ];

    private const REGIAO_TABELA = [
        'sudeste' => ['base' => 22.9, 'prazo' => 3, 'acrescimo' => 3.2],
        'sul' => ['base' => 26.9, 'prazo' => 4, 'acrescimo' => 3.6],
        'centro-oeste' => ['base' => 28.5, 'prazo' => 5, 'acrescimo' => 3.8],
        'nordeste' => ['base' => 32.9, 'prazo' => 7, 'acrescimo' => 4.1],
        'norte' => ['base' => 39.9, 'prazo' => 9, 'acrescimo' => 4.7],
    ];

    private Client $httpClient;

    public function __construct(?Client $client = null)
    {
        $this->httpClient = $client ?? new Client([
            'timeout' => 8,
        ]);
    }

    public function cotar(array $itens, string $cepDestino): array
    {
        $cep = $this->sanitizaCep($cepDestino);
        if (!$cep) {
            throw new InvalidArgumentException('Informe um CEP valido com 8 numeros.');
        }

        if (empty($itens)) {
            throw new RuntimeException('Adicione itens ao carrinho antes de calcular o frete.');
        }

        $cepInfo = $this->buscarCep($cep);
        $regiao = $this->descobrirRegiao((string)($cepInfo['uf'] ?? ''));
        $tabela = self::REGIAO_TABELA[$regiao];
        $pesoTotal = $this->calcularPesoTotal($itens);

        $economico = $this->montaOpcao(
            codigo: 'ECONOMICO',
            label: 'Envio Economico',
            descricao: 'Entrega padrao com rastreio.',
            base: (float)$tabela['base'],
            prazoBase: (int)$tabela['prazo'],
            pesoTotal: $pesoTotal,
            acrescimoPeso: (float)$tabela['acrescimo']
        );

        $expresso = $this->montaOpcao(
            codigo: 'EXPRESSO',
            label: 'Envio Expresso',
            descricao: 'Prioridade de expedicao + seguro basico.',
            base: (float)$tabela['base'] + 12,
            prazoBase: max(1, (int)$tabela['prazo'] - 2),
            pesoTotal: $pesoTotal,
            acrescimoPeso: (float)$tabela['acrescimo'],
            multiplicador: 1.35
        );

        return [
            'origem' => [
                'cep' => self::ORIGIN_CEP,
                'label' => self::ORIGIN_LABEL,
            ],
            'destino' => [
                'cep' => $cep,
                'cidade' => $cepInfo['localidade'] ?? '',
                'uf' => $cepInfo['uf'] ?? '',
                'bairro' => $cepInfo['bairro'] ?? '',
                'logradouro' => $cepInfo['logradouro'] ?? '',
            ],
            'peso_total' => $pesoTotal,
            'opcoes' => [$economico, $expresso],
        ];
    }

    private function sanitizaCep(string $cep): ?string
    {
        $numeros = preg_replace('/\D/', '', $cep);
        if (!$numeros || strlen($numeros) !== 8) {
            return null;
        }

        return $numeros;
    }

    private function calcularPesoTotal(array $itens): float
    {
        $peso = 0.0;
        foreach ($itens as $item) {
            $pesoUnitario = isset($item['peso']) && (float)$item['peso'] > 0
                ? (float)$item['peso']
                : self::DEFAULT_ITEM_WEIGHT;

            $quantidade = isset($item['quantidade']) ? max(1, (int)$item['quantidade']) : 1;
            $peso += $pesoUnitario * $quantidade;
        }

        return round(max($peso, self::DEFAULT_ITEM_WEIGHT), 2);
    }

    private function descobrirRegiao(string $uf): string
    {
        $uf = strtoupper(trim($uf));
        foreach (self::REGIAO_ESTADOS as $regiao => $estados) {
            if (in_array($uf, $estados, true)) {
                return $regiao;
            }
        }

        return 'sudeste';
    }

    private function buscarCep(string $cep): array
    {
        foreach (self::VIA_CEP_ENDPOINTS as $endpoint) {
            try {
                $response = $this->httpClient->request('GET', sprintf($endpoint, $cep), [
                    'http_errors' => false,
                    'headers' => ['Accept' => 'application/json'],
                ]);
            } catch (GuzzleException) {
                continue;
            }

            if ($response->getStatusCode() >= 400) {
                throw new InvalidArgumentException('CEP invalido ou indisponivel.');
            }

            $payload = json_decode((string)$response->getBody(), true);
            if (!is_array($payload) || isset($payload['erro'])) {
                throw new InvalidArgumentException('CEP nao encontrado.');
            }

            return $payload;
        }

        throw new RuntimeException('Nao foi possivel consultar o CEP no momento. Tente novamente em instantes.');
    }

    private function montaOpcao(
        string $codigo,
        string $label,
        string $descricao,
        float $base,
        int $prazoBase,
        float $pesoTotal,
        float $acrescimoPeso,
        float $multiplicador = 1.0
    ): array {
        $valor = $this->calcularValorFrete($base, $pesoTotal, $acrescimoPeso, $multiplicador);
        $prazo = $this->calcularPrazo($prazoBase, $pesoTotal);

        return [
            'codigo' => $codigo,
            'label' => $label,
            'descricao' => $descricao,
            'valor' => $valor,
            'prazo' => $prazo,
        ];
    }

    private function calcularValorFrete(float $base, float $peso, float $acrescimo, float $multiplicador): float
    {
        $pesoExtra = max(0, $peso - 1);
        $valor = ($base + $pesoExtra * $acrescimo) * $multiplicador;

        return round($valor, 2);
    }

    private function calcularPrazo(int $prazoBase, float $peso): int
    {
        $ajustePeso = max(0, (int)ceil($peso) - 1);
        $prazo = $prazoBase + $ajustePeso;

        return max(1, $prazo);
    }
}


<?php

declare(strict_types=1);

use App\Frete\FreteService;

session_start();

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método não permitido.']);
    exit;
}

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../frete/FreteService.php';

$payload = json_decode(file_get_contents('php://input'), true);
if (!is_array($payload)) {
    $payload = $_POST;
}

$cep = $payload['cep'] ?? '';
$servico = $payload['servico'] ?? null;
$carrinho = $_SESSION['carrinho'] ?? [];
$subtotal = array_reduce($carrinho, static function ($total, $item) {
    $preco = isset($item['preco']) ? (float)$item['preco'] : 0.0;
    $quantidade = isset($item['quantidade']) ? max(1, (int)$item['quantidade']) : 1;

    return $total + ($preco * $quantidade);
}, 0.0);

try {
    $service = new FreteService();
    $cotacao = $service->cotar($carrinho, (string)$cep);

    $selecionada = null;
    if ($servico) {
        foreach ($cotacao['opcoes'] as $opcao) {
            if ($opcao['codigo'] === $servico) {
                $selecionada = $opcao;
                break;
            }
        }
    }

    if (!$selecionada) {
        $selecionada = $cotacao['opcoes'][0] ?? null;
    }

    $freteValor = $selecionada ? (float)$selecionada['valor'] : 0.0;
    $_SESSION['frete_cotacao'] = [
        'cep' => $cotacao['destino']['cep'],
        'destino' => $cotacao['destino'],
        'opcoes' => $cotacao['opcoes'],
        'selecionado' => $selecionada,
    ];

    $resumo = [
        'subtotal' => $subtotal,
        'frete' => $freteValor,
        'total' => $subtotal + $freteValor,
    ];

    echo json_encode([
        'success' => true,
        'cotacao' => $cotacao,
        'selecionado' => $selecionada,
        'resumo' => $resumo,
    ]);
} catch (InvalidArgumentException $exception) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => $exception->getMessage()]);
} catch (RuntimeException $exception) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $exception->getMessage()]);
} catch (Throwable $exception) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao calcular o frete.',
        'details' => $exception->getMessage(),
    ]);
}

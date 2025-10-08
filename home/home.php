<?php

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../home/home.css">
    <link rel="stylesheet" href="../global/global.css">
    <title>Home_SkateLab</title>
</head>

<body>
    <nav>
        <?php include '../componentes/navbar.php'; ?>
    </nav>

    <main>

    <!-- HOME - CAPA -->
        <section id="capa">
            <div class="capa-conteudo">
                <h1> MONTE SEU <br> SKATE DOS <br> SONHOS </h1>
                <p>Peça por peça, visualizando tudo em tempo real</p>
                <a href="#beneficios" class="btn-saiba-mais">SAIBA MAIS</a>
            </div>
         </section>



    <!-- HOME - BENEFICIOS -->
         <section id="beneficios">
            <div class="beneficio-item">
                <img src="../img/imgs-home/home-entrega.png" alt="Entrega Expressa">
                <p>ENTREGA EXPRESSA<br><span>para todo o Brasil</span></p>
            </div>

            <div class="beneficio-item">
                <img src="../img/imgs-home/home-segura.png" alt="Compra Segura">
                <p>COMPRA SEGURA<br><span>ambiente protegido</span></p>
            </div>

            <div class="beneficio-item">
                <img src="../img/imgs-home/home-desconto.png" alt="5% Desconto">
                <p>5% DE DESCONTO<br><span>no PIX ou boleto</span></p>
            </div>

            <div class="beneficio-item">
                <img src="../img/imgs-home/home-troca.png" alt="Troca grátis">
                <p>PRIMEIRA TROCA GRÁTIS<br><span>em até 7 dias</span></p>
            </div>
         </section>



    <!-- HOME - CUSTOMIZAÇAO / MONTAGEM -->
        <section id="customizacao">
            <div class="customizacao-conteudo">
                <div class="texto">
                    <h2>CUSTOMIZAÇÃO EM TEMPO REAL</h2>
                    <p>VISUALIZE SEU SKATE ENQUANTO ESCOLHE<br>AS MELHORES PEÇAS EM 3 PASSOS SIMPLES</p>
                    <a href="#" class="btn-customizacao">TESTAR CUSTOMIZAÇÃO</a>
                </div>

                <div class="img-skate">
                    <img src="../img/imgs-home/home-montagem.png" alt="Montagem Skate">
                </div>
            </div>
        </section>



    <!-- HOME - PASSOS -->
        <section class="passos">
        <div class="passo passo1">
            <div class="numero">1</div>
            <img src="../img/imgs-home/home-passo1.png" alt="Passo 1: Escolha o Shape">
            <p>ESCOLHA O SHAPE</p>
        </div>
        <div class="passo passo2">
            <div class="numero">2</div>
            <img src="../img/imgs-home/home-passo2.png" alt="Passo 2: Adicione as Peças">
            <p>ADICIONE AS PEÇAS</p>
        </div>
        <div class="passo passo3">
            <div class="numero">3</div>
            <img src="../img/imgs-home/home-passo3.png" alt="Passo 3: Finalize sua Compra">
            <p>FINALIZE SUA COMPRA</p>
        </div>
     </section>


<!-- HOME - KITS -->
    <section class="kits">
        <h3>KITS TEMÁTICOS PARA CADA SKATISTA</h3>
        <div class="galeria-kits">
            <div class="kit">
                <img src="https://i.imgur.com/d0bYp2f.png" alt="Kit de Rolamentos e Rodas">
                <p class="nome-kit">RODA SPITFIRE BURNER WHITE & ROLAMENTO RED BONES</p>
                <p class="preco-kit">R$ 349,90</p>
                <p class="parcelamento-kit">Em até 12x de R$ 29,15</p>
                <a href="#" class="botao-ver-mais">Ver Mais</a>
            </div>
            <div class="kit">
                <img src="https://i.imgur.com/2s3xXpL.png" alt="Kit de Shape">
                <p class="nome-kit">SKATE KAROTO SHIRUPUKEN - G.SHIRATSUKI</p>
                <p class="preco-kit">R$ 399,90</p>
                <p class="parcelamento-kit">Em até 12x de R$ 33,32</p>
                <a href="#" class="botao-ver-mais">Ver Mais</a>
            </div>
            <div class="kit">
                <img src="https://i.imgur.com/r62Kq4R.png" alt="Kit de Trucks">
                <p class="nome-kit">TRUCK ESSÊNCIA 129</p>
                <p class="preco-kit">R$ 221,32</p>
                <p class="parcelamento-kit">Em até 12x de R$ 18,44</p>
                <a href="#" class="botao-ver-mais">Ver Mais</a>
            </div>
        </div>
        
        <div class="nov">
            <a href="#" class="botao-nov">Descubra Mais</a> 
        </div>
        
    </section>


<!-- HOME - NOVIDADES -->
    <section class="novidades">
        <div class="conteudo-novidades">
            <h2>NOVIDADES E PROMOÇÕES</h2>
            <p>Cadastre-se agora e receba novidades</p>
            <div class="formulario-novidades">
                <input type="email" placeholder="Digite seu e-mail para receber novidades e promoções">
                <a href="#" class="botao-cadastrar">CADASTRAR</a>
            </div>
        </div>
    </section>



    <!-- HOME - AVALIAÇÕES -->
    <section class="avaliacoes">
        <h2>AVALIAÇÕES</h2>
        <div class="carrossel-avaliacoes">
            <button class="seta esquerda">&lt;</button>
            <div class="card-avaliacao">
                <p>Lorem ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
                <p class="nome-avaliador">CAIO DOS SANTOS</p>
            </div>
            <div class="card-avaliacao">
                <p>Lorem ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
                <p class="nome-avaliador">KAUE FERREIRA</p>
            </div>
            <div class="card-avaliacao">
                <p>Lorem ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
                <p class="nome-avaliador">PEDRO D'EMIN</p>
            </div>
            <button class="seta direita">&gt;</button>
        </div>
    </section>

    <script src="scripts.js"></script>

    </main>
    
    <footer>
        <?php include '../componentes/footer.php'; ?>
</footer>
</body>
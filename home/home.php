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

    <!-- Home - Capa -->
        <section id="capa">
            <div class="capa-conteudo">
                <h1> MONTE SEU <br> SKATE DOS <br> SONHOS </h1> 
                <p>Peça por peça, visualizando tudo em tempo real</p> 
                <a href="#beneficios" class="btn-saiba-mais">SAIBA MAIS</a>
            </div>
        </section>


    <!-- Home - Beneficios -->
         <section id="beneficios">
            <div class="beneficio-item">
                <img src="../img/imgs-home/img-entrega.png" alt="Entrega Expressa">
                <p>ENTREGA EXPRESSA<br><span>para todo o Brasil</span></p>
            </div>

            <div class="beneficio-item">
                <img src="../img/imgs-home/img-segura.png" alt="Compra Segura">
                <p>COMPRA SEGURA<br><span>ambiente protegido</span></p>
            </div>
            
            <div class="beneficio-item">
                <img src="../img/imgs-home/img-desconto.png" alt="5% Desconto">
                 <p>5% DE DESCONTO<br><span>no PIX ou boleto</span></p>
            </div>

            <div class="beneficio-item">
                <img src="../img/imgs-home/img-troca.png" alt="Troca grátis">
                <p>PRIMEIRA TROCA GRÁTIS<br><span>em até 7 dias</span></p>
            </div>
        </section>


        <!-- Home - CUSTOMIZAÇÃO / MONTAGEM -->
         <section id="customizacao">
            <div class="customizacao-conteudo">
                <div class="texto">
                    <h2>CUSTOMIZAÇÃO EM TEMPO REAL</h2>
                    <p>VISUALIZE SEU SKATE ENQUANTO ESCOLHE<br>AS MELHORES PEÇAS EM 3 PASSOS SIMPLES</p>
                    <a href="#" class="btn-customizacao">TESTAR CUSTOMIZAÇÃO</a>
                </div>

                <div class="img-skate">
                    <img src="../img/imgs-home/img-montagem.png" alt="Montagem Skate">
                </div>
             </div>

             <div class="passos-container">
                <div class="passo">
                    <div class="passo-numero">1</div>
                    <div class="passo-image">
                        <img src="../img/imgs-home/img-passo1.png" alt="Shape">
                    </div>
                    <div class="passo-text">ESCOLHA O SHAPE</div>
                </div>
                
                <div class="passo">
                    <div class="passo-numero">2</div>
                    <div class="passo-image">
                        <img src="../img/imgs-home/img-passo2.png" alt="Peças">
                    </div>
                    <div class="passo-text">ADICIONE AS PEÇAS</div>
                </div>
                
                <div class="passo">
                    <div class="passo-numero">3</div>
                    <div class="passo-image">
                        <img src="../img/imgs-home/img-passo3.png" alt="Finalizar">
                    </div>
                    <div class="passo-text">FINALIZE SUA COMPRA</div>
                </div>
            </div>


            


















    </main>
        <?php include '../componentes/footer.php'; ?>
</body>
</html>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Verifique sua Conta - SkateLab</title>
    
    <link rel="stylesheet" href="global/global.css">
    <link rel="stylesheet" href="componentes/nav.css">
    <link rel="stylesheet" href="componentes/footer.css">
    
    <style>
        .container-verificacao {
            max-width: 450px; margin: 50px auto; padding: 30px;
            box-shadow: 0 6px 24px rgba(0,0,0,0.15); border-radius: 12px;
            text-align: center; font-family: 'Poppins', sans-serif;
            background: #fff;
        }
        .container-verificacao h2 { color: #5a2472; font-size: 1.8rem; }
        .container-verificacao p { color: #333; font-size: 1rem; line-height: 1.6; }
        .container-verificacao input[type="text"] {
            width: 100%; padding: 12px; font-size: 1.5rem; text-align: center;
            border: 1px solid #ccc; border-radius: 8px; letter-spacing: 5px;
            margin-top: 10px; box-sizing: border-box;
        }
        .container-verificacao button[type="submit"] { 
            background: #f08644; color: white; padding: 12px 25px; border: none;
            border-radius: 8px; cursor: pointer; margin-top: 20px; font-weight: 600;
            font-size: 1rem; transition: background 0.3s;
        }
        .container-verificacao button[type="submit"]:hover { background: #e25500; }
        .feedback { padding: 10px; margin: 15px 0; border-radius: 5px; font-weight: 500; }
        .error { background: #f8d7da; color: #721c24; }
        .success { background: #d4edda; color: #155724; }
        
        .btn-reenviar {
            display: block; 
            width: 100%;
            box-sizing: border-box;
            margin-top: 20px; 
            font-size: 0.9rem; 
            color: #5a2472;
            font-weight: 500;
            text-decoration: none;
            background: none;
            border: none;
            padding: 5px;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
        }
        .btn-reenviar:hover:not(:disabled) { 
            text-decoration: underline; 
        }
        
        .btn-reenviar:disabled {
            color: #999;
            cursor: not-allowed;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <?php include 'componentes/navbar.php'; ?>
    
    <main style="padding: 20px 0; flex: 1;">
        <div class="container-verificacao">
            <h2>Verifique sua Conta</h2>
            
            <?php if(isset($_GET['error'])): ?>
                <div class="feedback error">
                    <?php
                        if($_GET['error'] === 'invalid_code') echo 'Código inválido ou expirado. Tente novamente.';
                        if($_GET['error'] === 'not_verified') echo 'Você precisa verificar sua conta para fazer login.';
                        if($_GET['error'] === 'email_failed') echo 'Ocorreu um erro ao enviar o email. Tente novamente.';
                    ?>
                </div>
            <?php endif; ?>
            
            <?php if(isset($_GET['status']) && $_GET['status'] === 'resent'): ?>
                <div class="feedback success">
                    Um novo código foi enviado para o seu e-mail!
                </div>
            <?php endif; ?>

            <?php if(isset($_GET['email'])): ?>
                <p>Enviamos um código de 6 dígitos para o e-mail: <br><strong><?php echo htmlspecialchars($_GET['email']); ?></strong></p>
            
                <form action="auth/processar_verificacao.php" method="POST">
                    <input type="hidden" name="email" value="<?php echo htmlspecialchars($_GET['email']); ?>">
                    
                    <label for="codigo" style="font-weight: 500;">Digite o Código:</label>
                    <input type="text" id="codigo" name="codigo" maxlength="6" required>
                    
                    <button type="submit">Verificar Conta</button>
                </form>

                <button type="button" id="resendButton" class="btn-reenviar" disabled>
                    Reenviar código (aguarde 15s)
                </button>
                
            <?php else: ?>
                <p class="feedback error">Email não fornecido.</p>
            <?php endif; ?>
        </div>
    </main>

    <?php include 'componentes/footer.php'; ?>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const resendButton = document.getElementById('resendButton');
        
        const urlParams = new URLSearchParams(window.location.search);
        const email = urlParams.get('email');

        if (resendButton && email) {
            let countdown = 15; 
            
            // Inicia o contador
            const interval = setInterval(() => {
                countdown--;
                if (countdown > 0) {
                    resendButton.textContent = `Reenviar código (aguarde ${countdown}s)`;
                } else {
                   
                    clearInterval(interval);
                    resendButton.textContent = 'Não recebeu? Reenviar código';
                    resendButton.disabled = false; 
                }
            }, 1000); 

           
            resendButton.addEventListener('click', () => {
                if (!resendButton.disabled) {
                    window.location.href = `auth/reenviar_codigo.php?email=${encodeURIComponent(email)}`;
                }
            });
        }
    });
    </script>
</body>
</html>
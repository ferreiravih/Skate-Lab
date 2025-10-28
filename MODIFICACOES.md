# Lista com modificações no projeto.

### Pastas:
## 1. Criar Arquivo com variaveis de ambiente.
    /SKATE-LAB/config/db.php => "Arquivo de configuração do banco de dados".
     user: 'postgres'
    password: ''
    url: 'https://gzbeazpbvgiymtmtgffy.supabase.co'
    port: '5432'
    database: 'postgres'


## 2. Outros arquivos criados
    
    /Skate-Lab/admin/admin_auth.php => Valida o usuario verificando se é admin
    se nao for nao permitirá modificação e expulsará o usuario, fazendo com que
    retorne para a tela de login

    /Skate-Lab/admin/add_produto/criar_produto.php => Criara o produto ou peca no DB consultando a categoria existente para a escolha.

### Arquivos de teste:

    info.php => Informaçoes do servidor, e verficar se os driver do pdo_pgsql estam ativos.
    para vefificar com este arquivo, abra esta pagina info.php, pressione 'ctrl + f' para abrir a pesquisa, digite: 'PDO_PGSQL' se aparecer na busca esta tudo certo.
    Se não retornar nada na busca, no laragon click com o botao direito no laragon ou no menu, na aba PHP, depois Extensions, ative o PDO_PGSQL e reinicie o servidor.
    Se nao por possivel ativar, será preciso instalar o postgress:
    -1 Click com o botao direito no laragon ou no menu
    -2 Ferramentas ou tools
    -3 Quick add
    -4 Procure por postgress, escolha a versão e aguarde o downloads e instalação
    -5 reinicie o sevidor e ative a extensão do PDO_PGSQL.

    test_db.php => Testa a conexão com o supabase.



<?php
require_once '../db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_BCRYPT); // Criptografa a senha

    // Verifica se o nome de usuário ou o e-mail já existe
    $stmt = $pdo->prepare("SELECT * FROM knw_usuarios WHERE USU_NOME = ? OR USU_EMAIL = ?");
    $stmt->execute([$nome, $email]);
    if ($stmt->rowCount() > 0) {
        echo "Nome de usuário ou e-mail já existe!";
    } else {
        // Insere o novo usuário no banco de dados
        $stmt = $pdo->prepare("INSERT INTO knw_usuarios (USU_NOME, USU_EMAIL, USU_SENHA) VALUES (?, ?, ?)");
        if ($stmt->execute([$nome, $email, $senha])) {
            $_SESSION['usuario'] = $nome;
            $_SESSION['logado'] = true;
            header('Location: ../pag-feed/pagina-feed.php');
        } else {
            echo "Erro ao registrar usuário.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../styles/global.css">
    <link rel="stylesheet" href="pagina-criar-conta.css">
    <title>Document</title>
</head>

<body>
    <header>
        <div class="nav-bar">
            <a href="..">
                <svg class="btn-voltar" width="26" height="27" viewBox="0 0 26 27" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M21.3984 12.3323H8.41804L14.3803 6.3701L12.8657 4.86621L4.33301 13.3989L12.8657 21.9316L14.3696 20.4277L8.41804 14.4655H21.3984V12.3323Z"
                        fill="white" />
                </svg>
            </a>
            <a href="#">
                <img class="logo-know" src="../../assets/logo-know.png" alt="logo-know">
            </a>
        </div>
    </header>
    <main>
        <div class="container">
            <div class="campo-criar-conta">
                <h1 class="titulo">Crie uma conta</h1>
                <form class="form-criar-conta" method="post">
                    <div class="campos">
                        <label class="titulos" for="nome">nome</label>
                        <input class="inputs" name="nome" required>
                    </div>
                    <div class="campos">
                        <label class="titulos" for="email">e-mail</label>
                        <input class="inputs" type="email" name="email" required>
                    </div>
                    <div class="campos">
                        <label class="titulos" for="senha">senha</label>
                        <input class="inputs" type="password" name="senha" required>
                    </div>
                    <button type="submit" class="btn-1">Criar</button>
                </form>
            </div>
        </div>
    </main>
</body>

</html>
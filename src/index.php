<?php
require_once 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $credencial = $_POST['credencial'];
    $senha = $_POST['senha'];

    $stmt = $pdo->prepare("SELECT * FROM knw_usuarios WHERE USU_NOME = ? OR USU_EMAIL = ?");
    $stmt->execute([$credencial, $credencial]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if (password_verify($senha, $user['USU_SENHA'])) {
            $_SESSION['logado'] = true; // Salva o estado de login na sessão
            $_SESSION['email'] = $user['USU_EMAIL']; // Salva o email do usuário na sessão
            $_SESSION['usuario'] = $user['USU_NOME']; // Salva o nome do usuário na sessão
            header('Location: pag-feed/pagina-feed.php');
        } else {
            echo "Senha incorreta!";
        }
    } else {
        echo "Usuário não existe!";
    }
}
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="pag-inicio/pagina-inicio.css">
    <link rel="stylesheet" href="../styles/global.css">
    <title>KNOW</title>
</head>

<body>
    <header>
        <nav class="nav-bar">
            <a href="#"><img class="logo-know" src="../assets/logo-know.png" alt="logo-know"></a>
            <form class="container-login" method="post">
                <input class="campo-email" placeholder="e-mail ou nome de usuário" name="credencial" required>
                <input class="campo-senha" placeholder="senha" name="senha" type="password" required>
                <button class="btn-1" id="btn-entrar" type="submit">ENTRAR</button>
            </form>
        </nav>
    </header>
    <main>
        <div class="container" id="container-inicio">
            <div>
                <h1 class="titulo">Conectando alunos, facilitando o aprendizado</h1>
                <p class="subtitulo">Know é uma plataforma digital de aprendizado colaborativo desenvolvida para os
                    alunos do campus. Ela facilita a troca de conhecimentos e a resolução de dúvidas entre colegas.</p>
                <a href="pag-criar-conta/pagina-criar-conta.php">
                    <button class="btn-1" id="btn-cadastrar">Crie uma conta</button>
                </a>
            </div>
            <img class="img-inicio" src="../assets/img.png" alt="img-inicio">
        </div>
    </main>
</body>

</html>
<?php
require_once '../db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $nome = $_SESSION['usuario'];

    $stmt = $pdo->prepare("INSERT INTO knw_pergunta (PER_TITULO, PER_DESCRICAO, PER_USU_NOME) VALUES (?, ?, ?)");
    if ($stmt->execute([$titulo, $descricao, $nome])) {
        header('Location: ../pag-feed/pagina-feed.php');
    } else {
        echo "Erro ao registrar pergunta.";
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <title>KNOW - Crie sua pergunta</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../styles/global.css">
    <link rel="stylesheet" href="pagina-pergunta.css">
</head>

<body>
    <header>
        <div class="nav-bar">
            <a href="../pag-feed/pagina-feed.php">
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
        <div class="lateral-esquerda"></div>
        <div class="container">
            <h2 class="txt-faca-uma-pergunta">Faça uma pergunta</h2>
            <div class="pergunta-container">
                <form method="POST">
                    <textarea class="pergunta-titulo" name="titulo" placeholder="título" required></textarea>
                    <textarea class="pergunta-descricao" name="descricao" placeholder="Digite sua pergunta aqui..."
                        required></textarea>
                    <button class="btn-1" id="btn-enviar" type="submit">Enviar</button>
                </form>
                <div id="filtros">
                <div class="dropdown">
                    <button onclick="menuDropdown()" class="dropbtn"><img class="menu-usuario" src="../../assets/icon-dropdown.png" alt=""></button> <!-- mostra uma caixa de opcoes ao ser clicado -->
                    <div id="pergunta-dropdown" class="dropdown-conteudo"> <!-- conteudo mostrado ao clicar no dropdown -->
                        <p>conteudo</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="lateral-direita"></div>
    </main>
    <script src="pagina-pergunta.js"></script>
</body>

</html>
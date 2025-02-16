<?php
require_once '../db.php';
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['logado']) || !$_SESSION['logado']) {
    header('Location: ../index.php');
    exit();
}

// Verifica se o ID da pergunta foi passado na URL
if (!isset($_GET['id'])) {
    header('Location: ../pag-perfil/pagina-perfil.php');
    exit();
}

$pergunta_id = $_GET['id'];

// Prepara e executa a consulta para obter os detalhes da pergunta
$stmt = $pdo->prepare("SELECT * FROM knw_pergunta WHERE PER_ID = ?");
$stmt->execute([$pergunta_id]); // Execute a consulta
$pergunta = $stmt->fetch(PDO::FETCH_ASSOC); // Use fetch para obter apenas uma pergunta

// Processa o formulário de edição
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];

    $stmt = $pdo->prepare("UPDATE knw_pergunta SET PER_TITULO = ?, PER_DESCRICAO = ? WHERE PER_ID = ?");
    if ($stmt->execute([$titulo, $descricao, $pergunta_id])) {
        header('Location: ../pag-perfil/pagina-perfil.php');
    } else {
        echo "Erro ao editar pergunta.";
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <title>KNOW - Edite sua pergunta</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../styles/global.css">
    <link rel="stylesheet" href="pagina-edicao.css">
</head>

<body>
    <header>
        <div class="nav-bar">
            <a href="../pag-perfil/pagina-perfil.php">
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
            <h2 class="txt-faca-uma-pergunta">Edite sua pergunta</h2>
            <div class="pergunta-container">
                <form method="POST">
                    <textarea class="pergunta-titulo" name="titulo" placeholder="título" required><?= $pergunta['PER_TITULO'] ?></textarea>
                    <textarea class="pergunta-descricao" name="descricao" placeholder="Digite sua pergunta aqui..."
                        required><?= $pergunta['PER_DESCRICAO']?></textarea>
                    <button class="btn-1" id="btn-enviar" type="submit">Enviar</button>
                </form>
                <div id="filtros">
                    <button class="btn-1">
                        Disciplina
                        <img src="../../assets/icon-dropdown.png" alt="botao-filtro">
                    </button>
                    <button class="btn-1">
                        <p>Curso</p>
                        <img src="../../assets/icon-dropdown.png" alt="botao-filtro">
                    </button>
                </div>
            </div>
        </div>
        <div class="lateral-direita"></div>
    </main>
    <script src="pagina-pergunta.js"></script>
</body>

</html>
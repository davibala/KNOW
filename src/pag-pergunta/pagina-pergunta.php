<?php
require_once '../db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $nome = $_SESSION['usuario'];
    $tags = isset($_POST['tags']) ? explode(',', $_POST['tags']) : [];

    // Insere a pergunta
    $stmt = $pdo->prepare("INSERT INTO knw_pergunta (PER_TITULO, PER_DESCRICAO, PER_USU_NOME) VALUES (?, ?, ?)");
    if ($stmt->execute([$titulo, $descricao, $nome])) {
        $perguntaId = $pdo->lastInsertId();

        // Associa as tags à pergunta
        if (!empty($tags)) {
            foreach ($tags as $tagId) {
                // Verifica se a tag existe
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM knw_tags WHERE TAG_ID = ?");
                $stmt->execute([$tagId]);
                $exists = $stmt->fetchColumn();

                if ($exists) {
                    $stmt = $pdo->prepare("INSERT INTO pergunta_tags (PER_ID, TAG_ID) VALUES (?, ?)");
                    $stmt->execute([$perguntaId, $tagId]);
                } else {
                    echo "Erro: A tag com ID $tagId não existe.";
                }
            }
        }

        header('Location: ../pag-feed/pagina-feed.php');
    } else {
        echo "Erro ao registrar pergunta.";
    }
}

$stmt = $pdo->prepare("SELECT * FROM KNW_TAGS");
$stmt->execute();
$tags = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
                <form method="POST" class="form-pergunta">
                    <div id="titulo-pergunta" class="area-titulo" contenteditable="true" placeholder="Digite o titulo aqui..."></div>
                    <div id="descricao-pergunta" class="area-descricao" contenteditable="true" placeholder="Digite sua pergunta aqui..."></div>
                    <div class="flex-formatacoes-btn">
                        <div class="formatacoes">
                            <button class="btnForm" type="button" id="btn-bold"><img class="icon"
                                    src="../../assets/icons/icon-bold.png" alt="Negrito"></button>
                            <button class="btnForm" type="button" id="btn-italic"><img class="icon" id="icon-italic"
                                    src="../../assets/icons/icon-italic.png" alt="Itálico"></button>
                            <button class="btnForm" type="button" id="btn-underline"><img class="icon"
                                    src="../../assets/icons/icon-sublinhed.png" alt="Sublinhado"></button>
                        </div>
                        <div id="filtros">
                            <select class="tags" id="select-tags">
                                <option class="tag" value="">Adicionar tags</option>
                                <?php foreach ($tags as $tag): ?>
                                    <option class="tag" value="<?= $tag['TAG_ID'] ?>"><?= $tag['TAG_NOME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div id="tags-selecionadas"></div>
                        </div>
                        <button class="btn-1" id="btn-enviar" type="submit">Enviar</button>
                    </div>
                </form>
            </div>
            <div class="lateral-direita"></div>
    </main>
    <script src="pagina-pergunta.js"></script>
</body>

</html>
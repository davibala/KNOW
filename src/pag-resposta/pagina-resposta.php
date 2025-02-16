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
    header('Location: ../pag-feed/pagina-feed.php');
    exit();
}

$pergunta_id = $_GET['id'];

// Prepara e executa a consulta para obter os detalhes da pergunta
$stmt = $pdo->prepare("SELECT *, TIMESTAMPDIFF(MINUTE, PER_DATA, NOW()) AS DIFERENCA_MINUTOS FROM knw_pergunta WHERE PER_ID = ?");
$stmt->execute([$pergunta_id]); // Execute a consulta
$pergunta = $stmt->fetch(PDO::FETCH_ASSOC); // Use fetch para obter apenas uma pergunta

// Prepara e executa a consulta para obter as respostas da pergunta
$stmt = $pdo->prepare("SELECT * FROM KNW_RESPOSTA WHERE RES_PER_ID = ?"); // Use o ID da pergunta para filtrar as respostas
$stmt->execute([$pergunta_id]); // Execute a consulta
$respostas = $stmt->fetchAll(PDO::FETCH_ASSOC); // Use fetchAll para obter todas as respostas

// Verifica se a pergunta existe
if (!$pergunta) {
    header('Location: ../pag-feed/pagina-feed.php');
    exit();
}

// Processa o envio da resposta
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty(trim($_POST['resposta']))) {
        $resposta_usuario = trim($_POST['resposta']);
        $usuario_id = $_SESSION['usuario']; // Assumindo que o ID do usuário está na sessão

        // Insere a resposta no banco de dados
        $stmt = $pdo->prepare("INSERT INTO knw_resposta (RES_DESCRICAO, RES_PER_ID, RES_USU_NOME) VALUES (?, ?, ?)");
        $stmt->execute([$resposta_usuario, $pergunta_id, $usuario_id]);

        // Redireciona de volta para a página da pergunta ou para o feed
        header("Location: pagina-resposta.php?id=$pergunta_id");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../styles/global.css">
    <link rel="stylesheet" href="pagina-resposta.css">
    <title>KNOW - Crie sua resposta</title>
</head>

<body>
    <header>
        <nav class="nav-bar">
            <a href="../pag-feed/pagina-feed.php">
                <svg class="btn-voltar" width="26" height="27" viewBox="0 0 26 27" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M21.3984 12.3323H8.41804L14.3803 6.3701L12.8657 4.86621L4.33301 13.3989L12.8657 21.9316L14.3696 20.4277L8.41804 14.4655H21.3984V12.3323Z"
                        fill="white" />
                </svg>
            </a>
            <a href="pagina-resposta.php">
                <img class="logo-know" src="../../assets/logo-know.png" alt="logo-know">
            </a>
        </nav>
    </header>
    <main>
        <div class="lateral-esquerda"></div>
        <div class="container">
            <h2 class="titulo-pergunta"><?= $pergunta['PER_TITULO']; ?></h2> 
            <hr>
            <div class="pergunta">
                <p class="corpo-pergunta"><?= $pergunta['PER_DESCRICAO']; ?></p>
                <div class="autor">
                    <p class="data-de-envio">
                        <?php
                        if ($pergunta['DIFERENCA_MINUTOS'] < 60) {
                            echo "Enviado há " . $pergunta['DIFERENCA_MINUTOS'] . " minutos atrás por";
                        } elseif ($pergunta['DIFERENCA_MINUTOS'] < 1440) {
                            $horas = intdiv($pergunta['DIFERENCA_MINUTOS'], 60);
                            echo "Enviado há " . $horas . " horas atrás por";
                        } else {
                            $dias = intdiv($pergunta['DIFERENCA_MINUTOS'], 1440);
                            echo "Enviado há " . $dias . " dias atrás por";
                        }
                        ?>
                    </p>
                    <div class="img-autor"></div>
                    <p class="nome-autor"><?= $pergunta['PER_USU_NOME']; ?></p>
                </div>
            </div>
            <div class="respostas">
                <?php if (!empty($respostas)): ?>
                    <h2 class="titulo-respostas">Respostas</h2>
                    <?php foreach ($respostas as $resposta): ?>
                        <div class='resposta'>
                            <div class='flex-usuario-nome'>
                                <div class='icone-usuario'></div>
                                <h3 class='nome-usuario'><?= $resposta['RES_USU_NOME'] ?></h3>
                            </div>
                            <p class='conteudo-resposta'><?= $resposta['RES_DESCRICAO'] ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <?php if (!empty($respostas)): ?>
                <h2 class="titulo-sua-resposta">Responda a esta pergunta!</h2>
            <?php else: ?>
                <h2 class="titulo-sua-resposta">Seja o primeiro a responder esta pergunta!</h2>
            <?php endif; ?>
            <form method="POST" class="resposta-container">
                <textarea class="area-resposta" name="resposta" placeholder="Digite sua resposta aqui..."
                    required></textarea>
                <div class="flex-formatacoes-btn">
                    <div class="formatacoes">
                        <img src="../../assets/icon-bold.png" alt="">
                        <img src="../../assets/icon-italic.png" alt="">
                        <img src="../../assets/icon-sublinhed.png" alt="">
                    </div>
                    <button class="btn-1" id="btn-enviar" type="submit">Enviar</button>
                </div>
            </form>
        </div>
        <div class="lateral-direita"></div>
    </main>
</body>

</html>
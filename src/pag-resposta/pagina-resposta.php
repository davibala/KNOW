<?php
require_once '../db.php';
session_start();
if (!$_SESSION['logado']) {
    header('Location: ../index.php');
}

$pergunta_id = $_GET['id'];
$stmt = $pdo->prepare("SELECT *, TIMESTAMPDIFF(MINUTE, PER_DATA, NOW()) AS DIFERENCA_MINUTOS FROM knw_pergunta WHERE PER_ID = ?");
$stmt->execute([$pergunta_id]);
$pergunta = $stmt->fetch(PDO::FETCH_ASSOC);

// Use os detalhes da pergunta conforme necessário na página
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
            <h2 class="titulo-pergunta"><?php echo "$pergunta[PER_TITULO]" ?></h2>
            <hr>
            <div class="post">
                <p class="corpo-pergunta"><?php echo "$pergunta[PER_DESCRICAO]" ?></p>
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
                    <p class="nome-autor"><?php echo "$pergunta[PER_USU_NOME]" ?></p>
                </div>
            </div>
            <h2 class="titulo-sua-resposta">Responda esta pergunta</h2>
            <div class="resposta-container">
                <form>
                    <textarea class="area-resposta" name="resposta"
                        placeholder="Digite sua resposta aqui..."></textarea>
                    <button class="btn-1" id="btn-enviar" type="submit">Enviar</button>
                </form>
                <div class="formatacoes">
                    <img src="../../assets/icon-bold.png" alt="">
                    <img src="../../assets/icon-italic.png" alt="">
                    <img src="../../assets/icon-sublinhed.png" alt="">
                </div>
            </div>
        </div>
        <div class="lateral-direita"></div>
    </main>
</body>

</html>
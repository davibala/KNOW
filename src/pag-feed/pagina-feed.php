<?php
require_once '../db.php';
session_start();
if (!$_SESSION['logado']) {
    header('Location: ../index.php');
}

$pesquisa = isset($_GET['pesquisa']) ? $_GET['pesquisa'] : '';

$stmt = $pdo->prepare("SELECT *, TIMESTAMPDIFF(MINUTE, PER_DATA, NOW()) AS DIFERENCA_MINUTOS FROM knw_pergunta
                              WHERE PER_TITULO 
                              LIKE ? OR PER_DESCRICAO LIKE ?");
$stmt->execute(["%$pesquisa%", "%$pesquisa%"]);

$perguntas = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../styles/global.css">
    <link rel="stylesheet" href="pagina-feed.css">
    <title>Know - feed</title>
</head>

<body>
    <header>
        <nav class="nav-bar">
            <a href="pagina-feed.php">
                <img class="logo-know" src="../../assets/logo-know.png" alt="logo-know">
            </a>
            <form class="container-pesquisa" method="GET">
                <input class="campo-pesquisa" type="text" name="pesquisa" placeholder="Pesquisar"
                    value="<?php echo htmlspecialchars($pesquisa); ?>">
                <button class="icone-pesquisa btn-1" type="submit"><img src="../../assets/icon-pesquisa.png"
                        alt="icone-pesquisa"></button>
            </form>
            <div class="container-usuario">
                <img class="menu-usuario" src="../../assets/icon-dropdown.png" alt="">
                <div class="icone-usuario"></div>
            </div>
        </nav>
    </header>
    <main>
        <div class="lateral-esquerda"></div>
        <div class="container">
            <div class="flex-titulo-pergunta">
                <h2 class="titulo-inicial"><?php echo "Olá " . $_SESSION['usuario'] ?></h2>
                <a class="btn-pergunta btn-1" href="../pag-pergunta/pagina-pergunta.php">
                    <button>Faça uma pergunta</button>
                </a>
            </div>
            <?php if (count($perguntas) > 0): ?>
                <?php foreach ($perguntas as $pergunta): ?>
                    <div class='post'>
                        <div class="flex-usuario-nome-data">
                            <div class='flex-usuario-nome'>
                                <div class='icone-usuario'></div>
                                <h3 class='nome-usuario'><?= $pergunta['PER_USU_NOME'] ?></h3>
                            </div>
                            <p class='data-pergunta'>
                                <?php
                                if ($pergunta['DIFERENCA_MINUTOS'] < 60) {
                                    echo "Enviado há " . $pergunta['DIFERENCA_MINUTOS'] . " minutos atrás";
                                } elseif ($pergunta['DIFERENCA_MINUTOS'] < 1440) { // 1440 minutos = 1 dia
                                    $horas = intdiv($pergunta['DIFERENCA_MINUTOS'], 60);
                                    echo "Enviado há " . $horas . " horas atrás";
                                } else {
                                    $dias = intdiv($pergunta['DIFERENCA_MINUTOS'], 1440);
                                    echo "Enviado há " . $dias . " dias atrás";
                                }
                                ?>
                            </p>
                        </div>
                        <h4 class='titulo-pergunta'><?= $pergunta['PER_TITULO'] ?></h4>
                        <p class='conteudo-pergunta'><?= $pergunta['PER_DESCRICAO'] ?></p>
                        <div class='flex-tag-responder'>
                            <div class='tags'>
                                <div class='tag tag-um'>IPI</div>
                                <div class='tag tag-dois'>Lógica de programação</div>
                            </div>
                            <a class='btn-1 btn-responder'
                                href='../pag-resposta/pagina-resposta.php?id=<?= $pergunta['PER_ID'] ?>'>
                                <button>Responder</button>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="msg-erro">Nenhuma pergunta encontrada para a pesquisa.</p>
            <?php endif; ?>
        </div>
        <div class="lateral-direita"></div>
    </main>
</body>

</html>
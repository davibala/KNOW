<?php
require_once '../db.php';
session_start();
if (!$_SESSION['logado']) {
    header('Location: ../index.php');
    exit();
}

$pesquisa = isset($_GET['pesquisa']) ? $_GET['pesquisa'] : '';
$tag_filtro = isset($_GET['tag']) ? $_GET['tag'] : '';

// Carregar todas as tags
$stmt = $pdo->prepare("SELECT * FROM KNW_TAGS");
$stmt->execute();
$tags = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Filtrar perguntas com base na tag selecionada
if ($tag_filtro) {
    $stmt = $pdo->prepare("SELECT p.*, TIMESTAMPDIFF(MINUTE, p.PER_DATA, NOW()) AS DIFERENCA_MINUTOS 
                           FROM knw_pergunta p
                           JOIN PERGUNTA_TAGS pt ON p.PER_ID = pt.PER_ID
                           WHERE pt.TAG_ID = ? AND (p.PER_TITULO LIKE ? OR p.PER_DESCRICAO LIKE ?)");
    $stmt->execute([$tag_filtro, "%$pesquisa%", "%$pesquisa%"]);
} else {
    $stmt = $pdo->prepare("SELECT *, TIMESTAMPDIFF(MINUTE, PER_DATA, NOW()) AS DIFERENCA_MINUTOS 
                           FROM knw_pergunta
                           WHERE PER_TITULO LIKE ? OR PER_DESCRICAO LIKE ?");
    $stmt->execute(["%$pesquisa%", "%$pesquisa%"]);
}

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
                    value="<?= $pesquisa ?>"> <!-- Guarda dentro do campo de pesquisa o valor que foi pesquisado  -->
                <button class="icone-pesquisa" type="submit"><img src="../../assets/icon-pesquisa.png"
                        alt="icone-pesquisa"></button>
            </form>
            <div class="container-usuario">
                <div class="dropdown">
                    <button onclick="menuDropdown()" class="dropbtn"><img class="menu-usuario"
                            src="../../assets/icon-dropdown.png" alt=""></button>
                    <!-- mostra uma caixa de opcoes ao ser clicado -->
                    <div id="perfil-dropdown" class="dropdown-conteudo">
                        <!-- conteudo mostrado ao clicar no dropdown -->
                        <a href="../pag-perfil/pagina-perfil.php">Perfil</a>
                        <a href="sair.php">Sair</a>
                    </div>
                </div>
                <img class="icone-usuario" src="../../assets/icon-usuario.png" alt=".">
            </div>
        </nav>
    </header>
    <main>
        <div class="lateral-esquerda">
            <h3 class="tit-tags">TAGS</h3>
            <br>
            <div class="lista-tags">
                <?php foreach ($tags as $tag): ?>
                    <a href="pagina-feed.php?tag=<?= $tag['TAG_ID'] ?>" class="tag-link">
                        <?= htmlspecialchars($tag['TAG_NOME']) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="container">
            <div class="flex-titulo-pergunta">
                <h2 class="titulo-inicial"><?php echo "Olá " . $_SESSION['usuario'] ?></h2>
                <!-- exibi o nome do usuario que esta salvo na sessao -->
                <a class="btn-pergunta btn-1" href="../pag-pergunta/pagina-pergunta.php">
                    <button>Faça uma pergunta</button>
                </a>
            </div>
            <?php if (count($perguntas) > 0): ?> <!-- verifica se há perguntas cadastradas -->
                <?php foreach ($perguntas as $pergunta): ?> <!-- itera sobre as perguntas -->
                    <div class='post'>
                        <div class="flex-usuario-nome-data">
                            <div class='flex-usuario-nome'>
                                <img class="icone-usuario" src="../../assets/icon-usuario.png" alt=".">
                                <h3 class='nome-usuario'><?= $pergunta['PER_USU_NOME'] ?></h3>
                                <!-- exibi o nome do usuario que fez a pergunta -->
                            </div>
                            <p class='data-pergunta'>
                                <?php
                                if ($pergunta['DIFERENCA_MINUTOS'] < 60) { // 60 minutos = 1 hora
                                    echo "Enviado há " . $pergunta['DIFERENCA_MINUTOS'] . " minutos atrás"; // exibi a quantidade de minutos
                                } elseif ($pergunta['DIFERENCA_MINUTOS'] < 1440) { // 1440 minutos = 1 dia
                                    $horas = intdiv($pergunta['DIFERENCA_MINUTOS'], 60); // retorna o valor em horas
                                    echo "Enviado há " . $horas . " horas atrás";
                                } else {
                                    $dias = intdiv($pergunta['DIFERENCA_MINUTOS'], 1440); // retorna o valor em dias
                                    echo "Enviado há " . $dias . " dias atrás";
                                }
                                ?>
                            </p>
                        </div>
                        <h4 class='titulo-pergunta'><?= $pergunta['PER_TITULO'] ?></h4> <!-- exibi o titulo da pergunta -->
                        <p class='conteudo-pergunta'><?= $pergunta['PER_DESCRICAO'] ?></p> <!-- exibi o conteudo da pergunta -->
                        <div class='flex-tag-responder'>
                            <div class='tags'>
                                    <?php
                                    $stmt = $pdo->prepare("SELECT * FROM PERGUNTA_TAGS WHERE PER_ID = ?");
                                    $stmt->execute([$pergunta['PER_ID']]);
                                    $tags = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    ?>

                                    <?php foreach ($tags as $tag): ?>
                                        <?php
                                            $stmt = $pdo->prepare("SELECT * FROM KNW_TAGS WHERE TAG_ID = ?");
                                            $stmt->execute([$tag['TAG_ID']]);
                                            $tagNome = $stmt->fetch(PDO::FETCH_ASSOC);
                                        ?>
                                        <p class="tag tag-cor"><?= $tagNome['TAG_NOME'] ?></p>
                                    <?php endforeach; ?>
                            </div>
                            <a class='btn-1 btn-responder'
                                href='../pag-resposta/pagina-resposta.php?id=<?= $pergunta['PER_ID'] ?>'>
                                <!-- redireciona para a pagina de resposta com o id daquela pergunta -->
                                <button>Responder</button>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="msg-erro">Nenhuma pergunta encontrada para a pesquisa.</p>
            <?php endif;
            ?>
        </div>
        <div class="lateral-direita"></div>
    </main>
    <script src="pagina-feed.js"></script>
</body>

</html>
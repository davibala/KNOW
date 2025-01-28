<?php
    require_once '../db.php';
    session_start();
    if (!$_SESSION['logado']) {
        header('Location: ../index.php');
    }
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
            <div class="container-pesquisa">
                <input class="campo-pesquisa" type="text" placeholder="Pesquisar">
                <button class="icone-pesquisa btn-1" type="submit"><img src="../../assets/icone-pesquisa.png"
                        alt="z"></button>
            </div>
            <div class="container-usuario">
                <img class="menu-usuario" src="../../assets/btn-filtro.png" alt="">
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
            <?php
                $stmt = $pdo->prepare("SELECT * FROM knw_pergunta");
                $stmt->execute();
                $perguntas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>

            <?php foreach ($perguntas as $pergunta): ?>
                <div class='post'>
                    <div class='flex-usuario-nome'>
                        <div class='icone-usuario'></div>
                        <h3 class='nome-usuario'><?= $pergunta['PER_USU_NOME'] ?></h3>
                    </div>
                    <h4 class='titulo-pergunta'><?= $pergunta['PER_TITULO'] ?></h4>
                    <p class='conteudo-pergunta'><?= $pergunta['PER_DESCRICAO'] ?></p>
                    <div class='flex-tag-responder'>
                        <div class='tags'>
                            <div class='tag tag-um'>IPI</div>
                            <div class='tag tag-dois'>Lógica de programação</div>
                        </div>
                        <a class='btn-1 btn-responder' href='../pag-resposta/pagina-resposta.php'>
                            <button>Responder</button>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="lateral-direita"></div>
    </main>
</body>

</html>
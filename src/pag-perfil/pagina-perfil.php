<?php
require_once '../db.php'; // Conexão com o banco de dados
session_start(); // Iniciar sessão

// Se o usuário não estiver logado
if (!isset($_SESSION['usuario'])) {
    header('Location: /src/'); // Redirecionar para a página de login
    exit();
}

$pesquisa = isset($_GET['pesquisa']) ? $_GET['pesquisa'] : '';

// Carrega perguntas do usuário
$stmt = $pdo->prepare("SELECT *, TIMESTAMPDIFF(MINUTE, PER_DATA, NOW()) AS DIFERENCA_MINUTOS FROM knw_pergunta
                              WHERE PER_USU_NOME = ?");
$stmt->execute([$_SESSION['usuario']]);
$perguntas = $stmt->fetchAll(PDO::FETCH_ASSOC); // Armazena as perguntas em um array associativo

// Carrega respostas do usuário
$stmt = $pdo->prepare("SELECT * FROM knw_resposta
                              WHERE RES_USU_NOME = ?");
$stmt->execute([$_SESSION['usuario']]); 
$respostas = $stmt->fetchAll(PDO::FETCH_ASSOC); // Armazena as respostas em um array associativo

// Carrega o perfil do usuário
$stmt = $pdo->prepare("SELECT * FROM knw_usuarios WHERE USU_NOME = ?"); // Selecionar o usuário
$stmt->execute([$_SESSION['usuario']]); // Executar a query
$usuario = $stmt->fetch(PDO::FETCH_ASSOC); // Armazena o usuário em um array associativo

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../styles/global.css">
    <link rel="stylesheet" href="pagina-perfil.css">
    <title>Know - Perfil</title>
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
            <img class="logo-know" src="../../assets/logo-know.png" alt="logo-know">
        </div>
    </header>
    <main>
        <div class="lateral-esquerda"></div>
        <div class="container">
            <div class="container-usuario">
                <div class="informacoes-usuario">
                    <img class="icone-usuario" src="../../assets/icon-usuario.png" alt="">
                    <h2 class="nome-usuario"><?= htmlspecialchars($usuario['USU_NOME']) ?></h2>
                    <p class="email-usuario"><?= htmlspecialchars($usuario['USU_EMAIL']) ?></p>
                </div>
                <div class="flex-opcoes">
                    <div class="flex-per-res">
                        <button class="btn-1 btn-minhas-perguntas">Minhas perguntas</button>
                        <button class="btn-1 btn-minhas-respostas">Minhas repostas</button>
                    </div>
                    <div class="flex-edi-sai">
                        <button class="btn-1 btn-editar-perfil">Editar perfil</button>
                        <button class="btn-1 btn-sair">Sair</button>
                    </div>
                </div>
            </div>
            <div class="container-perguntas-respotas">
                <h2 id="titulo-secao">Minhas perguntas</h2>
                
                <div class="conteudo">
                    <!-- Seção de perguntas -->
                    <div id="secao-perguntas">
                        <?php foreach ($perguntas as $pergunta): ?>
                            <?php
                            $stmt = $pdo->prepare("SELECT COUNT(*) AS total_respostas FROM knw_resposta WHERE RES_PER_ID = ?");
                            $stmt->execute([$pergunta['PER_ID']]);
                            $total_respostas = $stmt->fetch(PDO::FETCH_ASSOC)['total_respostas'];
                            $tem_respostas = $total_respostas > 0;
                            ?>
                            <div class='post'>
                                <div class="flex-tit-opcoes">
                                    <h3><?= htmlspecialchars($pergunta['PER_TITULO']) ?></h3>
                                    <div class="dropdown">
                                        <button onclick="menuDropdown('dropdown-<?= $pergunta['PER_ID'] ?>')"
                                            class="dropbtn">
                                            <img class="opcoes" src="../../assets/icon-opcoes.png" alt="icon-opcoes">
                                        </button>
                                        <!-- Usa o ID único para o dropdown -->
                                        <div id="dropdown-<?= $pergunta['PER_ID']?>" class="dropdown-conteudo">
                                            <?php if (!$tem_respostas): ?>
                                                <a href="#">Editar</a>
                                                <form action="excluir_pergunta.php" method="POST">
                                                    <input type="hidden" name="pergunta_id" value="<?= $pergunta['PER_ID'] ?>">
                                                    <button type="submit" class="btn-excluir">Excluir</button>
                                                </form>
                                            <?php else: ?>
                                                <span class="disabled-link" styles="background-color: gray;">Editar</span>
                                                <span class="disabled-link">Excluir</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class='conteudo-pergunta'>
                                    <p>b<?= htmlspecialchars($pergunta['PER_DESCRICAO']) ?></p>
                                </div>
                                <div class='flex-tag-respostas'>
                                    <div class='tags'>
                                        <div class='tag tag-um'>IPI</div>
                                        <div class='tag tag-dois'>Lógica de programação</div>
                                    </div>
                                    <a class='btn-1 btn-respostas'
                                        href="../pag-resposta/pagina-resposta.php?id=<?= $pergunta['PER_ID'] ?>">
                                        <button>Ver respostas</button>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Seção de respostas -->
                    <div id="secao-respostas">
                        <?php if (count($respostas) > 0): ?>
                            <?php foreach ($respostas as $resposta): ?>
                                <?php
                                $stmt = $pdo->prepare("SELECT PER_TITULO FROM knw_pergunta WHERE PER_ID = ?");
                                $stmt->execute([$resposta['RES_PER_ID']]);
                                $pergunta = $stmt->fetch(PDO::FETCH_ASSOC);
                                ?>
                                <div class='post'>
                                    <div class="flex-tit-opcoes">
                                        <h3>Resposta para: <?= htmlspecialchars($pergunta['PER_TITULO']) ?></h3>
                                        <div class="dropdown">
                                            <button onclick="menuDropdown('dropdown-<?= $resposta['RES_ID'] ?>')" class="dropbtn">
                                                <img class="opcoes" src="../../assets/icon-opcoes.png" alt="icon-opcoes">
                                            </button>
                                            <div id="dropdown-<?= $resposta['RES_ID'] ?>" class="dropdown-conteudo">    
                                                <a href="#">Editar</a>
                                                <!-- Adicionar um formulário para exclusão -->
                                                <form action="excluir_resposta.php" method="POST">
                                                    <input type="hidden" name="resposta_id" value="<?= $resposta['RES_ID'] ?>">
                                                    <button type="submit" class="btn-excluir">Excluir</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class='conteudo-pergunta'>
                                        <p><?= htmlspecialchars($resposta['RES_DESCRICAO']) ?></p>
                                    </div>
                                    <a id="btn-perguntas" class='btn-1 btn-respostas'
                                        href="../pag-resposta/pagina-resposta.php?id=<?= $resposta['RES_PER_ID'] ?>">
                                        <button id="btnper">Ver pergunta</button>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="msg-erro">Nenhuma resposta encontrada.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="lateral-direita"></div>
    </main>

    <script src="pagina-perfil.js"></script>
</body>

</html>
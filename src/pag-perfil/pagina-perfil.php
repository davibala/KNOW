<?php
require_once '../db.php'; // Conexão com o banco de dados
session_start(); // Iniciar sessão

// Se o usuário não estiver logado
if (!isset($_SESSION['usuario'])) {
    header('Location: /src/'); // Redirecionar para a página de login
    exit();
}

// Carrega os dados do usuário
$stmt = $pdo->prepare("SELECT * FROM knw_usuarios WHERE USU_NOME = ?");
$stmt->execute([$_SESSION['usuario']]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

$mensagemErro = ''; // Variável para armazenar a mensagem de erro

// Processa o formulário de edição de perfil
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = isset($_POST['nome']) ? trim($_POST['nome']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $senha = isset($_POST['senha']) ? trim($_POST['senha']) : '';
    $novaSenha = isset($_POST['nova-senha']) ? trim($_POST['nova-senha']) : '';

    // Verifica se a senha atual está correta
    if ($senha != '' && password_verify($senha, $usuario['USU_SENHA'])) {
        // Atualiza a senha, se fornecida
        if ($novaSenha != '') {
            $novaSenhaHash = password_hash($novaSenha, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("UPDATE knw_usuarios SET USU_SENHA = ? WHERE USU_NOME = ?");
            $stmt->execute([$novaSenhaHash, $_SESSION['usuario']]);
        }

        // Atualiza o nome, se fornecido
        if ($nome != '') {
            $stmt = $pdo->prepare("UPDATE knw_usuarios SET USU_NOME = ? WHERE USU_NOME = ?");
            $stmt->execute([$nome, $_SESSION['usuario']]);
            $_SESSION['usuario'] = $nome;
        }

        // Atualiza o email, se fornecido
        if ($email != '') {
            $stmt = $pdo->prepare("UPDATE knw_usuarios SET USU_EMAIL = ? WHERE USU_NOME = ?");
            $stmt->execute([$email, $_SESSION['usuario']]);
        }

        // Processa o upload da imagem de perfil
        if (isset($_FILES['foto-perfil']) && $_FILES['foto-perfil']['error'] == UPLOAD_ERR_OK) {
            $fotoPerfil = $_FILES['foto-perfil'];
            $nomeFoto = $fotoPerfil['name'];
            $novoNomeFoto = uniqid(); // Gera um nome único para o arquivo
            $extensao = strtolower(pathinfo($nomeFoto, PATHINFO_EXTENSION));
            $tmpFoto = $fotoPerfil['tmp_name'];
            $pasta = '../../assets/fotos-perfil/'; // Caminho relativo para salvar a imagem

            // Validações
            $tamanhoMaximo = 2 * 1024 * 1024; // 2MB
            $extensoesPermitidas = ['jpg', 'jpeg', 'png'];

            if ($fotoPerfil['size'] > $tamanhoMaximo) {
                echo "Erro: A imagem deve ter no máximo 2MB.";
            }

            if (!in_array($extensao, $extensoesPermitidas)) {
                echo "Erro: A imagem deve ser JPG, JPEG ou PNG.";
            }

            // Move o arquivo para a pasta de uploads
            $caminhoCompleto = $pasta . $novoNomeFoto . '.' . $extensao;
            if (move_uploaded_file($tmpFoto, $caminhoCompleto)) {
                // Verifica se o usuário já tem uma imagem de perfil
                $stmt = $pdo->prepare("SELECT * FROM KNW_IMAGEM WHERE IMG_USU_NOME = ?");
                $stmt->execute([$_SESSION['usuario']]);
                $imagemExistente = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($imagemExistente) {
                    // Exclui a imagem antiga do servidor
                    if (file_exists($imagemExistente['IMG_CAMINHO'])) {
                        unlink($imagemExistente['IMG_CAMINHO']); // Remove o arquivo antigo
                    }

                    // Atualiza a imagem existente no banco de dados
                    $stmt = $pdo->prepare("UPDATE KNW_IMAGEM SET IMG_CAMINHO = ? WHERE IMG_USU_NOME = ?");
                    $stmt->execute([$caminhoCompleto, $_SESSION['usuario']]);
                } else {
                    // Insere uma nova imagem no banco de dados
                    $stmt = $pdo->prepare("INSERT INTO KNW_IMAGEM (IMG_CAMINHO, IMG_USU_NOME) VALUES (?, ?)");
                    $stmt->execute([$caminhoCompleto, $_SESSION['usuario']]);
                }
            } else {
                $mensagemErro = 'Erro ao enviar imagem!';
            }
        }
    } else {
        $mensagemErro = 'Senha incorreta!';
    }
}

// Carrega as perguntas e respostas do usuário
$stmt = $pdo->prepare("SELECT *, TIMESTAMPDIFF(MINUTE, PER_DATA, NOW()) AS DIFERENCA_MINUTOS FROM knw_pergunta WHERE PER_USU_NOME = ?");
$stmt->execute([$_SESSION['usuario']]);
$perguntas = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT * FROM knw_resposta WHERE RES_USU_NOME = ?");
$stmt->execute([$_SESSION['usuario']]);
$respostas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Carrega a imagem de perfil do usuário
$stmt = $pdo->prepare("SELECT IMG_CAMINHO FROM knw_imagem WHERE IMG_USU_NOME = ?");
$stmt->execute([$_SESSION['usuario']]);
$imagem = $stmt->fetch(PDO::FETCH_ASSOC);

$fotoPerfilPadrao = '../../assets/icon-usuario.png'; // Caminho da imagem padrão
$fotoPerfilUsuario = $imagem ? $imagem['IMG_CAMINHO'] : $fotoPerfilPadrao; // Define o caminho da imagem

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

            <a class="btn-sair" href="../pag-feed/sair.php">
                <img class="icon-sair" src="../../assets/icon-sair.png" alt="icon-sair">
            </a>
        </div>
    </header>
    <main>
        <div class="lateral-esquerda"></div>
        <div class="container">
            <div class="container-usuario">
                <div class="informacoes-usuario">
                    <?php if (isset($imagem)): ?>
                        <img class="icone-usuario" src="<?= $fotoPerfilUsuario ?>" alt="">
                    <?php else: ?>
                        <img class="icone-usuario" src="../../assets/icon-usuario.png" alt=".">
                    <?php endif; ?>
                    <div class="flex-nome-config">
                        <h2 class="nome-usuario"><?= htmlspecialchars($usuario['USU_NOME']) ?></h2>
                        <button class="btn-editar-perfil">
                            <img class="icon-config" src="../../assets/icon-cog.png" alt="">
                        </button>
                    </div>
                    <p class="email-usuario"><?= htmlspecialchars($usuario['USU_EMAIL']) ?></p>
                </div>
                <div class="flex-opcoes">
                    <div class="flex-per-res">
                        <button class="btn-1 btn-minhas-perguntas">Minhas perguntas</button>
                        <button class="btn-1 btn-minhas-respostas">Minhas repostas</button>
                    </div>
                </div>
            </div>
            <div class="container-perguntas-respotas">
                <h2 id="titulo-secao"></h2>
                <div class="conteudo">
                    <div id="secao-editar-perfil">
                        <form class="form-perfil" action="pagina-perfil.php" enctype="multipart/form-data"
                            method="POST">
                            <div class="aaa">
                                <div class="foto-perfil-container">
                                    <img id="preview-imagem" class="perfil-icon-usuario" src="<?= $fotoPerfilUsuario ?>"
                                        alt="icon-usuario" data-foto-padrao="<?= $fotoPerfilPadrao ?>">
                                    <input name="foto-perfil" class="foto-perfil-input" id="foto-perfil-input"
                                        type="file">
                                    <label for="foto-perfil-input" class="foto-perfil-label">Alterar Foto</label>
                                </div>
                                <div class="perfil-inputs">
                                    <div class="flex-nome-input" id="campo-nome">
                                        <label for="nome">Nome</label>
                                        <input type="text" name="nome" id="nome"
                                            value="<?= htmlspecialchars($usuario['USU_NOME']) ?>">
                                    </div>
                                    <div class="flex-nome-input" id="campo-email">
                                        <label for="email">Email</label>
                                        <input type="email" name="email" id="email"
                                            value="<?= htmlspecialchars($usuario['USU_EMAIL']) ?>">
                                    </div>
                                    <div class="flex-nome-input" id="campo-senha">
                                        <label for="senha">Senha</label>
                                        <input type="password" name="senha" id="senha" required>
                                    </div>
                                    <div class="flex-nome-input" id="campo-nova-senha">
                                        <label for="nova-senha">Nova senha</label>
                                        <input type="password" name="nova-senha" id="nova-senha">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" id="btn-salvar">Salvar</button>
                        </form>
                    </div>
                    <!-- Seção de perguntas -->
                    <div id="secao-perguntas">
                        <?php if (count($perguntas) > 0): ?>
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
                                            <button onclick="menuDropdown('dropdown-pergunta-<?= $pergunta['PER_ID'] ?>')"
                                                class="dropbtn">
                                                <img class="opcoes" src="../../assets/icon-opcoes.png" alt="icon-opcoes">
                                            </button>
                                            <!-- Usa o ID único para o dropdown -->
                                            <div id="dropdown-pergunta-<?= $pergunta['PER_ID'] ?>" class="dropdown-conteudo">
                                                <?php if (!$tem_respostas): ?>
                                                    <a class='btn-excluir'
                                                        href="../pag-edicao/pagina-edicao.php?id=<?= $pergunta['PER_ID'] ?>">
                                                        <button>Editar</button>
                                                    </a>
                                                    <form action="excluir_pergunta.php" method="POST">
                                                        <input type="hidden" name="pergunta_id" value="<?= $pergunta['PER_ID'] ?>">
                                                        <button type="submit" class="btn-excluir">Excluir</button>
                                                    </form>
                                                <?php else: ?>
                                                    <div class="tooltip">
                                                        <p class="disabled-link">
                                                            Editar
                                                        </p>
                                                        <p class="disabled-link">
                                                            Excluir
                                                        </p>
                                                        <p class="tooltiptext">
                                                            Você não pode editar/excluir esta pergunta pois já foi respondida.
                                                        </p>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class='conteudo-pergunta'>
                                        <p><?= htmlspecialchars($pergunta['PER_DESCRICAO']) ?></p>
                                    </div>
                                    <div class='flex-tag-respostas'>
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
                                        <a class='btn-1 btn-respostas'
                                            href="../pag-resposta/pagina-resposta.php?id=<?= $pergunta['PER_ID'] ?>">
                                            <button id="btnper">Ver respostas</button>
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="msg-erro">Nenhuma pergunta encontrada.</p>
                        <?php endif; ?>
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
                                            <button onclick="menuDropdown('dropdown-resposta-<?= $resposta['RES_ID'] ?>')"
                                                class="dropbtn">
                                                <img class="opcoes" src="../../assets/icon-opcoes.png" alt="icon-opcoes">
                                            </button>
                                            <div id="dropdown-resposta-<?= $resposta['RES_ID'] ?>" class="dropdown-conteudo">
                                                <a class="btn-excluir" href="#">Editar</a>
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
                                    <a id="btn-resposta" class='btn-1 btn-respostas'
                                        href="../pag-resposta/pagina-resposta.php?id=<?= $resposta['RES_PER_ID'] ?>">
                                        <button id="btnres">Ver pergunta</button>
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
    <div id="mensagem-erro" class="mensagem-erro" data-mensagem="<?= htmlspecialchars($mensagemErro) ?>"></div>
    <script src="pagina-perfil.js"></script>
</body>

</html>
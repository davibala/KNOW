<?php 

require_once('../db.php');
session_start(); // Iniciar sessão


if (isset($_POST['pergunta_id'])) {
    $pergunta_id = $_POST['pergunta_id'];

    // Verificar se a resposta pertence ao usuário logado
    $stmt = $pdo->prepare("SELECT PER_USU_NOME FROM KNW_PERGUNTA WHERE PER_ID = ?");
    $stmt->execute([$pergunta_id]);
    $pergunta = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($pergunta && $pergunta['PER_USU_NOME'] == $_SESSION['usuario']) {
        // Excluir a pergunta
        $stmt = $pdo->prepare("DELETE FROM KNW_PERGUNTA WHERE PER_ID = ?");
        $stmt->execute([$pergunta_id]);

        // Redirecionar de volta para a página de perfil
        header('Location: pagina-perfil.php');
        exit();
    } else {
        echo "Você não tem permissão para excluir esta pergunta.";
    }
} else {
    echo "ID da pergunta não fornecido.";
}

?>
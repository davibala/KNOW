<?php 

require_once '../db.php';
session_start();


if (isset($_POST['resposta_id'])) {
    $resposta_id = $_POST['resposta_id'];

    // Verificar se a resposta pertence ao usuário logado
    $stmt = $pdo->prepare("SELECT RES_USU_NOME FROM KNW_RESPOSTA WHERE RES_ID = ?");
    $stmt->execute([$resposta_id]);
    $resposta = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($resposta && $resposta['RES_USU_NOME'] == $_SESSION['usuario']) {
        // Excluir a resposta
        $stmt = $pdo->prepare("DELETE FROM KNW_RESPOSTA WHERE RES_ID = ?");
        $stmt->execute([$resposta_id]);

        // Redirecionar de volta para a página de perfil
        header('Location: pagina-perfil.php');
        exit();
    } else {
        echo "Você não tem permissão para excluir esta resposta.";
    }
} else {
    echo "ID da resposta não fornecido.";
}

?>
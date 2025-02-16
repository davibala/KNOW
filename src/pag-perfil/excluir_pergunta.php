<?php 

require_once '../db.php'; // Conexão com o banco de dados
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $perguntaId = $_POST['pergunta_id']; // ID da pergunta a ser excluída

    try {
        // Exclui os registros associados na tabela pergunta_tags
        $stmt = $pdo->prepare("DELETE FROM pergunta_tags WHERE PER_ID = ?");
        $stmt->execute([$perguntaId]);

        // Exclui a pergunta
        $stmt = $pdo->prepare("DELETE FROM knw_pergunta WHERE PER_ID = ?");
        $stmt->execute([$perguntaId]);

        // Redireciona de volta ao perfil
        header('Location: pagina-perfil.php');
    } catch (PDOException $e) {
        echo "Erro ao excluir pergunta: " . $e->getMessage();
    }
}

?>
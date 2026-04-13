<?php
session_start();
require 'conexao.php';

// Segurança: Se não estiver logado, chuta o usuário de volta para o index
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'] ?? null;

// Lógica para SALVAR as alterações
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $_POST['titulo'];
    $conteudo = $_POST['conteudo'];
    
    $stmt = $pdo->prepare("UPDATE posts SET titulo = :titulo, conteudo = :conteudo WHERE id = :id");
    $stmt->execute(['titulo' => $titulo, 'conteudo' => $conteudo, 'id' => $id]);
    
    header("Location: index.php");
    exit;
}

// Busca o post atual para preencher o formulário
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = :id");
$stmt->execute(['id' => $id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) die("Post não encontrado.");
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Post</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-5">
    <div class="container" style="max-width: 800px;">
        <div class="card shadow-sm">
            <div class="card-header bg-warning text-white">
                <h5 class="mb-0">Editar Postagem</h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Título</label>
                        <input type="text" name="titulo" class="form-control" value="<?= htmlspecialchars($post['titulo']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Conteúdo</label>
                        <textarea name="conteudo" class="form-control" rows="5" required><?= htmlspecialchars($post['conteudo']) ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-success">Salvar Alterações</button>
                    <a href="index.php" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
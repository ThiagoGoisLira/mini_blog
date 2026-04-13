<?php
session_start();
require 'conexao.php';

// Verifica se o usuário está logado
$logado = isset($_SESSION['logado']) && $_SESSION['logado'] === true;

// Lógica para SALVAR novo post (Apenas se logado)
if ($logado && $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['titulo'])) {
    $titulo = $_POST['titulo'];
    $conteudo = $_POST['conteudo'];
    $stmt = $pdo->prepare("INSERT INTO posts (titulo, conteudo) VALUES (:titulo, :conteudo)");
    $stmt->execute(['titulo' => $titulo, 'conteudo' => $conteudo]);
    header("Location: index.php");
    exit;
}

// Lógica para DELETAR um post (Apenas se logado)
if ($logado && isset($_GET['deletar'])) {
    $id_deletar = $_GET['deletar'];
    $stmt = $pdo->prepare("DELETE FROM posts WHERE id = :id");
    $stmt->execute(['id' => $id_deletar]);
    header("Location: index.php");
    exit;
}

// Busca os posts
$posts = $pdo->query("SELECT * FROM posts ORDER BY data_criacao DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Meu Mini-Blog PRO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4 shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="index.php"><i class="bi bi-rocket-takeoff"></i> Mini-Blog PRO</a>
        <div class="d-flex">
            <?php if ($logado): ?>
                <span class="navbar-text text-white me-3">Olá, <?= $_SESSION['usuario'] ?>!</span>
                <a href="logout.php" class="btn btn-outline-light btn-sm">Sair</a>
            <?php else: ?>
                <a href="login.php" class="btn btn-light btn-sm">Área Restrita</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<div class="container" style="max-width: 800px;">

    <?php if ($logado): ?>
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Escrever nova postagem</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="index.php">
                    <div class="mb-3">
                        <input type="text" name="titulo" class="form-control" placeholder="Título do post..." required>
                    </div>
                    <div class="mb-3">
                        <textarea name="conteudo" class="form-control" rows="3" placeholder="O que você quer compartilhar hoje?" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-success"><i class="bi bi-send"></i> Publicar</button>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <h3 class="mb-3 text-secondary">Últimas Postagens</h3>
    
    <?php if (count($posts) > 0): ?>
        <?php foreach ($posts as $post): ?>
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <h4 class="card-title text-primary"><?= htmlspecialchars($post['titulo']) ?></h4>
                    <h6 class="card-subtitle mb-2 text-muted">
                        <i class="bi bi-calendar3"></i> <?= date('d/m/Y H:i', strtotime($post['data_criacao'])) ?>
                    </h6>
                    <p class="card-text mt-3"><?= nl2br(htmlspecialchars($post['conteudo'])) ?></p>
                    
                    <hr>
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="post.php?id=<?= $post['id'] ?>" class="btn btn-outline-primary btn-sm">Ler e Comentar</a>
                        
                        <?php if ($logado): ?>
                            <div>
                                <a href="editar.php?id=<?= $post['id'] ?>" class="btn btn-warning btn-sm text-white"><i class="bi bi-pencil"></i></a>
                                <a href="index.php?deletar=<?= $post['id'] ?>" onclick="return confirm('Tem certeza que deseja deletar este post? Isso apagará os comentários também.');" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="alert alert-info">Nenhuma postagem ainda.</div>
    <?php endif; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
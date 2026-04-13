<?php
// 1. Inclui o arquivo de conexão
require 'conexao.php';

// 2. Verifica se o ID do post foi passado na URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    // Se não tiver ID, encerra a execução e exibe uma mensagem
    die("Post não encontrado!");
}

$post_id = $_GET['id'];

// 3. Busca os dados do Post específico no banco
$sql_post = "SELECT * FROM posts WHERE id = :id";
$stmt_post = $pdo->prepare($sql_post);
$stmt_post->execute(['id' => $post_id]);
$post = $stmt_post->fetch(PDO::FETCH_ASSOC);

// Se o post não existir no banco de dados
if (!$post) {
    die("Post não encontrado!");
}

// 4. Lógica para SALVAR um novo comentário (se o formulário foi enviado)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nome_autor'])) {
    $nome_autor = $_POST['nome_autor'];
    $texto_comentario = $_POST['texto_comentario'];

    $sql_insert = "INSERT INTO comentarios (post_id, nome_autor, texto_comentario) VALUES (:post_id, :nome_autor, :texto_comentario)";
    $stmt_insert = $pdo->prepare($sql_insert);
    $stmt_insert->execute([
        'post_id' => $post_id,
        'nome_autor' => $nome_autor,
        'texto_comentario' => $texto_comentario
    ]);

    // Recarrega a página para exibir o novo comentário imediatamente
    header("Location: post.php?id=" . $post_id);
    exit;
}

// 5. Busca todos os comentários que pertencem a ESTE post
$sql_comentarios = "SELECT * FROM comentarios WHERE post_id = :post_id ORDER BY data_criacao ASC";
$stmt_comentarios = $pdo->prepare($sql_comentarios);
$stmt_comentarios->execute(['post_id' => $post_id]);
$comentarios = $stmt_comentarios->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($post['titulo']) ?> - Meu Mini-Blog</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f0f2f5; margin: 0; padding: 20px; color: #333; }
        .container { max-width: 800px; margin: 0 auto; }
        .caixa { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .data { font-size: 0.8em; color: #777; margin-top: -10px; margin-bottom: 15px; }
        .btn-voltar { display: inline-block; margin-bottom: 20px; color: #1a73e8; text-decoration: none; font-weight: bold; }
        
        /* Estilos específicos para os comentários */
        .area-comentarios { background-color: #f9f9f9; padding: 15px; border-radius: 8px; margin-top: 20px; }
        .comentario-item { border-bottom: 1px solid #ddd; padding: 10px 0; }
        .comentario-item:last-child { border-bottom: none; }
        .autor-comentario { font-weight: bold; color: #1a73e8; }
        
        input[type="text"], textarea { width: 100%; padding: 10px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { background: #1a73e8; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; font-weight: bold; }
        button:hover { background: #1557b0; }
    </style>
</head>
<body>

<div class="container">
    <a href="index.php" class="btn-voltar">&larr; Voltar para a página inicial</a>

    <div class="caixa">
        <h1><?= htmlspecialchars($post['titulo']) ?></h1>
        <p class="data">Publicado em: <?= date('d/m/Y H:i', strtotime($post['data_criacao'])) ?></p>
        <p><?= nl2br(htmlspecialchars($post['conteudo'])) ?></p>
    </div>

    <div class="caixa area-comentarios">
        <h2>Deixe seu comentário</h2>
        
        <form method="POST" action="post.php?id=<?= $post['id'] ?>">
            <input type="text" name="nome_autor" placeholder="Seu nome" required>
            <textarea name="texto_comentario" rows="3" placeholder="O que você achou do post?" required></textarea>
            <button type="submit">Enviar Comentário</button>
        </form>

        <h3 style="margin-top: 30px;">Comentários (<?= count($comentarios) ?>)</h3>
        
        <?php if (count($comentarios) > 0): ?>
            <?php foreach ($comentarios as $comentario): ?>
                <div class="comentario-item">
                    <p class="autor-comentario"><?= htmlspecialchars($comentario['nome_autor']) ?></p>
                    <p><?= nl2br(htmlspecialchars($comentario['texto_comentario'])) ?></p>
                    <p class="data"><?= date('d/m/Y H:i', strtotime($comentario['data_criacao'])) ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Ainda não há comentários. Seja o primeiro!</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
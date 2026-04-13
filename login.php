<?php
session_start();
require 'conexao.php';

$erro = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    // Busca o usuário no banco
    $sql = "SELECT * FROM usuarios WHERE usuario = :usuario";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['usuario' => $usuario]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // password_verify confere se a senha digitada bate com a criptografada no banco
    if ($user && password_verify($senha, $user['senha'])) {
        $_SESSION['logado'] = true;
        $_SESSION['usuario'] = $user['usuario'];
        header("Location: index.php");
        exit;
    } else {
        $erro = "Usuário ou senha incorretos!";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login - Mini-Blog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center vh-100">
    <div class="container" style="max-width: 400px;">
        <div class="card shadow-sm">
            <div class="card-body">
                <h3 class="card-title text-center mb-4">Acesso Restrito</h3>
                <?php if ($erro): ?>
                    <div class="alert alert-danger"><?= $erro ?></div>
                <?php endif; ?>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Usuário</label>
                        <input type="text" name="usuario" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Senha</label>
                        <input type="password" name="senha" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Entrar</button>
                </form>
                <a href="index.php" class="d-block text-center mt-3 text-decoration-none">Voltar ao Blog</a>
            </div>
        </div>
    </div>
</body>
</html>
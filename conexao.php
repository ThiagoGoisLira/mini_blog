<?php
// Configurações do banco de dados no XAMPP
$host = 'localhost';
$banco = 'miniblog';
$usuario = 'root'; // O usuário padrão do XAMPP é sempre 'root'
$senha = '';       // A senha padrão do XAMPP é vazia

try {
    // Tenta criar a conexão usando PDO
    $pdo = new PDO("mysql:host=$host;dbname=$banco;charset=utf8", $usuario, $senha);
    
    // Configura o PDO para nos avisar caso aconteça algum erro de SQL
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Descomente (tire as duas barras) da linha abaixo apenas para testar!
    //echo "Conexão realizada com sucesso!";
    
} catch (PDOException $e) {
    // Se a conexão falhar, ele captura o erro e exibe na tela
    echo "Erro de conexão: " . $e->getMessage();
}
?>
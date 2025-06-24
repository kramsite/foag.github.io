<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: entrar.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Bem-vindo</title>
</head>
<body>
    <h2>Bem-vindo, <?php echo $_SESSION['usuario']; ?>!</h2>
    <p>Você está logado com sucesso.</p>
    <a href="logout.php">Sair</a>
</body>
</html>

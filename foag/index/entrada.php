<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Bem-vindo</title>
    <style>
         body {
            font-family:'Poppins', sans-serif;;
            background: linear-gradient(to right, #38a5ff,rgb(46, 154, 241));
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        
        h1 {
            font-size: 5em;
            font-family: 'Snap ITC', sans-serif;
            color:rgb(255, 255, 255);
            margin-bottom: 30px;
        }

        .mensagem {
            background-color:rgb(97, 184, 255);
            padding: 20px;
            border-radius: 15px;
        }

        .mensagem h2 {
            font-size: 2em;
            color:rgb(255, 255, 255);
            margin-bottom: 10px;
        }

        .mensagem p {
            color: white;
        }

        .mensagem a {
            text-decoration: none;
        }
        </style>
        
</head>
<body>
    <h1>FOAG</h1>
    <div class="mensagem">
    <h2>Bem-vindo, <?php echo $_SESSION['usuario']; ?>!</h2>
    <p>Você está logado com sucesso.</p>
    <a href="../calendario/calendario.php">Entrar</a>
    <div>
</body>
</html>

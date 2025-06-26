<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = trim($_POST['senha'] ?? '');
    $data_nascimento = trim($_POST['data_nascimento'] ?? '');
    $data_cadastro = date("Y-m-d H:i:s");

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || empty($senha) || empty($nome) || empty($data_nascimento)) {
        echo "Por favor, preencha todos os campos corretamente.";
        exit;
    }

    $arquivo = __DIR__ . '/usuarios.txt';
    $usuarios = file_exists($arquivo) ? file($arquivo, FILE_IGNORE_NEW_LINES) : [];

    foreach ($usuarios as $linha) {
        list($emailSalvo) = explode('|', $linha);
        if ($emailSalvo === $email) {
            echo <<<HTML
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Erro</title>
    <meta http-equiv="refresh" content="3;url=cadastro.php">
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
            margin-bottom: 10px;
        }
        h2 {
            font-size: 2em;
            color:rgb(255, 255, 255);
            margin-bottom: 10px;
        }
       
    </style>
</head>
<body>
    <h1> FOAG </h1>
        <h2>Ops... este e-mail já está cadastrado!</h2>
</body>
</html>
HTML;
            exit;
        }
    }

    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
    $linha = $email . '|' . $senha_hash . '|nome=' . $nome . '|nascimento=' . $data_nascimento . '|cadastrado_em=' . $data_cadastro . "\n";
    file_put_contents($arquivo, $linha, FILE_APPEND);

    echo <<<HTML
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastro realizado</title>
    <meta http-equiv="refresh" content="3;url=../index/index.php">
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
            margin-bottom: 10px;
        }
        h2 {
            font-size: 2.5em;
            color:rgb(255, 255, 255);
            margin-bottom: 10px;
        }
        p {
            color: white;
            font-size: 16px;
        }
        small {
            color: white;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <h1> FOAG </h1>
        <h2>Cadastro realizado com sucesso :)</h2>
        <p>Você será redirecionado em alguns segundos...</p>
        <small>Se não for redirecionado, <a href="../index/index.php">clique aqui</a>.</small>
</body>
</html>
HTML;
    header('refresh:3;url=../index/index.php');
    exit;
} else {
    echo "Acesso inválido.";
    exit;
}
?>

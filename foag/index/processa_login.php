<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = trim($_POST['senha'] ?? '');

    if (filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($senha)) {
        $arquivo = __DIR__ . '/../cadastro/usuarios.txt';

        if (!file_exists($arquivo)) {
            echo "Nenhum usuário cadastrado.";
            exit;
        }

        $usuarios = file($arquivo, FILE_IGNORE_NEW_LINES);
        foreach ($usuarios as $linha) {
            $partes = explode('|', $linha);
            $emailSalvo = $partes[0];
            $senhaHash = $partes[1];

            // Captura o nome (removendo o prefixo "nome=")
            $nomeUsuario = '';
            if (isset($partes[2]) && strpos($partes[2], 'nome=') === 0) {
                $nomeUsuario = str_replace('nome=', '', $partes[2]);
            }

            if ($email === $emailSalvo && password_verify($senha, $senhaHash)) {
                // Armazena o nome em vez do email na sessão
                $_SESSION['usuario'] = $nomeUsuario ?: $emailSalvo;
                header('Location: entrada.php');
                exit;
            }
        }

        // Mensagem de erro (email ou senha incorretos)
        echo <<<HTML
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login inválido</title>
    <meta http-equiv="refresh" content="3;url=index.php">
    <style>
        body {
            font-family:'Poppins', sans-serif;
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
            font-size: 1.8em;
            color:rgb(255, 255, 255);
            margin-bottom: 10px;
        }
        p {
            color: white;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <h1>FOAG</h1>
    <h2>Vish... e-mail ou senha incorretos :(</h2>
    <p>Tente novamente!</p>
</body>
</html>
HTML;
        exit;
    } else {
        // Mensagem de erro (dados incompletos)
        echo <<<HTML
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Erro no login</title>
    <meta http-equiv="refresh" content="3;url=index.php">
    <style>
        body {
            font-family:'Poppins', sans-serif;
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
            font-size: 1.8em;
            color:rgb(255, 255, 255);
            margin-bottom: 10px;
        }
        p {
            color: white;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <h1>FOAG</h1>
    <h2>Ops... tem algo de errado!</h2>
    <p>Preencha o e-mail e a senha corretamente.</p>
</body>
</html>
HTML;
        exit;
    }
} else {
    echo "Acesso inválido.";
    exit;
}
?>

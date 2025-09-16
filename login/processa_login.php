<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = trim($_POST['senha'] ?? '');

    if (filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($senha)) {
        $arquivo = __DIR__ . '/../json/usuarios.json';

        if (!file_exists($arquivo)) {
            echo "Nenhum usuário cadastrado.";
            exit;
        }

        // Ler e decodificar o JSON
        $conteudo = file_get_contents($arquivo);
        $usuarios = json_decode($conteudo, true) ?? [];

        foreach ($usuarios as $usuario) {
            if ($usuario['email'] === $email && password_verify($senha, $usuario['senha'])) {
                // Salva o nome na sessão (ou e-mail se não tiver nome)
                $_SESSION['usuario'] = $usuario['nome'] ?: $usuario['email'];

                // Mostra tela de sucesso com gato feliz 😺
                exibirMensagem("Login realizado com sucesso!", "entrada.php", "gato5.png");
                exit;
            }
        }

        // Mensagem de erro (email ou senha incorretos) → gato triste
        exibirMensagem("Vish... e-mail ou senha incorretos :(", "index.php", "gato6.png");
        exit;
    } else {
        // Dados incompletos → gato desconfiado
        exibirMensagem("Ops... preencha o e-mail e a senha corretamente.", "login6", "gato_desconfiado.png");
        exit;
    }
} else {
    echo "Acesso inválido.";
    exit;
}

// Função para exibir mensagens com estilo
function exibirMensagem($mensagem, $redirect, $imagem) {
    echo <<<HTML
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <meta http-equiv="refresh" content="3;url={$redirect}">
    <style>
        body {
            font-family:'Poppins', sans-serif;
            background: linear-gradient(to right, #38a5ff, rgb(46, 154, 241));
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        img {
            max-width: 250px;
            margin-bottom: 20px;
        }
        h2 {
            font-size: 1.8em;
            color: white;
            margin-bottom: 10px;
            text-align: center;
        }
        p {
            color: white;
            font-size: 16px;
            text-align: center;
        }
    </style>
</head>
<body>
    <img src="../img/gatofoag/{$imagem}" alt="gato">
    <h2>{$mensagem}</h2>
    <p>Você será redirecionado em alguns segundos...</p>
</body>
</html>
HTML;
}
?>

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
            echo "Este e-mail já está cadastrado.";
            exit;
        }
    }

    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
    $linha = $email . '|' . $senha_hash . '|nome=' . $nome . '|nascimento=' . $data_nascimento . '|cadastrado_em=' . $data_cadastro . "\n";
    file_put_contents($arquivo, $linha, FILE_APPEND);

    echo "<p>Cadastro realizado com sucesso! Redirecionando para login...</p>";
    header('refresh:3;url=../index/index.php');
    exit;
} else {
    echo "Acesso inválido.";
    exit;
}
?>

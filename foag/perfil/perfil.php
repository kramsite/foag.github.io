<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_email'])) {
    header("Location: perfil.php");
    exit;
}

$emailLogado = $_SESSION['usuario_email'];
$arquivo = __DIR__ . '/usuarios.txt';

// Variáveis padrão
$nome = '';
$data_nascimento = '';
$data_cadastro = '';
$foto = 'uploads/default.png'; // Pode ser personalizado se você quiser permitir upload

// Procura o usuário no arquivo
if (file_exists($arquivo)) {
    $usuarios = file($arquivo, FILE_IGNORE_NEW_LINES);
    foreach ($usuarios as $linha) {
        $dados = explode('|', $linha);
        if ($dados[0] === $emailLogado) {
            $nome = str_replace('nome=', '', $dados[2]);
            $data_nascimento = str_replace('nascimento=', '', $dados[3]);
            $data_cadastro = str_replace('cadastrado_em=', '', $dados[4]);
            break;
        }
    }
}

// Se o usuário não for encontrado, volta para o login
if (empty($nome)) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil do Usuário - FOAG</title>
    <link rel="stylesheet" href="perfil.css">
</head>
<body>

    <div class="main-content">
        <button class="back-btn" onclick="window.history.back()">Voltar</button>

        <div class="profile-container">
            <!-- Foto do usuário -->
            <div class="profile-img-container">
                <img src="<?php echo $foto; ?>" alt="Foto do Perfil" class="profile-img">
            </div>

            <!-- Informações do perfil -->
            <div class="profile-details">
                <h3><?php echo htmlspecialchars($nome); ?></h3>
                <div class="profile-info">
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($emailLogado); ?></p>
                    <p><strong>Data de Nascimento:</strong> <?php echo htmlspecialchars($data_nascimento); ?></p>
                    <p><strong>Cadastrado em:</strong> <?php echo htmlspecialchars($data_cadastro); ?></p>
                </div>

                <!-- Informações extras -->
                <div class="extra-info">
                    <p><strong>Escola:</strong> Colégio Exemplo</p>
                    <p><strong>Curso:</strong> Ensino Médio</p>
                </div>

                <a href="#" class="btn">Editar Perfil</a>
            </div>
        </div>
    </div>

</body>
</html>
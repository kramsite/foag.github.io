<?php
session_start();

$dataFile = 'profile_data.json';
$nome = '';
$email = '';
$foto = 'uploads/default.png';
$error = '';

if (file_exists($dataFile)) {
    $data = json_decode(file_get_contents($dataFile), true);
    $nome = $data['nome'] ?? '';
    $email = $data['email'] ?? '';
    $foto = $data['foto'] ?? 'uploads/default.png';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);

    if (empty($nome) || empty($email)) {
        $error = "Nome e email são obrigatórios.";
    } else {
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] == UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            if (in_array($ext, $allowed)) {
                $newName = 'uploads/profile_' . time() . '.' . $ext;
                move_uploaded_file($_FILES['foto']['tmp_name'], $newName);
                $foto = $newName;
            } else {
                $error = "Formato de foto inválido. Use jpg, png ou gif.";
            }
        }

        if (!isset($error)) {
            $saveData = [
                'nome' => $nome,
                'email' => $email,
                'foto' => $foto
            ];
            file_put_contents($dataFile, json_encode($saveData, JSON_PRETTY_PRINT));
            $_SESSION['success'] = "Perfil salvo com sucesso!";
            header("Location: index.php");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil do Usuário - FOAG</title>
    <link rel="stylesheet" href="perfil.css">
    <link rel="stylesheet" href="style.css">
    <style>
        /* Remove a sidebar */
        .sidebar {
            display: none;
        }

        /* Centraliza o conteúdo */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f4f8;
        }
    </style>
</head>
<body>

    <div class="main-content">
        <button class="back-btn" onclick="window.history.back()">Voltar</button>

        <div class="profile-container">
            <div class="profile-img-container">
                <img src="<?php echo $foto; ?>" alt="Foto do Perfil" class="profile-img">
            </div>

            <div class="profile-details">
                <h3><?php echo $nome ?: 'Nome do Usuário'; ?></h3>
                <div class="profile-info">
                    <p><strong>Email:</strong> <?php echo $email ?: 'usuario@example.com'; ?></p>
                    <p><strong>Telefone:</strong> (00) 00000-0000</p>
                    <p><strong>Endereço:</strong> Rua Exemplo, 123, Bairro, Cidade</p>
                </div>
                <a href="#" class="btn">Editar Perfil</a>
            </div>
        </div>
    </div>

</body>
</html>

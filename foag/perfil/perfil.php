<?php
session_start();

// Caminho para o arquivo onde salvamos os dados (simples storage)
$dataFile = 'profile_data.json';

// Inicializa variáveis
$nome = '';
$email = '';
$horario = '';
$anotacoes = '';
$foto = 'uploads/default.png'; // foto padrão

// Carrega dados se existir
if (file_exists($dataFile)) {
    $data = json_decode(file_get_contents($dataFile), true);
    $nome = $data['nome'] ?? '';
    $email = $data['email'] ?? '';
    $horario = $data['horario'] ?? '';
    $anotacoes = $data['anotacoes'] ?? '';
    $foto = $data['foto'] ?? 'uploads/default.png';
}

// Upload da foto
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $horario = trim($_POST['horario']);
    $anotacoes = trim($_POST['anotacoes']);

    // Validações simples
    if (empty($nome) || empty($email)) {
        $error = "Nome e email são obrigatórios.";
    } else {
        // Tratamento upload de foto
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] == UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
            $ext = strtolower($ext);
            $allowed = ['jpg','jpeg','png','gif'];
            if (in_array($ext, $allowed)) {
                $newName = 'uploads/profile_'.time().'.'.$ext;
                move_uploaded_file($_FILES['foto']['tmp_name'], $newName);
                $foto = $newName;
            } else {
                $error = "Formato de foto inválido. Use jpg, png ou gif.";
            }
        }

        if (!isset($error)) {
            // Salva dados no arquivo JSON
            $saveData = [
                'nome' => $nome,
                'email' => $email,
                'horario' => $horario,
                'anotacoes' => $anotacoes,
                'foto' => $foto
            ];
            file_put_contents($dataFile, json_encode($saveData, JSON_PRETTY_PRINT));
            $_SESSION['success'] = "Perfil salvo com sucesso!";
            header("Location: profile.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Editar Perfil do Estudante</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        label { display: block; margin-top: 10px; }
        input[type=text], input[type=email], textarea { width: 100%; padding: 8px; margin-top: 5px; }
        textarea { height: 100px; }
        .error { color: red; }
        .preview-img { max-width: 150px; margin-top: 10px; }
        button { margin-top: 15px; padding: 10px 15px; }
    </style>
</head>
<body>

<h1>Editar Perfil do Estudante</h1>

<?php if (!empty($error)): ?>
    <p class="error"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <label>Nome:
        <input type="text" name="nome" value="<?= htmlspecialchars($nome) ?>" required />
    </label>
    <label>Email:
        <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" required />
    </label>
    <label>Horário (ex: Segunda 8h-10h, Terça 14h-16h):
        <textarea name="horario"><?= htmlspecialchars($horario) ?></textarea>
    </label>
    <label>Anotações:
        <textarea name="anotacoes"><?= htmlspecialchars($anotacoes) ?></textarea>
    </label>
    <label>Foto de Perfil:
        <input type="file" name="foto" accept="image/*" />
    </label>
    <?php if ($foto && file_exists($foto)): ?>
        <img src="<?= htmlspecialchars($foto) ?>" alt="Foto de Perfil" class="preview-img" />
    <?php endif; ?>

    <button type="submit">Salvar Perfil</button>
</form>

</body>
</html>

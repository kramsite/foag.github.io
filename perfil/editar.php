<?php
// Simulando o usuário logado
$email_logado = "rafa@gmail.com";

// Caminhos
$caminho_json = "../json/usuarios.json";
$pasta_fotos = "../img/perfil/";
$foto_padrao = "foto_padrao.png";

$escolas_json = "../json/escolas.json";
$series_json = "../json/series.json";

$opcoes_escolas = file_exists($escolas_json) ? json_decode(file_get_contents($escolas_json), true) : [];
$opcoes_series = file_exists($series_json) ? json_decode(file_get_contents($series_json), true) : [];


// Carregando os dados
if (!file_exists($caminho_json)) {
    echo "Arquivo de usuários não encontrado!";
    exit;
}

$usuarios = json_decode(file_get_contents($caminho_json), true);
$usuario_index = null;

foreach ($usuarios as $index => $usuario) {
    if ($usuario["email"] === $email_logado) {
        $usuario_index = $index;
        break;
    }
}

if ($usuario_index === null) {
    echo "Usuário não encontrado!";
    exit;
}

// Atualizar dados se enviado
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuarios[$usuario_index]["nome"] = $_POST["nome"];
    $usuarios[$usuario_index]["nascimento"] = $_POST["nascimento"];
    $usuarios[$usuario_index]["telefone"] = $_POST["telefone"];
    $usuarios[$usuario_index]["serie"] = $_POST["serie"];
    $usuarios[$usuario_index]["escola"] = $_POST["escola"];

    // Upload da nova foto
    if (isset($_FILES["foto"]) && $_FILES["foto"]["error"] === 0) {
        $ext = pathinfo($_FILES["foto"]["name"], PATHINFO_EXTENSION);
        $nome_foto = uniqid() . "." . $ext;
        move_uploaded_file($_FILES["foto"]["tmp_name"], $pasta_fotos . $nome_foto);
        $usuarios[$usuario_index]["foto"] = $nome_foto;
    }

    // Salvar alterações
    file_put_contents($caminho_json, json_encode($usuarios, JSON_PRETTY_PRINT));
    header("Location: perfil.php");
    exit;
}

$usuario = $usuarios[$usuario_index];
$foto_perfil = (!empty($usuario["foto"]) && file_exists($pasta_fotos . $usuario["foto"]))
    ? $usuario["foto"]
    : $foto_padrao;
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Editar Perfil</title>
  <link rel="stylesheet" href="editar.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Roboto:wght@400;500&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>
<body>
  <form class="container" method="post" enctype="multipart/form-data">
    <div class="left-panel">
      <img src="<?= $pasta_fotos . $foto_perfil ?>" class="avatar" alt="Foto de perfil">
      <h2>Editar Perfil</h2>
      <p>Altere suas informações abaixo.</p>

      <label for="foto" class="label-upload">Trocar Foto</label>
      <input type="file" id="foto" name="foto" accept="image/*" class="input-upload">
    </div>

    <div class="right-panel">
      <div class="campo-info">
        <label for="nome">Nome</label>
        <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($usuario["nome"]) ?>" required>
      </div>

      <div class="campo-info">
        <label for="nascimento">Data de Nascimento</label>
        <input type="date" id="nascimento" name="nascimento" value="<?= htmlspecialchars($usuario["nascimento"]) ?>" required>
      </div>

      <div class="campo-info">
        <label for="telefone">Telefone</label>
        <input type="text" id="telefone" name="telefone" value="<?= htmlspecialchars($usuario["telefone"]) ?>">
      </div>

      <div class="campo-info">
  <label for="serie">Série/Curso</label>
  <select id="serie" name="serie" required>
    <option value="">Selecione a série</option>
    <?php foreach ($opcoes_series as $serie): ?>
      <option value="<?= $serie ?>" <?= ($serie === $usuario["serie"]) ? "selected" : "" ?>>
        <?= $serie ?>
      </option>
    <?php endforeach; ?>
  </select>
</div>

<div class="campo-info">
  <label for="escola">Escola/Faculdade</label>
  <select id="escola" name="escola" required>
    <option value="">Selecione a escola</option>
    <?php foreach ($opcoes_escolas as $escola): ?>
      <option value="<?= $escola ?>" <?= ($escola === $usuario["escola"]) ? "selected" : "" ?>>
        <?= $escola ?>
      </option>
    <?php endforeach; ?>
  </select>
</div>


      <div class="botoes">
        <button type="submit" class="salvar">Salvar</button>
        <button type="button" class="cancelar" onclick="location.href='perfil.php'">Cancelar</button>
      </div>
    </div>
  </form>
</body>
</html>

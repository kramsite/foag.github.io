<?php
// Simulando o usuário logado
$email_logado = "rafa@gmail.com";

// Caminhos
$caminho_json = "../json/usuarios.json";
$pasta_fotos = "../img/perfil/";
$foto_padrao = "foto_padrao.png";

// Carregando os dados
if (!file_exists($caminho_json)) {
    echo "Arquivo de usuários não encontrado!";
    exit;
}

$usuarios = json_decode(file_get_contents($caminho_json), true);
$usuario_logado = null;

foreach ($usuarios as $usuario) {
    if ($usuario["email"] === $email_logado) {
        $usuario_logado = $usuario;
        break;
    }
}

if (!$usuario_logado) {
    echo "Usuário não encontrado!";
    exit;
}

// Definindo a foto
$foto_perfil = (!empty($usuario_logado["foto"]) && file_exists($pasta_fotos . $usuario_logado["foto"]))
    ? $usuario_logado["foto"]
    : $foto_padrao;
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Perfil - Foag</title>
  <link rel="stylesheet" href="perfil.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Roboto:wght@400;500&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>
<body>
  <button class="btn-sair" onclick="location.href='../login.php'">Sair</button>

  <div class="container">
    <div class="left-panel">
      <img src="<?= $pasta_fotos . $foto_perfil ?>" alt="Foto de perfil" class="avatar">
      <h2>Seu Perfil</h2>
      <p>Essas são suas informações salvas no sistema.</p>
      <button class="btn-seta" onclick="location.href='editar.php'">✎</button>
    </div>

    <div class="right-panel">
      <div class="campo-info">
        <label>Nome</label>
        <div class="texto-info"><?= htmlspecialchars($usuario_logado["nome"]) ?></div>
      </div>

      <div class="campo-info">
        <label>Email</label>
        <div class="texto-info"><?= htmlspecialchars($usuario_logado["email"]) ?></div>
      </div>

      <div class="campo-info">
        <label>Data de Nascimento</label>
        <div class="texto-info"><?= htmlspecialchars($usuario_logado["nascimento"]) ?></div>
      </div>

      <div class="campo-info">
        <label>Telefone</label>
        <div class="texto-info"><?= htmlspecialchars($usuario_logado["telefone"]) ?></div>
      </div>

      <div class="campo-info">
        <label>Série / Ano</label>
        <div class="texto-info"><?= htmlspecialchars($usuario_logado["serie"]) ?></div>
      </div>

      <div class="campo-info">
        <label>Escola / Faculdade</label>
        <div class="texto-info"><?= htmlspecialchars($usuario_logado["escola"]) ?></div>
      </div>
    </div>
  </div>
</body>
</html>

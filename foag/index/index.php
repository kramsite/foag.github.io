<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="style.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
</head>
<body>
  <!-- Cabeçalho fixo -->
  <div class="logo">
    FOAG
  </div>

  <!-- Página de login -->
  <div class="login-page">
    <div class="left-section">
      <img src="../img/livro.png" alt="Inspirational image" style="width: 100%; height: 100%; object-fit: cover;">
    </div>
    <div class="right-section">
      <h1>Login</h1>
      <form method="POST" action="processa_login.php">
        <label for="email">E-mail:</label><br>
        <input type="email" id="email" name="email" required><br><br>

        <label for="senha">Senha:</label><br>
        <input type="password" id="senha" name="senha" required><br><br>

        <a href="../cadastro/cadastro.php">CADASTRE-SE</a>

        <button type="submit">Entrar</button>
    </form>
      <div id="g_id_onload"
           data-client_id="SEU_CLIENT_ID_AQUI"
           data-auto_prompt="false">
      </div>
      <div class="g_id_signin"
           data-type="standard"
           data-size="large"
           data-theme="outline"
           data-text="sign_in_with"
           data-shape="rectangular"
           data-logo_alignment="left">
      </div>
    </div>
  </div>
</body>
</html>
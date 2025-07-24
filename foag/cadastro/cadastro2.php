
</html>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="stylecads2.css">
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
      <h2>Cadastro de Usuário</h2>
    <form method="POST" action="processa_cadastro.php">
    <label for="nome">Nome</label>
    <input type="text" id="nome" name="nome" required>

    <label for="email">E-mail</label>
    <input type="email" id="email" name="email" required>

    <label for="senha">Senha</label>
    <input type="password" id="senha" name="senha" required>

    <label for="confirmar_senha">Confirmar Senha</label>
    <input type="password" id="confirmar_senha" name="confirmar_senha" required>

    <label for="data_nascimento">Data de Nascimento</label>
    <input type="date" id="data_nascimento" name="data_nascimento" required>

    <label for="serie">Série/Curso</label>
    <input type="text" id="serie" name="serie" required>

    <label for="escola">Escola/Faculdade</label>
    <input type="text" id="escola" name="escola" required>

    <label for="telefone">Telefone</label>
    <input type="tel" id="telefone" name="telefone" pattern="[0-9]{10,11}" placeholder="Apenas números" required>

    <label for="notificacoes">Preferências de Notificação</label>
    <select id="notificacoes" name="notificacoes" required>
        <option value="email">E-mail</option>
        <option value="sms">SMS</option>
        <option value="ambos">Ambos</option>
        <option value="nenhum">Nenhum</option>
    </select>

    <label>
        <input type="checkbox" name="termos" required>
        Aceito os <a href="#">termos de uso</a> e a política de privacidade.
    </label>

    <button type="submit">Cadastrar</button>
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
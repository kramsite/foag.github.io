<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastro de Usuário</title>
  <link rel="stylesheet" href="stylecads.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
  <div class="logo">FOAG</div>

  <!-- Fundo com imagem -->
  <div class="background"></div>

  <!-- Bloco central do formulário -->
  <div class="form-container">
    <h2>Cadastro de Usuário</h2>
    <form method="POST" action="processa_cadastro.php">
      <label for="nome">Nome</label>
      <input type="text" id="nome" name="nome" required>

      <label for="email">E-mail</label>
      <input type="email" id="email" name="email" required>

      <div class="form-row">
        <div>
          <label for="senha">Senha</label>
          <input type="password" id="senha" name="senha" required>
        </div>
        <div>
          <label for="confirmar_senha">Confirmar</label>
          <input type="password" id="confirmar_senha" name="confirmar_senha" required>
        </div>
      </div>

      <div class="form-row">
        <div>
          <label for="data_nascimento">Nascimento</label>
          <input type="date" id="data_nascimento" name="data_nascimento" required>
        </div>
        <div>
          <label for="telefone">Telefone</label>
          <input type="tel" id="telefone" name="telefone" pattern="[0-9]{10,11}" placeholder="Apenas números" required>
        </div>
      </div>

      <div class="form-row">
        <div>
          <label for="serie">Série/Curso</label>
          <input type="text" id="serie" name="serie" required>
        </div>
        <label for="escola">Escola/Faculdade</label>
      <select id="escola" name="escola" required>
        <option value="email">E-mail</option>
        <option value="sms">SMS</option>
        <option value="ambos">Ambos</option>
        <option value="nenhum">Nenhum</option>
      </select>
      </div>

      <label for="notificacoes">Preferências de Notificação</label>
      <select id="notificacoes" name="notificacoes" required>
        <option value="escola"></option>
        <option value="escola"></option>
        <option value="escola"></option>
        <option value="escola"></option>
        <option value="escola"></option>
        <option value="escola"></option>
        <option value="escola"></option>
        <option value="escola"></option>
        <option value="escola"></option>
        <option value="escola"></option>
        <option value="escola"></option>
        <option value="escola"></option>
        <option value="escola"></option>
        <option value="escola"></option>

      </select>

      <label class="termos">
        <input type="checkbox" name="termos" required>
        Aceito os <a href="#">termos de uso</a> e a política de privacidade.
      </label>

      <button type="submit">Cadastrar</button>
    </form>
  </div>
</body>
</html>

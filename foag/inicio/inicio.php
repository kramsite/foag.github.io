<<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Horário Escolar - Sobre</title>
  <link rel="stylesheet" href="inicio.css" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Roboto:wght@400;500&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>
<body>

  <!-- Cabeçalho -->
  <header class="cabecalho">
    FOAG
    <div class="header-icons">
      <i id="icon-perfil" class="fa-regular fa-user" title="Perfil"></i>
      <i id="icon-sair" class="fa-solid fa-right-from-bracket" title="Sair"></i>
    </div>
  </header>

    <div class="intro-text">
        <h1>Bem-vindo ao nosso site!</h1>
        <p>Esta é a página inicial, onde você pode navegar para diferentes seções.</p>
    </div>

    <!-- Container Principal com os Links -->
    <div class="container">
        <div class="box">
            <a href="../calendario/calendario.html">Calendário</a>
        </div>
        <div class="box">
            <a href="../agenda/agenda.html">Agenda</a>
        </div>
        <div class="box">
            <a href="../HORARIO/horario.html">Horário</a>
        </div>
        <div class="box">
            <a href="#">Perfil</a>
        </div>
        <div class="box">
            <a href="#">Sobre</a>
        </div>
        <div class="box">
            <a href="#">Contato</a>
        </div>
    </div>

    <div id="logout-modal" class="modal">
    <div class="modal-content">
      <h3>Ah... já vai?</h3>
      <h4>Tem certeza de que deseja sair?</h4>
      <div class="modal-buttons">
        <button id="confirm-logout">Sim</button>
        <button id="cancel-logout">Cancelar</button>
      </div>
    </div>
  </div>

  <!-- Rodapé -->
  <footer>
    &copy; 2025 FOAG. Todos os direitos reservados.
  </footer>

    <script src="../inicio/inicio.js"></script>
</body>
</html>

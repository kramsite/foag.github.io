<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Horário Escolar - Sobre</title>
  <link rel="stylesheet" href="inicio.css">
  <link rel="stylesheet" href="dark_inicio.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  <script src="../m.escuro/dark-mode.js"></script>
</head>
<body>
  <!-- Cabeçalho -->
  <nav class="navbar">
    <div class="logo">FOAG</div>
    <div class="header-icons">
      <i id="themeToggle" class="fa-solid fa-moon" title="Modo Escuro"></i>
      <i id="icon-perfil" class="fa-regular fa-user" title="Perfil"></i>
      <i id="icon-sair" class="fa-solid fa-right-from-bracket" title="Sair"></i>
    </div>
  </nav>

  <!-- Conteúdo principal -->
  <main>
    <!-- Boas-vindas -->
    <section class="welcome">
      <h1>Bem-vindo(a), estudante!</h1>
      <p>Organize sua rotina escolar de forma prática e rápida.</p>
    </section>

    <!-- Painel de Acesso Rápido -->
    <section class="dashboard">
      <div class="card">
        <h3>📋 Tarefas</h3>
        <p>Veja e adicione suas tarefas.</p>
        <a href="#">Ver tarefas</a>
      </div>
      <div class="card">
        <h3>📆 Faltas</h3>
        <p>Controle suas presenças e faltas.</p>
        <a href="../calendario/calendario.php">Ver faltas</a>
      </div>
      <div class="card">
        <h3>🕐 Horários</h3>
        <p>Consulte sua grade de aulas.</p>
        <a href="../HORARIO/horario.php">Ver horários</a>
      </div>
      <div class="card">
        <h3>👤 Perfil</h3>
        <p>Veja suas informações.</p>
        <a href="../perfil/perfil.php">Ver perfil</a>
      </div>
    </section>
  </main>

  <!-- Rodapé -->
  <footer>&copy; 2025 FOAG. Todos os direitos reservados.</footer>
</body>
</html>

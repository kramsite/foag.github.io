<?php
// Inicia a sessão (caso queira usar futuramente para salvar dados por usuário)
session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Organizador</title>
  <link rel="stylesheet" href="agenda.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
</head>

<body>
  <header class="cabecalho">
  FOAG
  <div class="header-icons">
    <i id="icon-perfil" class="fa-regular fa-user" title="Perfil"></i>
    <i id="icon-sair" class="fa-solid fa-right-from-bracket" title="Sair"></i>
  </div>
</header>


  <div class="container">
    <nav class="menu">
      <a href="../inicio/inicio.php">Início</a>
      <a href="../calendario/calendario.php">Calendário</a>
      <a href="../HORARIO/horario.php">Horário</a>
      <a href="#">Perfil</a>
      <a href="#">Sobre</a>
      <a href="#">Contato</a>
    </nav>

    <main class="main-content">
      <div id="container-notas">
        <!-- Notas -->
        <div id="notas">
          <textarea placeholder="Escreva suas notas aqui..." wrap="soft"></textarea>
          <button id="btn-salvar-nota">Salvar Nota</button>
          <div id="saved-notes">
            <h2>Notas Salvas</h2>
            <ul id="noteList"></ul>
          </div>
        </div>

        <!-- Tarefas e Não Esquecer -->
        <div id="tarefas">
          <div class="titulo-tabela">TAREFAS</div>
          <table id="tabela-tarefas">
            <thead>
              <tr><th>#</th><th>Tarefa</th><th>Data</th><th>Ações</th></tr>
            </thead>
            <tbody id="lista-tarefas"></tbody>
          </table>
          <button id="add-tarefa">Adicionar Tarefa</button>

          <div class="titulo-tabela">NÃO ESQUECER</div>
          <table id="tabela-nao-esquecer">
            <thead>
              <tr><th>#</th><th>Item</th><th>Data</th><th>Ações</th></tr>
            </thead>
            <tbody id="lista-nao-esquecer"></tbody>
          </table>
          <button id="add-nao-esquecer">Adicionar Item</button>
        </div>
      </div>
    </main>
  </div>

  <!-- Modal de Confirmação -->
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


  <footer>&copy; 2025 FOAG. Todos os direitos reservados.</footer>

  <!-- Importa jsPDF via CDN -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script>
    window.jsPDF = window.jspdf.jsPDF;

    // Botões do header
document.getElementById('icon-perfil').addEventListener('click', () => {
    window.location.href = '../perfil/perfil.php'; // Redireciona para perfil
});

const logoutModal = document.getElementById('logout-modal');
const confirmLogout = document.getElementById('confirm-logout');
const cancelLogout = document.getElementById('cancel-logout');

// Abrir modal ao clicar no ícone de sair
document.getElementById('icon-sair').addEventListener('click', () => {
  logoutModal.style.display = 'flex';
});

// Botão "Sim" - redireciona
confirmLogout.addEventListener('click', () => {
  window.location.href = '../index/index.php';
});

// Botão "Cancelar" - fecha o modal
cancelLogout.addEventListener('click', () => {
  logoutModal.style.display = 'none';
});

// Fecha o modal se clicar fora dele
logoutModal.addEventListener('click', e => {
  if (e.target === logoutModal) {
    logoutModal.style.display = 'none';
  }
});


  </script>
  <script src="./agenda.js"></script>
</body>
</html>

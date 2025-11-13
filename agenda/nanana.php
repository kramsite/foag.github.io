<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Organizador</title>
  <link rel="stylesheet" href="agenda.css" />
  <link rel="stylesheet" href="dark_agenda.css">
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  <script src="../m.escuro/dark-mode.js"></script>
</head>

<body>
 <header class="cabecalho">
  FOAG
  <div class="header-icons">
    <i id="themeToggle" class="fa-solid fa-moon" title="Modo Escuro"></i>
    <i id="icon-perfil" class="fa-regular fa-user" title="Perfil"></i>
    <i id="icon-fogi" class="fa-solid fa-robot" title="Assistente FOAG — FOGi"></i>
    <i id="icon-sair" class="fa-solid fa-right-from-bracket" title="Sair"></i>
  </div>
</header>

  <div class="container">
    <nav class="menu">
      <a href="../inicio/sla.php">Início</a>
      <a href="../calendario/calendario.php">Calendário</a>
      <a href="../HORARIO/horario.php">Horário</a>
      <a href="../sobre/sobre.html">Sobre Nós</a>
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
            <div class="notas-container" id="noteList">
              <!-- As notas salvas serão inseridas aqui -->
            </div>
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

  <!-- Modal para nomear a nota -->
  <div id="modal-nomear-nota" class="modal-nomear-nota">
    <div class="modal-content">
      <h3>Dê um nome para sua nota</h3>
      <input type="text" id="nome-nota" placeholder="Digite um título para sua nota..." maxlength="50">
      <div class="modal-buttons">
        <button id="confirmar-nome-nota">Salvar</button>
        <button id="cancelar-nome-nota">Cancelar</button>
      </div>
    </div>
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

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="./agenda.js"></script>
  <script>
   
  const fogiBtn = document.getElementById("icon-fogi");
  const fogiModal = document.getElementById("fogi-modal");
  const fogiFrame = document.getElementById("fogi-iframe");
  const fogiClose = document.getElementById("fogi-close");

  // abre IA
  fogiBtn.addEventListener("click", () => {
    fogiFrame.src = "http://127.0.0.1:5000";  // Flask/Ollama rodando
    fogiModal.style.display = "flex";
    document.body.style.overflow = "hidden";
  });

  // fecha IA
  fogiClose.addEventListener("click", () => {
    fogiModal.style.display = "none";
    fogiFrame.src = "about:blank"; // limpa sessão
    document.body.style.overflow = "";
  });

  // sair da IA via postMessage (botão Sair dentro da própria FOGi)
  window.addEventListener("message", (ev) => {
    if (ev.data?.type === "FOGI_CLOSE") {
      fogiModal.style.display = "none";
      fogiFrame.src = "about:blank";
      document.body.style.overflow = "";
    }
  });
</script>

</body>
</html>
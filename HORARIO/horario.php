<?php
session_start();

  $current = basename($_SERVER['PHP_SELF']); // ex: pomodoro.php, calendario.php
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horário Escolar</title>
    <link rel="stylesheet" href="horario.css">
    <link rel="stylesheet" href="dark_hora.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <!-- Importando a biblioteca jsPDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <!-- Importando a biblioteca jsPDF AutoTable -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.24/jspdf.plugin.autotable.min.js"></script>
    <script src="../m.escuro/dark-mode.js"></script>

    <!-- Estilos do botão e modal da FOGi -->
    <style>
      #icon-fogi {
        cursor: pointer;
        transition: 0.2s;
      }
      #icon-fogi:hover {
        color: #38a5ff;
        transform: scale(1.1);
      }

      /* Modal full-screen da FOGi */
      #fogi-modal {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 9999;
        background: rgba(0,0,0,0.5);
        backdrop-filter: blur(4px);
        align-items: center;
        justify-content: center;
      }

      #fogi-modal .fogi-container {
        background: #ffffff;
        width: 90%;
        max-width: 1100px;
        height: 80vh;
        border-radius: 12px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        box-shadow: 0 10px 35px rgba(0,0,0,0.2);
      }

      #fogi-modal .fogi-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #38a5ff;
        color: #fff;
        padding: 8px 14px;
        font-weight: 600;
        font-size: 0.95rem;
      }

      #fogi-close {
        border: none;
        background: #ffffff;
        color: #333;
        padding: 4px 10px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.85rem;
      }

      #fogi-close:hover {
        background: #f1f1f1;
      }

      #fogi-iframe {
        flex: 1;
        border: none;
        width: 100%;
        height: 100%;
      }
    </style>
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
        <!-- Menu lateral -->
        <nav class="menu">
  <a href="../inicioo/inicio.php" class="<?= $current === 'inicio.php' ? 'active' : '' ?>">
    <i class="fa-solid fa-house"></i> Início
  </a>

  <a href="../calend/calendario.php" class="<?= $current === 'calendario.php' ? 'active' : '' ?>">
    <i class="fa-solid fa-calendar-days"></i> Calendário
  </a>

  <a href="../bloco/agenda.php" class="<?= $current === 'agenda.php' ? 'active' : '' ?>">
    <i class="fa-solid fa-book"></i> Agenda
  </a>

  <a href="../pomodoro/pomodoro.php" class="<?= $current === 'pomodoro.php' ? 'active' : '' ?>">
    <i class="fa-solid fa-stopwatch"></i> Pomodoro
  </a>

  <a href="../notas/notas.php" class="<?= $current === 'notas.php' ? 'active' : '' ?>">
    <i class="fa-solid fa-check-double"></i> Boletim
  </a>

  <a href="../horario/horario.php" class="<?= $current === 'horario.php' ? 'active' : '' ?>">
    <i class="fa-solid fa-clock"></i> Horário
  </a>

  <a href="../sobre/sobre.html" class="<?= $current === 'sobre.html' ? 'active' : '' ?>">
    <i class="fa-solid fa-circle-info"></i> Sobre
  </a>
</nav>

        <!-- Área principal para conteúdo -->
        <div class="main-content">
            <!-- Título da Tabela -->
            <h2 class="titulo-tabela">Horário</h2>
            
            <table id="scheduleTable">
                <thead>
                    <tr>
                        <th>Horário</th>
                        <th>Segunda-feira</th>
                        <th>Terça-feira</th>
                        <th>Quarta-feira</th>
                        <th>Quinta-feira</th>
                        <th>Sexta-feira</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td contenteditable="true"></td>
                        <td contenteditable="true"></td>
                        <td contenteditable="true"></td>
                        <td contenteditable="true"></td>
                        <td contenteditable="true"></td>
                        <td contenteditable="true"></td>
                    </tr>
                    <tr>
                        <td contenteditable="true"></td>
                        <td contenteditable="true"></td>
                        <td contenteditable="true"></td>
                        <td contenteditable="true"></td>
                        <td contenteditable="true"></td>
                        <td contenteditable="true"></td>
                    </tr>
                    <tr>
                        <td contenteditable="true"></td>
                        <td contenteditable="true"></td>
                        <td contenteditable="true"></td>
                        <td contenteditable="true"></td>
                        <td contenteditable="true"></td>
                        <td contenteditable="true"></td>
                    </tr>
                </tbody>
            </table>

            <!-- Botões de ação -->
            <button onclick="salvarEdicoes()">Salvar Edições</button>
            <button onclick="adicionarLinha()">Adicionar Linha</button>
            <button onclick="removerLinha()">Remover Linha</button>
            <button onclick="adicionarIntervalo()">Adicionar Intervalo</button>
            <button onclick="salvarComoPDF()">Salvar como PDF</button>
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

    <!-- Modal da FOGi -->
    <div id="fogi-modal">
      <div class="fogi-container">
        <div class="fogi-header">
          <span>FOGi — Assistente de Estudos</span>
          <button id="fogi-close">Fechar</button>
        </div>
        <iframe id="fogi-iframe" src="about:blank"></iframe>
      </div>
    </div>

    <footer>
        &copy; 2025 FOAG. Todos os direitos reservados.
    </footer>
      
    <script src="horario.js"></script>

    <script>
      const fogiBtn   = document.getElementById("icon-fogi");
      const fogiModal = document.getElementById("fogi-modal");
      const fogiFrame = document.getElementById("fogi-iframe");
      const fogiClose = document.getElementById("fogi-close");

      // abre IA
      fogiBtn.addEventListener("click", () => {
        fogiFrame.src = "http://127.0.0.1:5000";  // Flask/Ollama rodando
        fogiModal.style.display = "flex";
        document.body.style.overflow = "hidden";
      });

      // fecha IA pelo botão "Fechar" do modal
      fogiClose.addEventListener("click", () => {
        fogiModal.style.display = "none";
        fogiFrame.src = "about:blank"; // limpa sessão
        document.body.style.overflow = "";
      });

      // sair da IA via postMessage (botão X dentro do FOGi.html)
      window.addEventListener("message", (ev) => {
        if (ev.data && ev.data.type === "FOGI_CLOSE") {
          fogiModal.style.display = "none";
          fogiFrame.src = "about:blank";
          document.body.style.overflow = "";
        }
      });
    </script>
</body>
</html>

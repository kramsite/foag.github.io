<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horário Escolar</title>
    <link rel="stylesheet" href="babuino.css">
    <link rel="stylesheet" href="../m.escuro/dark-mode.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <!-- Importando a biblioteca jsPDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <!-- Importando a biblioteca jsPDF AutoTable -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.24/jspdf.plugin.autotable.min.js"></script>
    <script src="../m.escuro/dark-mode.js"></script>
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
        <!-- Menu lateral -->
        <nav class="menu">
            <a href="../inicio/inicio.html">Início</a>
            <a href="../agenda/agenda.php">Agenda</a>
            <a href="../calendario/calendario.php">Calendario</a>
            <a href="#">Sobre</a>
            <a href="#">Contato</a>
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
            <button onclick="removerLinha()">Remover Linha</button> <!-- Botão de remover linha -->
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

<button class="theme-toggle" id="themeToggle">🌙</button>

    <footer>
        &copy; 2025 FOAG. Todos os direitos reservados.
      </footer>
      
    <script src="horario.js"></script>
</body>
</html>

<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FOAG - Início</title>
    <link rel="stylesheet" href="inicioo.css">
    <link rel="stylesheet" href="dark_ini.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
   
</head>
<body>
    <header class="cabecalho">
        <div class="header-left">
            <div class="logo">FOAG</div>
            <!-- Menu Principal no Topo -->
            <nav class="top-menu">
                <a href="../inicio/inicio.php" class="active">
                    <i class="fa-solid fa-house"></i>
                    Início
                </a>
                <a href="../calend/calendario.php">
                    <i class="fa-solid fa-calendar-days"></i>
                    Calendário
                </a>
                <a href="../bloco/agenda.php">
                    <i class="fa-solid fa-book"></i>
                    Agenda
                </a>
                <a href="../pomodoro/pomodoro.php">
                    <i class="fa-solid fa-stopwatch"></i>
                    Pomodoro
                </a>
                <a href="../notas/notas.php">
                    <i class="fa-solid fa-check-double"></i>
                    Boletim
                </a>
                <a href="../horario/horario.php">
                    <i class="fa-solid fa-clock"></i>
                    Horário
                </a>
                <a href="../sobre/sobre.html">
                    <i class="fa-solid fa-circle-info"></i>
                    Sobre
                </a>
            </nav>
        </div>
        <div class="header-icons">
      <i id="themeToggle" class="fa-solid fa-moon" title="Modo Escuro"></i>
      <i id="icon-perfil" class="fa-regular fa-user" title="Perfil"></i>

      <!-- ÍCONE DA IA -->
      <i id="icon-fogi" class="fa-solid fa-robot" title="Assistente FOAG — FOGi"></i>

      <i id="icon-sair" class="fa-solid fa-right-from-bracket" title="Sair"></i>
    </div>
    </header>

    <main class="main-content">
        <div class="welcome-container">
            <!-- Lado Esquerdo - Boas-vindas e Estatísticas -->
            <div class="left-panel">
                <div class="welcome-header">
                    <h1>Bem-vindo de volta! 👋</h1>
                    <p class="subtitle">Seu organizador pessoal FOAG está aqui para te ajudar a ser mais produtivo</p>
                </div>
                
                <div class="stats-grid-large">
                    <div class="stat-card-large">
                        <div class="stat-icon-large">
                            <i class="fa-solid fa-calendar-check"></i>
                        </div>
                        <div class="stat-info-large">
                            <span class="stat-number-large" id="dias-consecutivos">5</span>
                            <span class="stat-label-large">Dias produtivos consecutivos</span>
                        </div>
                    </div>
                    
                    <div class="stat-card-large">
                        <div class="stat-icon-large">
                            <i class="fa-solid fa-tasks"></i>
                        </div>
                        <div class="stat-info-large">
                            <span class="stat-number-large" id="tarefas-pendentes">12</span>
                            <span class="stat-label-large">Tarefas para hoje</span>
                        </div>
                    </div>
                    
                    <div class="stat-card-large">
                        <div class="stat-icon-large">
                            <i class="fa-solid fa-chart-line"></i>
                        </div>
                        <div class="stat-info-large">
                            <span class="stat-number-large" id="percentual-geral">88%</span>
                            <span class="stat-label-large">Taxa de produtividade</span>
                        </div>
                    </div>
                </div>

                <div class="motivational-section">
                    <div class="motivational-quote-large">
                        <i class="fa-solid fa-quote-left"></i>
                        <p id="quote-text">Organizar é o primeiro passo para o sucesso! Comece seu dia planejando suas atividades e veja a diferença que isso faz na sua produtividade.</p>
                        <i class="fa-solid fa-quote-right"></i>
                    </div>
                    
                    <div class="quick-tips">
                        <h3>💡 Dicas Rápidas</h3>
                        <ul>
                            <li>Revise suas tarefas pela manhã</li>
                            <li>Estabeleça metas realistas para o dia</li>
                            <li>Faça pausas regulares</li>
                            <li>Celebre suas conquistas</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Lado Direito - Informações -->
            <div class="right-panel">
                <!-- Cartão de Resumo do Mês -->
                <div class="info-card">
                    <div class="card-header">
                        <h2><i class="fa-solid fa-chart-line"></i> Resumo do Mês</h2>
                    </div>
                    <div class="card-content">
                        <div class="metric-row">
                            <div class="metric">
                                <span class="metric-value" id="total-presencas">22</span>
                                <span class="metric-label">Presenças</span>
                            </div>
                            <div class="metric">
                                <span class="metric-value faltas" id="total-faltas">3</span>
                                <span class="metric-label">Faltas</span>
                            </div>
                            <div class="metric">
                                <span class="metric-value" id="percentual-presenca">88%</span>
                                <span class="metric-label">Frequência</span>
                            </div>
                        </div>
                        
                        <div class="progress-container">
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 88%"></div>
                            </div>
                            <span class="progress-text">Meta: 85% de frequência</span>
                        </div>
                    </div>
                </div>

                <!-- Cartão de Anotações Importantes -->
                <div class="info-card">
                    <div class="card-header">
                        <h2><i class="fa-solid fa-star"></i> Anotações Importantes</h2>
                        <button class="btn-add" id="add-note">
                            <i class="fa-solid fa-plus"></i>
                        </button>
                    </div>
                    <div class="card-content">
                        <div class="notes-list" id="notes-list">
                            <!-- Anotações serão carregadas aqui -->
                        </div>
                        <div class="empty-notes" id="empty-notes">
                            <i class="fa-solid fa-clipboard"></i>
                            <p>Nenhuma anotação importante</p>
                            <button class="btn-primary" id="create-first-note">
                                <i class="fa-solid fa-plus"></i>
                                Criar primeira anotação
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Cartão de Lembretes -->
                <div class="info-card">
                    <div class="card-header">
                        <h2><i class="fa-solid fa-bell"></i> Próximos Lembretes</h2>
                    </div>
                    <div class="card-content">
                        <div class="reminders-list" id="reminders-list">
                            <!-- Lembretes serão carregados aqui -->
                        </div>
                        <div class="empty-reminders" id="empty-reminders">
                            <i class="fa-solid fa-bell-slash"></i>
                            <p>Nenhum lembrete próximo</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal para nova anotação -->
    <div id="note-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Nova Anotação Importante</h3>
                <button class="modal-close" id="close-note-modal">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <textarea id="note-text" placeholder="Digite sua anotação importante aqui..." maxlength="200"></textarea>
                <div class="modal-footer">
                    <button class="btn-secondary" id="cancel-note">Cancelar</button>
                    <button class="btn-primary" id="save-note">Salvar</button>
                </div>
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
        <div class="footer-content">
            <p>&copy; 2025 FOAG. Todos os direitos reservados.</p>
            <p>Organize seu tempo, conquiste seus objetivos</p>
        </div>
    </footer>

    <script src="inicio.js"></script>
   
  <script>
document.addEventListener('DOMContentLoaded', function() {
    // --------- PERFIL ----------
    const iconPerfil = document.getElementById('icon-perfil');
    if (iconPerfil) {
        iconPerfil.addEventListener('click', function() {
            // troca o caminho aqui se seu perfil for outro arquivo
            window.location.href = '../perfil/perfil.php';
        });
    }

    // --------- MODAL DE SAIR ----------
    const iconSair = document.getElementById('icon-sair');
    const logoutModal = document.getElementById('logout-modal');
    const confirmLogout = document.getElementById('confirm-logout');
    const cancelLogout = document.getElementById('cancel-logout');

    if (iconSair && logoutModal) {
        iconSair.addEventListener('click', function() {
            logoutModal.style.display = 'flex';
        });
    }

    if (cancelLogout && logoutModal) {
        cancelLogout.addEventListener('click', function() {
            logoutModal.style.display = 'none';
        });
    }

    if (confirmLogout) {
        confirmLogout.addEventListener('click', function() {
            // redireciona pra tela de login
            window.location.href = '../index/index.php';
        });
    }

    if (logoutModal) {
        logoutModal.addEventListener('click', function(e) {
            if (e.target === logoutModal) {
                logoutModal.style.display = 'none';
            }
        });
    }

    // --------- FOGi (IA) ----------
    const fogiBtn = document.getElementById("icon-fogi");
    const fogiModal = document.getElementById("fogi-modal");
    const fogiFrame = document.getElementById("fogi-iframe");
    const fogiClose = document.getElementById("fogi-close");

    if (fogiBtn && fogiModal && fogiFrame && fogiClose) {
        fogiBtn.addEventListener("click", () => {
            fogiFrame.src = "http://127.0.0.1:5000";
            fogiModal.style.display = "flex";
            document.body.style.overflow = "hidden";
        });

        fogiClose.addEventListener("click", () => {
            fogiModal.style.display = "none";
            fogiFrame.src = "about:blank";
            document.body.style.overflow = "";
        });

        window.addEventListener("message", (ev) => {
            if (ev.data?.type === "FOGI_CLOSE") {
                fogiModal.style.display = "none";
                fogiFrame.src = "about:blank";
                document.body.style.overflow = "";
            }
        });
    }
});
</script>
</body>
</html>

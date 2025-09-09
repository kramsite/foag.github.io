<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>FOAG – Controle de Estudos</title>

  <link rel="stylesheet" href="controle.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

  <!-- Bibliotecas (defer preserva a ordem) -->
  <script defer src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
  <script defer src="controle.js"></script>
</head>
<body>
  <!-- Cabeçalho -->
  <header class="cabecalho">
    <div class="logo">FOAG</div>
    <div class="header-icons">
      <i id="themeToggle" class="fa-solid fa-moon" title="Modo Escuro"></i>
      <i id="icon-perfil" class="fa-regular fa-user" title="Perfil"></i>
      <i id="icon-sair" class="fa-solid fa-right-from-bracket" title="Sair"></i>
    </div>
  </header>

  <div class="container">
    <!-- Menu lateral -->
    <nav class="menu">
      <a href="../agenda/agenda.php"><i class="fa-regular fa-square-check"></i> Tarefas</a>
      <a href="../calendario/calendario.php"><i class="fa-regular fa-calendar"></i> Faltas / Calendário</a>
      <a href="../HORARIO/horario.php"><i class="fa-regular fa-clock"></i> Horários</a>
      <a href="../perfil/perfil.php"><i class="fa-regular fa-user"></i> Perfil</a>
      <a class="active" href="#"><i class="fa-solid fa-book"></i> Controle de Estudos</a>
    </nav>

    <!-- Conteúdo -->
    <main class="conteudo">
      <section class="estudos-wrapper">
        <!-- TIMER + CRONÔMETRO (TABS) -->
        <section class="card">
          <h2>⏱️ Tempo de Estudo</h2>
          <p class="sub">Use o <strong>Timer (Pomodoro)</strong> para sessões cronometradas ou o <strong>Cronômetro</strong> para contar livre.</p>

          <div class="tabs">
            <button class="tab-btn active" data-tab="pomodoro">Timer (Pomodoro)</button>
            <button class="tab-btn" data-tab="stopwatch">Cronômetro</button>
          </div>

          <!-- Painel: Pomodoro -->
          <div id="tab-pomodoro" class="tab-panel active">
            <div class="grid-2">
              <label class="row" style="flex:1 1 140px"><span style="min-width:120px;color:#666">Foco (min)</span>
                <input id="focusM" class="input" type="number" min="5" max="120" value="25" />
              </label>
              <label class="row" style="flex:1 1 140px"><span style="min-width:120px;color:#666">Pausa curta (min)</span>
                <input id="shortM" class="input" type="number" min="3" max="30" value="5" />
              </label>
              <label class="row" style="flex:1 1 140px"><span style="min-width:120px;color:#666">Pausa longa (min)</span>
                <input id="longM" class="input" type="number" min="5" max="60" value="15" />
              </label>
              <label class="row" style="flex:1 1 140px"><span style="min-width:120px;color:#666">A cada (ciclos)</span>
                <input id="everyCycles" class="input" type="number" min="2" max="8" value="4" />
              </label>
            </div>

            <div class="row" style="margin-top:12px">
              <select id="discipline" class="select" style="flex:1">
                <option value="Geral">Geral</option>
              </select>
              <input id="newDiscipline" class="input" placeholder="Nova disciplina" style="flex:1" />
              <button class="btn" id="addDiscipline">Adicionar</button>
            </div>

            <div class="timer" id="timer">25:00</div>
            <div class="row" style="justify-content:center; gap:8px">
              <button class="btn" id="startBtn"><i class="fa-solid fa-play"></i> Iniciar</button>
              <button class="btn secondary" id="pauseBtn"><i class="fa-solid fa-pause"></i> Pausar</button>
              <button class="btn ghost" id="resetBtn"><i class="fa-solid fa-rotate-left"></i> Reset</button>
              <button class="btn secondary" id="savePartialPomodoro" title="Salvar o tempo decorrido até agora">
                <i class="fa-solid fa-floppy-disk"></i> Salvar sessão parcial
  </button>
            </div>
            <div class="row" style="justify-content:center; gap:8px; margin-top:10px">
              <span class="pill" id="modePill"><i class="fa-solid fa-hourglass-half"></i> Foco</span>
              <span class="pill" id="cyclePill"><i class="fa-solid fa-repeat"></i> Ciclo 1</span>
            </div>
            <div class="progress" style="margin-top:12px"><span id="timerProgress"></span></div>
            <audio id="ding" preload="auto">
              <source src="https://cdn.pixabay.com/download/audio/2022/03/15/audio_6f4caa1a68.mp3?filename=ui-interface-sfx-confirmation-95384.mp3" type="audio/mpeg" />
            </audio>
          </div>

          <!-- Painel: Cronômetro -->
          <div id="tab-stopwatch" class="tab-panel">
            <div class="row" style="margin-top:6px">
              <select id="stopwatchDiscipline" class="select" style="flex:1"></select>
              <button class="btn secondary" id="swSaveSession" title="Salvar a sessão atual como estudo"><i class="fa-solid fa-floppy-disk"></i> Salvar sessão</button>
            </div>
            <div class="timer" id="stopwatchDisplay">00:00:00</div>
            <div class="row" style="justify-content:center; gap:8px">
              <button class="btn" id="swStart"><i class="fa-solid fa-play"></i> Iniciar</button>
              <button class="btn secondary" id="swPause"><i class="fa-solid fa-pause"></i> Pausar</button>
              <button class="btn ghost" id="swReset"><i class="fa-solid fa-rotate-left"></i> Zerar</button>
              <button class="btn" id="swLap"><i class="fa-solid fa-flag-checkered"></i> Volta</button>
            </div>
            <div class="list" id="lapsList" style="margin-top:12px"></div>
          </div>
        </section>

        <!-- METAS SEMANAIS -->
        <section class="card">
          <h2>🎯 Metas Semanais</h2>
          <p class="sub">Defina horas por disciplina e acompanhe o progresso (seg a dom).</p>
          <div class="row">
            <select id="goalDiscipline" class="select" style="flex:1"></select>
            <input id="goalHours" class="input" type="number" min="1" max="60" placeholder="Horas/semana" />
            <button class="btn" id="saveGoal">Salvar meta</button>
          </div>
          <div id="goalsList" class="list" style="margin-top:12px"></div>
        </section>

        <!-- TAREFAS -->
        <section class="card">
          <h2>✅ Tarefas</h2>
          <p class="sub">Crie checklists por disciplina e datas.</p>
          <div class="row">
            <input id="taskTitle" class="input" placeholder="Título da tarefa" style="flex:2" />
            <input id="taskDate" class="input" type="date" style="flex:1" />
          </div>
          <div class="row" style="margin-top:8px">
            <select id="taskDiscipline" class="select" style="flex:1"></select>
            <button class="btn" id="addTask"><i class="fa-solid fa-plus"></i> Adicionar</button>
          </div>
          <div id="tasksList" class="list" style="margin-top:12px"></div>
        </section>

        <!-- ESTATÍSTICAS — LINHA -->
        <section class="card wide" id="stats-line">
          <h2>📈 Horas por Dia (últimos 14 dias)</h2>
          <p class="sub">Visualize sua evolução diária de estudo.</p>
          <canvas id="lineChart"></canvas>
        </section>

        <!-- ESTATÍSTICAS — DISTRIBUIÇÃO -->
        <section class="card wide" id="stats-pie">
          <h2>🧩 Distribuição por Disciplina</h2>
          <p class="sub">Como suas horas se dividem entre as disciplinas.</p>
          <canvas id="pieChart"></canvas>
        </section>


        <!-- HISTÓRICO / EXPORT -->
        <section class="card full">
          <div class="row" style="justify-content:space-between; align-items:center">
            <h2>🗂️ Histórico de Sessões</h2>
            <div class="row">
              <button class="btn secondary" id="clearHistory"><i class="fa-solid fa-trash"></i> Limpar histórico</button>
              <button class="btn" id="exportCsv"><i class="fa-solid fa-file-arrow-down"></i> Exportar CSV</button>
            </div>
          </div>
          <table class="table" id="historyTable">
            <thead>
              <tr><th>Data</th><th>Disciplina</th><th>Modo</th><th>Duração (min)</th></tr>
            </thead>
            <tbody></tbody>
          </table>
        </section>
      </section>
    </main>
  </div>

  <!-- Modal de Sair -->
  <div id="logout-modal" class="modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:2000;justify-content:center;align-items:center;">
    <div class="modal-content" style="background:#fff;color:#333;padding:25px;border-radius:10px;text-align:center;width:300px;box-shadow:0 5px 20px rgba(0,0,0,.3)">
      <h3>Ah... já vai?</h3>
      <h4>Tem certeza que deseja sair?</h4>
      <div class="modal-buttons" style="margin-top:20px;display:flex;justify-content:center;gap:15px;">
        <button id="confirm-logout" class="btn">Sim</button>
        <button id="cancel-logout" class="btn secondary">Cancelar</button>
      </div>
    </div>
  </div>

  <footer>&copy; 2025 FOAG. Todos os direitos reservados.</footer>
</body>
</html>

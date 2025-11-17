<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>FOAG — Início</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- Fonte Poppins -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
body {
  margin: 0;
  padding: 0;
  background-color: #aad5f8;
  font-family: "Poppins", sans-serif;
}

/* CONTAINER PRINCIPAL */
.foag-wrapper {
  width: 80%;
  max-width: 900px;
  margin: 40px auto;
  display: flex;
  border-radius: 20px;
  overflow: hidden;
  background-color: white;
  box-shadow: 0 0 15px rgba(0,0,0,0.12);
}

/* LADO ESQUERDO */
.left {
  background-color: #38a5ff;
  flex: 1;
  color: white;
  padding: 40px 25px;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}

.left h1 {
  font-size: 1.8rem;
  margin: 0 0 8px 0;
}

.left p {
  font-size: .9rem;
  opacity: .95;
  max-width: 350px;
}

.badge {
  padding: 6px 12px;
  width: fit-content;
  background: rgba(255,255,255,0.25);
  border-radius: 999px;
  font-size: .75rem;
  margin-bottom: 10px;
  display: flex;
  align-items: center;
  gap: 6px;
}

.badge-dot {
  width: 9px;
  height: 9px;
  border-radius: 50%;
  background: #22c55e;
  box-shadow: 0 0 0 3px rgba(34,197,94,0.3);
}

.stats {
  margin-top: 25px;
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 12px;
}

.stat-box {
  background: rgba(255,255,255,0.22);
  padding: 10px;
  border-radius: 12px;
}

.stat-label {
  font-size: .75rem;
  opacity: .9;
}

.stat-value {
  font-size: 1.3rem;
  font-weight: 600;
  margin-top: 3px;
}

/* FOGI */
.fogi {
  display: flex;
  align-items: center;
  margin-top: 20px;
  gap: 10px;
  background: rgba(255,255,255,0.18);
  padding: 10px 12px;
  border-radius: 12px;
  font-size: .85rem;
}

.fogi-avatar {
  width: 34px;
  height: 34px;
  border-radius: 50%;
  background: white;
  color: #38a5ff;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.2rem;
}

/* LADO DIREITO */
.right {
  flex: 1.1;
  background: #f3f7ff;
  padding: 30px 22px;
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.right h2 {
  margin: 0;
  font-size: 1.1rem;
}

.sub {
  font-size: .85rem;
  color: #4b5563;
  margin-bottom: 5px;
}

/* TABELA */
table {
  width: 100%;
  font-size: .8rem;
  border-collapse: collapse;
  background: white;
  border-radius: 12px;
  overflow: hidden;
  border: 1px solid #cbd5e1;
}

thead {
  background: #e2ecff;
}

th, td {
  padding: 8px;
}

tbody tr:nth-child(even) {
  background: #f8faff;
}

.tag {
  padding: 2px 7px;
  border-radius: 999px;
  font-size: .7rem;
}

.tag-alerta { background: #fde04755; color: #b45309; }
.tag-ok { background: #7dd3fc55; color: #0369a1; }
.tag-danger { background: #fca5a555; color: #b91c1c; }

/* ATALHOS */
.shortcuts {
  display: grid;
  grid-template-columns: repeat(2,1fr);
  gap: 10px;
}

.shortcut {
  background: white;
  border: 1px solid #cbd5e1;
  padding: 10px 12px;
  border-radius: 12px;
  font-size: .85rem;
  cursor: pointer;
}

.shortcut:hover {
  background: #eef6ff;
  border-color: #38a5ff;
}
</style>

</head>
<body>

<div class="foag-wrapper">

  <!-- ESQUERDA -->
  <div class="left">
    <div>
      <div class="badge">
        <div class="badge-dot"></div>
        Semana ativa
      </div>

      <h1>Oi, <span style="font-weight:700;">Aluno</span> 👋</h1>
      <p>Aqui é seu painel FOAG. Você acompanha tudo da escola sem surtar.</p>

      <div class="stats">
        <div class="stat-box">
          <div class="stat-label">Provas</div>
          <div class="stat-value">3</div>
        </div>
        <div class="stat-box">
          <div class="stat-label">Tarefas</div>
          <div class="stat-value">5</div>
        </div>
        <div class="stat-box">
          <div class="stat-label">Faltas</div>
          <div class="stat-value">1</div>
        </div>
        <div class="stat-box">
          <div class="stat-label">Média</div>
          <div class="stat-value">8.4</div>
        </div>
      </div>
    </div>

    <div class="fogi">
      <div class="fogi-avatar">😼</div>
      “Quer ajuda no estudo da próxima prova?”
    </div>
  </div>

  <!-- DIREITA -->
  <div class="right">

    <div>
      <h2>Próximas atividades</h2>
      <p class="sub">Fica ligado nos prazos 👇</p>

      <table>
        <thead>
          <tr>
            <th>Data</th>
            <th>Matéria</th>
            <th>Tipo</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>18/11</td>
            <td>Matemática</td>
            <td>Prova</td>
            <td><span class="tag tag-alerta">em 3 dias</span></td>
          </tr>
          <tr>
            <td>19/11</td>
            <td>História</td>
            <td>Trabalho</td>
            <td><span class="tag tag-ok">prazo ok</span></td>
          </tr>
          <tr>
            <td>21/11</td>
            <td>Português</td>
            <td>Redação</td>
            <td><span class="tag tag-danger">não iniciado</span></td>
          </tr>
        </tbody>
      </table>
    </div>

    <div>
      <h2>Atalhos rápidos</h2>
      <p class="sub">Vá direto ao ponto:</p>

      <div class="shortcuts">
        <button class="shortcut">📅 Calendário</button>
        <button class="shortcut">📊 Notas</button>
        <button class="shortcut">📝 Tarefas</button>
        <button class="shortcut">👤 Perfil</button>
      </div>
    </div>

  </div>

</div>

</body>
</html>

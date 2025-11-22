// ------------------------------
// CONSTANTES / ESTADO
// ------------------------------
const PERIODOS = ["manha", "tarde", "noite"];
let ultimaCelulaFocada = null;

// ------------------------------
// INICIALIZAÇÃO GERAL
// ------------------------------
document.addEventListener("DOMContentLoaded", () => {
  inicializarHorarioFOAG();
});

function inicializarHorarioFOAG() {
  // Carregar horário do período ativo (ou padrão)
  const periodoAtual = getPeriodoAtual();
  carregarEdicoes(periodoAtual);

  // Registrar foco nas células editáveis
  registrarFocusNasCelulas();

  // Tabs de período (Manhã / Tarde / Noite)
  inicializarTabsHorario();

  // Paleta de matérias
  inicializarPaletaMaterias();

  // Modal de logout
  inicializarLogoutModal();

  // Re-carregar quando mudar de período (se já tiver salvo)
  // (isso já é feito nas tabs, mas se você mudar lógica depois, tá separado)
}

// ------------------------------
// PERÍODO (ABAS)
// ------------------------------
function getPeriodoAtual() {
  const tabAtiva = document.querySelector(".tab-horario.active");
  if (tabAtiva && tabAtiva.dataset.periodo) {
    return tabAtiva.dataset.periodo;
  }
  // fallback
  return "manha";
}

function getStorageKey(periodo) {
  return `horarioEscolar_${periodo || getPeriodoAtual()}`;
}

function inicializarTabsHorario() {
  const tabs = document.querySelectorAll(".tab-horario");
  if (!tabs.length) return; // se não tiver abas, ignora

  tabs.forEach(tab => {
    tab.addEventListener("click", () => {
      // troca classe active
      tabs.forEach(t => t.classList.remove("active"));
      tab.classList.add("active");

      // carrega horário do período dessa aba
      const periodo = tab.dataset.periodo || "manha";
      carregarEdicoes(periodo);

      // re-registra focos nas novas células
      registrarFocusNasCelulas();
    });
  });
}

// ------------------------------
// FOCO NAS CÉLULAS (pra paleta funcionar)
// ------------------------------
function registrarFocusNasCelulas() {
  const tabela = document.getElementById("scheduleTable");
  if (!tabela) return;

  // remove listeners antigos? não precisa; a cada load a tabela é recriada
  const celulasEditaveis = tabela.querySelectorAll('td[contenteditable="true"]');
  celulasEditaveis.forEach(td => {
    td.addEventListener("focus", () => {
      ultimaCelulaFocada = td;
    });
  });
}

// ------------------------------
// PALETA DE MATÉRIAS
// ------------------------------
function inicializarPaletaMaterias() {
  const pills = document.querySelectorAll(".pill-materia");
  if (!pills.length) return; // se não tiver paleta, ignora

  pills.forEach(pill => {
    pill.addEventListener("click", () => {
      if (!ultimaCelulaFocada) return;

      const texto = pill.dataset.text || pill.innerText.trim();

      // pill especial pra limpar célula
      if (pill.classList.contains("pill-clear")) {
        ultimaCelulaFocada.textContent = "";
      } else {
        ultimaCelulaFocada.textContent = texto;
      }
    });
  });
}

// ------------------------------
// LOCALSTORAGE – SALVAR / CARREGAR
// ------------------------------
function salvarEdicoes() {
  const tabela = document.getElementById("scheduleTable");
  if (!tabela) return;

  const periodo = getPeriodoAtual();
  const key = getStorageKey(periodo);

  // validação simples: pelo menos uma matéria preenchida
  const linhas = tabela.tBodies[0]?.rows || [];
  let temConteudo = false;

  for (let i = 0; i < linhas.length; i++) {
    const cells = linhas[i].cells;
    for (let j = 1; j < cells.length; j++) { // ignora coluna de horário
      if (cells[j].textContent.trim() !== "") {
        temConteudo = true;
        break;
      }
    }
    if (temConteudo) break;
  }

  if (!temConteudo) {
    alert("Nenhuma matéria preenchida ainda. Preencha o horário antes de salvar.");
    return;
  }

  localStorage.setItem(key, tabela.tBodies[0].innerHTML);
  alert(`Horário (${periodo}) salvo com sucesso!`);
}

function carregarEdicoes(periodo) {
  const tabela = document.getElementById("scheduleTable");
  if (!tabela || !tabela.tBodies[0]) return;

  const key = getStorageKey(periodo);
  const salvo = localStorage.getItem(key);

  if (salvo) {
    tabela.tBodies[0].innerHTML = salvo;
  } else {
    // se quiser limpar quando não tiver nada salvo
    // tabela.tBodies[0].innerHTML = "";
  }

  // re-anexar focus nas novas células
  registrarFocusNasCelulas();
}

// ------------------------------
// ADICIONAR / REMOVER LINHA
// ------------------------------
function adicionarLinha() {
  const tabela = document.getElementById("scheduleTable");
  if (!tabela || !tabela.tBodies[0]) return;

  const tbody = tabela.tBodies[0];
  const totalColunas = tabela.tHead.rows[0].cells.length; // Horário + dias

  const novaLinha = tbody.insertRow();

  for (let i = 0; i < totalColunas; i++) {
    const celula = novaLinha.insertCell();
    celula.contentEditable = true;
  }

  registrarFocusNasCelulas();
}

function removerLinha() {
  const tabela = document.getElementById("scheduleTable");
  if (!tabela || !tabela.tBodies[0]) return;

  const tbody = tabela.tBodies[0];
  if (tbody.rows.length > 1) {
    tbody.deleteRow(tbody.rows.length - 1);
  }
}

// ------------------------------
// ADICIONAR INTERVALO
// ------------------------------
function adicionarIntervalo() {
  const tabela = document.getElementById("scheduleTable");
  if (!tabela || !tabela.tBodies[0]) return;

  const tbody = tabela.tBodies[0];
  const totalColunas = tabela.tHead.rows[0].cells.length;

  const novaLinha = tbody.insertRow();
  const celula = novaLinha.insertCell(0);

  celula.colSpan = totalColunas;
  celula.contentEditable = true;
  celula.textContent = "Intervalo";

  // as outras células não existem por causa do colspan
}

// ------------------------------
// HORÁRIOS PADRÃO (OPCIONAL)
// ------------------------------
// se você tiver um botão chamando preencherHorariosPadrao()
function preencherHorariosPadrao() {
  const tabela = document.getElementById("scheduleTable");
  if (!tabela || !tabela.tBodies[0]) return;

  const tbody = tabela.tBodies[0];
  const horariosPadrao = [
    "07:00 - 07:50",
    "07:50 - 08:40",
    "08:40 - 09:30",
    "09:30 - 09:45 (Intervalo)",
    "09:45 - 10:35",
    "10:35 - 11:25"
  ];

  // garante quantidade de linhas suficiente
  while (tbody.rows.length < horariosPadrao.length) {
    adicionarLinha();
  }

  for (let i = 0; i < horariosPadrao.length; i++) {
    const linha = tbody.rows[i];
    if (!linha) continue;

    const celHorario = linha.cells[0];
    if (!celHorario) continue;

    celHorario.textContent = horariosPadrao[i];

    // marca intervalo de forma diferente se quiser
    if (horariosPadrao[i].toLowerCase().includes("intervalo")) {
      // você pode deixar as outras células vazias mesmo
    }
  }
}

// ------------------------------
// PDF – jsPDF + AutoTable
// ------------------------------
function salvarComoPDF() {
  const { jsPDF } = window.jspdf;
  const doc = new jsPDF();

  const tabela = document.getElementById("scheduleTable");
  if (!tabela) return;

  const rows = tabela.rows;
  if (!rows.length) return;

  const periodo = getPeriodoAtual();

  // Info do aluno (se existir no HTML)
  const nome   = document.getElementById("info-nome")?.value || "";
  const escola = document.getElementById("info-escola")?.value || "";
  const serie  = document.getElementById("info-serie")?.value || "";
  const turno  = document.getElementById("info-turno")?.value || "";

  // Cabeçalho "FOAG"
  doc.setFont("helvetica", "bold");
  doc.setFontSize(20);
  doc.text("FOAG - Horário Escolar", 10, 12);

  // Linha com dados do aluno
  doc.setFontSize(11);
  doc.setFont("helvetica", "normal");

  let linhaY = 20;
  if (nome) {
    doc.text(`Aluno(a): ${nome}`, 10, linhaY);
    linhaY += 6;
  }
  if (escola && serie) {
    doc.text(`Escola: ${escola}  |  Série: ${serie}`, 10, linhaY);
    linhaY += 6;
  }
  if (turno) {
    doc.text(`Turno: ${turno}`, 10, linhaY);
    linhaY += 6;
  }

  // Período atual
  doc.text(`Período (FOAG): ${periodo}`, 10, linhaY);
  linhaY += 6;

  // Data/hora de geração
  const dataAtual = new Date();
  const dataFormatada = dataAtual.toLocaleString("pt-BR", {
    weekday: "long",
    year: "numeric",
    month: "long",
    day: "numeric",
    hour: "2-digit",
    minute: "2-digit"
  });
  doc.setFontSize(10);
  doc.text(`Gerado em: ${dataFormatada}`, 10, linhaY);

  // Monta dados da tabela
  const headers = [];
  for (let i = 0; i < rows[0].cells.length; i++) {
    headers.push(rows[0].cells[i].textContent.trim());
  }

  const data = [];
  for (let i = 1; i < rows.length; i++) {
    const row = [];
    for (let j = 0; j < rows[i].cells.length; j++) {
      row.push(rows[i].cells[j].textContent.trim());
    }
    data.push(row);
  }

  // AutoTable
  doc.autoTable({
    head: [headers],
    body: data,
    startY: linhaY + 6,
    theme: "grid",
    styles: {
      fontSize: 9
    },
    headStyles: {
      fillColor: [56, 165, 255],
      textColor: [255, 255, 255],
      fontStyle: "bold"
    },
    bodyStyles: {
      textColor: [15, 23, 42]
    },
    alternateRowStyles: {
      fillColor: [240, 244, 248]
    }
  });

  doc.save(`horario_escolar_${periodo}.pdf`);
}

// ------------------------------
// MODAL LOGOUT
// ------------------------------
function inicializarLogoutModal() {
  const logoutModal   = document.getElementById("logout-modal");
  const confirmLogout = document.getElementById("confirm-logout");
  const cancelLogout  = document.getElementById("cancel-logout");
  const iconSair      = document.getElementById("icon-sair");

  if (!logoutModal || !confirmLogout || !cancelLogout || !iconSair) return;

  // abrir modal
  iconSair.addEventListener("click", () => {
    logoutModal.style.display = "flex";
  });

  // confirmar
  confirmLogout.addEventListener("click", () => {
    window.location.href = "../index/index.php";
  });

  // cancelar
  cancelLogout.addEventListener("click", () => {
    logoutModal.style.display = "none";
  });

  // clicar fora
  logoutModal.addEventListener("click", e => {
    if (e.target === logoutModal) {
      logoutModal.style.display = "none";
    }
  });
}

/* ------------------------------
   FUNÇÕES ANTIGAS CONTINUAM VISÍVEIS
   (porque os botões chamam por onclick)
   ------------------------------ */
// deixar acessíveis no escopo global
window.salvarEdicoes = salvarEdicoes;
window.carregarEdicoes = carregarEdicoes;
window.adicionarLinha = adicionarLinha;
window.removerLinha = removerLinha;
window.adicionarIntervalo = adicionarIntervalo;
window.salvarComoPDF = salvarComoPDF;
window.preencherHorariosPadrao = preencherHorariosPadrao;

function salvarEdicoes() {
    const tabela = document.getElementById("scheduleTable");
    localStorage.setItem("horarioEscolar", tabela.innerHTML);
    alert("Horário salvo com sucesso!");
}

function carregarEdicoes() {
    const tabela = document.getElementById("scheduleTable");
    const dadosSalvos = localStorage.getItem("horarioEscolar");
    if (dadosSalvos) {
        tabela.innerHTML = dadosSalvos;
    }
}

function adicionarLinha() {
    const tabela = document.getElementById("scheduleTable");
    const novaLinha = tabela.insertRow(tabela.rows.length);
    for (let i = 0; i < 6; i++) {
        const celula = novaLinha.insertCell(i);
        celula.contentEditable = true;
        celula.style.backgroundColor = i === 0 ? "#38a5ff" : "#ececec";
        celula.style.color = i === 0 ? "white" : "black";
        if (i === 0) {
            celula.innerHTML = "";
        }
    }
}

function removerLinha() {
    const tabela = document.getElementById("scheduleTable");
    if (tabela.rows.length > 1) {
        tabela.deleteRow(tabela.rows.length - 1);
    }
}

function adicionarIntervalo() {
    const tabela = document.getElementById("scheduleTable");
    const novaLinha = tabela.insertRow(tabela.rows.length);
    const celula = novaLinha.insertCell(0);
    celula.colSpan = 6;
    celula.contentEditable = true;
    celula.style.backgroundColor = "#38a5ff";
    celula.style.color = "white";
    celula.innerHTML = "Intervalo";
}

function salvarComoPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    const tabela = document.getElementById("scheduleTable");
    const rows = tabela.rows;

    let data = [];
    const headers = [];

    // Cabeçalho
    for (let i = 0; i < rows[0].cells.length; i++) {
        headers.push(rows[0].cells[i].textContent);
    }

    // Linhas
    for (let i = 1; i < rows.length; i++) {
        let row = [];
        for (let j = 0; j < rows[i].cells.length; j++) {
            row.push(rows[i].cells[j].textContent);
        }
        data.push(row);
    }

    // Nome do site (usa fonte suportada)
    doc.setFont("helvetica", "bold");
    doc.setFontSize(24);
    doc.text("FOAG", 10, 10);

    // Data
    const dataAtual = new Date();
    const dataFormatada = dataAtual.toLocaleDateString('pt-BR', {
        weekday: 'long', year: 'numeric', month: 'long', day: 'numeric',
        hour: '2-digit', minute: '2-digit', second: '2-digit'
    });
    doc.setFontSize(12);
    doc.text(`Gerado em: ${dataFormatada}`, 10, 20);

    // Gera tabela
    doc.autoTable({
        head: [headers],
        body: data,
        startY: 30,
        theme: 'grid',
        margin: { top: 10 },
        tableWidth: 'auto',
        headStyles: {
            fillColor: [56, 165, 255],
            textColor: [255, 255, 255],
            fontSize: 12,
            fontStyle: 'bold',
        },
        bodyStyles: {
            fillColor: [255, 255, 255],
            textColor: [56, 165, 255],
            fontSize: 10,
        },
        alternateRowStyles: {
            fillColor: [240, 240, 240]
        }
    });

    doc.save('horario_escolar.pdf');
}

const logoutModal = document.getElementById('logout-modal');
const confirmLogout = document.getElementById('confirm-logout');
const cancelLogout = document.getElementById('cancel-logout');
const iconSair = document.getElementById('icon-sair');

// Abrir modal ao clicar no ícone de sair
iconSair.addEventListener('click', () => {
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

// Fecha o modal se clicar fora da caixa
logoutModal.addEventListener('click', e => {
  if (e.target === logoutModal) {
    logoutModal.style.display = 'none';
  }
});


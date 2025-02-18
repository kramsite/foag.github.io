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
        celula.style.backgroundColor = i === 0 ? "#007BFF" : "#e3f2fd";
        celula.style.color = i === 0 ? "white" : "black";
        if (i === 0) {
            celula.innerHTML = "Novo Horário";
        }
    }
}

function adicionarIntervalo() {
    const tabela = document.getElementById("scheduleTable");
    const novaLinha = tabela.insertRow(tabela.rows.length);
    const celula = novaLinha.insertCell(0);
    celula.colSpan = 6;
    celula.contentEditable = true;
    celula.style.backgroundColor = "#ffcccb";
    celula.innerHTML = "Intervalo";
}

// Função para salvar a tabela como PDF
function salvarComoPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    // Tabela
    const tabela = document.getElementById("scheduleTable");
    const rows = tabela.rows;

    let yPosition = 10; // Posição vertical inicial no PDF

    // Adicionando cabeçalho da tabela
    doc.setFontSize(12);
    for (let i = 0; i < rows[0].cells.length; i++) {
        doc.text(rows[0].cells[i].textContent, 10 + (i * 40), yPosition);
    }
    yPosition += 10;

    // Adicionando o conteúdo da tabela
    for (let i = 1; i < rows.length; i++) {
        for (let j = 0; j < rows[i].cells.length; j++) {
            doc.text(rows[i].cells[j].textContent, 10 + (j * 40), yPosition);
        }
        yPosition += 10;
    }

    // Gerando o arquivo PDF
    doc.save('horario_escolar.pdf');
}

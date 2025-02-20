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
    // Remover a última linha, mas não o cabeçalho (índice 0)
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
    celula.innerHTML = "Intervalo";
}

// Função para salvar a tabela como PDF com formato de tabela
function salvarComoPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    const tabela = document.getElementById("scheduleTable");
    const rows = tabela.rows;

    let data = [];
    
    // Cabeçalho
    const headers = [];
    for (let i = 0; i < rows[0].cells.length; i++) {
        headers.push(rows[0].cells[i].textContent);
    }

    // Dados da tabela
    for (let i = 1; i < rows.length; i++) {
        let row = [];
        for (let j = 0; j < rows[i].cells.length; j++) {
            row.push(rows[i].cells[j].textContent);
        }
        data.push(row);
    }

    // Adicionar o nome do site "FOAG" no canto superior esquerdo com uma fonte bonita
    doc.setFont("courier"); // Fonte padrão, pois jsPDF não tem Snap ITC embutida
    doc.setFontSize(24);
    doc.text("FOAG", 10, 10); // Posição (10, 10) coloca "FOAG" no canto superior esquerdo

    // Adicionar a data no topo
    const dataAtual = new Date();
    const dataFormatada = dataAtual.toLocaleDateString('pt-BR', {
        weekday: 'long', year: 'numeric', month: 'long', day: 'numeric',
        hour: '2-digit', minute: '2-digit', second: '2-digit'
    });

    doc.setFontSize(12);
    doc.text(`Gerado em: ${dataFormatada}`, 10, 20); // Posição (10, 20) coloca a data logo abaixo de "FOAG"

    // Gerando a tabela no PDF usando autoTable
    doc.autoTable({
        head: [headers],
        body: data,
        startY: 30,  // Ajuste para não sobrepor o nome do site e a data
        theme: 'grid',
        margin: { top: 10 },
        tableWidth: 'auto',
        headStyles: {
            fillColor: [56, 165, 255], // Cor de fundo do cabeçalho
            textColor: [255, 255, 255], // Cor do texto do cabeçalho (branco)
            fontSize: 12,
            fontStyle: 'bold',
        },
        bodyStyles: {
            fillColor: [255, 255, 255], // Cor de fundo das células (branco)
            textColor: [56, 165, 255],  // Cor do texto das células (cor 38a5ff)
            fontSize: 10,
            fontStyle: 'normal',
        },
        alternateRowStyles: {
            fillColor: [240, 240, 240]  // Cor alternada para as linhas
        }
    });

    // Gerando o arquivo PDF
    doc.save('horario_escolar.pdf');
}

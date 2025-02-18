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

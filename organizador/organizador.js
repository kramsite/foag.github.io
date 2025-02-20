document.addEventListener('DOMContentLoaded', () => {
    const addTarefa = document.getElementById('add-tarefa');
    const listaTarefas = document.getElementById('tabela-tarefas');
    const addNaoEsquecer = document.getElementById('add-nao-esquecer');
    const listaNaoEsquecer = document.getElementById('tabela-nao-esquecer');

    console.log(listaTarefas);
    console.log(listaNaoEsquecer);

    if (addTarefa) {
        addTarefa.addEventListener('click', () => {
            const newRow = listaTarefas.insertRow();
            const cell1 = newRow.insertCell();
            const cell2 = newRow.insertCell();
            cell1.textContent = listaTarefas.rows.length;
            cell2.setAttribute('contenteditable', 'true');
        });
    }

    if (addNaoEsquecer) {
        addNaoEsquecer.addEventListener('click', () => {
            const newRow = listaNaoEsquecer.insertRow();
            const cell1 = newRow.insertCell();
            const cell2 = newRow.insertCell();
            cell1.textContent = listaNaoEsquecer.rows.length;
            cell2.setAttribute('contenteditable', 'true');
        });
    }
});
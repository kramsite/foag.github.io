document.addEventListener('DOMContentLoaded', () => {
    const addTarefa = document.getElementById('add-tarefa');
    const listaTarefas = document.getElementById('tabela-tarefas');
    const addNaoEsquecer = document.getElementById('add-nao-esquecer');
    const listaNaoEsquecer = document.getElementById('tabela-nao-esquecer');

    // Funções para gerenciar as tarefas
    if (addTarefa) {
        addTarefa.addEventListener('click', () => {
            const newRow = listaTarefas.insertRow();
            const cell1 = newRow.insertCell();
            const cell2 = newRow.insertCell();
            cell1.textContent = listaTarefas.rows.length;
            cell2.setAttribute('contenteditable', 'true');

            // Adiciona um ouvinte de evento para limpar o conteúdo ao começar a digitar
            cell2.addEventListener('focus', () => {
                if (cell2.textContent === 'Tarefa ' + (listaTarefas.rows.length - 1)) {
                    cell2.textContent = '';
                }
            });
        });
    }

    if (addNaoEsquecer) {
        addNaoEsquecer.addEventListener('click', () => {
            const newRow = listaNaoEsquecer.insertRow();
            const cell1 = newRow.insertCell();
            const cell2 = newRow.insertCell();
            cell1.textContent = listaNaoEsquecer.rows.length;
            cell2.setAttribute('contenteditable', 'true');

            // Adiciona um ouvinte de evento para limpar o conteúdo ao começar a digitar
            cell2.addEventListener('focus', () => {
                if (cell2.textContent === 'Item ' + (listaNaoEsquecer.rows.length - 1)) {
                    cell2.textContent = '';
                }
            });
        });
    }

    // Adiciona ouvintes de evento para limpar o conteúdo existente nas células iniciais
    const tarefasIniciais = listaTarefas.querySelectorAll('td[contenteditable="true"]');
    tarefasIniciais.forEach(cell => {
        cell.addEventListener('focus', () => {
            if (cell.textContent.startsWith('Tarefa ')) {
                cell.textContent = '';
            }
        });
    });

    const itensIniciais = listaNaoEsquecer.querySelectorAll('td[contenteditable="true"]');
    itensIniciais.forEach(cell => {
        cell.addEventListener('focus', () => {
            if (cell.textContent.startsWith('Item ')) {
                cell.textContent = '';
            }
        });
    });

    // Funções para gerenciar as notas
    function salvar() {
        const texto = document.getElementById('notas').value;
        const title = prompt('Digite um título para a nota:');

        if (title && title.trim() !== '') {
            localStorage.setItem('nota-' + title, texto);
            carregarNotas(); // Atualiza a lista após salvar
            alert('Nota salva com sucesso!');
        } else {
            alert('O título da nota não pode estar vazio!');
        }
    }

    function excluirNota(title) {
        localStorage.removeItem('nota-' + title);
        carregarNotas();
    }

    function carregarNotas() {
        const noteList = document.getElementById('noteList');
        noteList.innerHTML = ''; // Clear the list

        for (let i = 0; i < localStorage.length; i++) {
            const key = localStorage.key(i);
            if (key.startsWith('nota-')) {
                const title = key.substring(5);
                const listItem = document.createElement('li');
                listItem.textContent = title;

                // Add click event listener to open the note
                listItem.addEventListener('click', () => {
                    const noteContent = localStorage.getItem('nota-' + title);
                    document.getElementById('notas').value = noteContent;
                });

                const deleteButton = document.createElement('button');
                deleteButton.textContent = 'Excluir';
                deleteButton.onclick = () => excluirNota(title);

                listItem.appendChild(deleteButton);
                noteList.appendChild(listItem);
            }
        }
    }

    // Chama carregarNotas() quando a página carrega
    window.addEventListener('DOMContentLoaded', carregarNotas);

    // Adicionando o evento de salvar
    const salvarNotaButton = document.querySelector("button[onclick='salvar()']");
    if (salvarNotaButton) {
        salvarNotaButton.addEventListener('click', salvar);
    }
});
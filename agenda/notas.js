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
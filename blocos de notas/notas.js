function salvar() {
    const texto = document.getElementById('notas').value;
    const title = prompt('Digite um título para a nota:');

    if (title && title.trim() !== '') {
        localStorage.setItem('nota-' + title, texto);
        carregarNotas();
        alert('Nota salva com sucesso!');
    } else {
        alert('O título da nota não pode estar vazio!');
    }
}

function excluirNota(title) {
    // Remove a nota do localStorage
    localStorage.removeItem('nota-' + title);
    carregarNotas(); // Atualiza a lista de notas
}

function carregarNotas() {
    const noteList = document.getElementById('noteList');
    noteList.innerHTML = ''; // Limpa a lista antes de adicionar as notas

    // Itera por todas as notas no localStorage
    for (let i = 0; i < localStorage.length; i++) {
        const key = localStorage.key(i);
        if (key.startsWith('nota-')) {
            const title = key.substring(5);
            const listItem = document.createElement('li');
            listItem.textContent = title;

            // Adiciona o botão de excluir à nota
            const deleteButton = document.createElement('button');
            deleteButton.textContent = 'Excluir';
            deleteButton.onclick = () => excluirNota(title);

            listItem.appendChild(deleteButton);
            noteList.appendChild(listItem);
        }
    }
}

window.onload = carregarNotas;
document.addEventListener('DOMContentLoaded', () => {
    // Elementos do DOM
    const listaTarefas = document.getElementById('lista-tarefas');
    const listaNaoEsquecer = document.getElementById('lista-nao-esquecer');
    const salvarNotaButton = document.getElementById('btn-salvar-nota');
    const textareaNotas = document.querySelector('#notas textarea');
    const noteList = document.getElementById('noteList');
    const themeToggle = document.getElementById('themeToggle');

    // =============================================
    // FUNÇÕES PARA TAREFAS E "NÃO ESQUECER"
    // =============================================

    function criarLinha(lista) {
        const index = lista.rows.length + 1;
        const row = lista.insertRow();

        // Célula do índice
        const cellIndex = row.insertCell(0);
        cellIndex.textContent = index;

        // Célula do conteúdo (editável)
        const cellConteudo = row.insertCell(1);
        cellConteudo.contentEditable = true;
        cellConteudo.style.wordBreak = 'break-word';

        // Célula da data
        const cellData = row.insertCell(2);
        const inputData = document.createElement('input');
        inputData.type = 'date';
        cellData.appendChild(inputData);

        // Célula de ações (botão excluir)
        const cellAcoes = row.insertCell(3);
        const btnExcluir = document.createElement('button');
        btnExcluir.textContent = 'Excluir';
        btnExcluir.className = 'btn-excluir';
        
        btnExcluir.addEventListener('click', function() {
            if (confirm('Deseja realmente excluir esta linha?')) {
                lista.deleteRow(row.rowIndex - 1);
                // Atualiza os índices e salva
                atualizarIndices(lista);
                salvarDados();
            }
        });

        cellAcoes.appendChild(btnExcluir);
    }

    function atualizarIndices(lista) {
        Array.from(lista.rows).forEach((row, idx) => {
            row.cells[0].textContent = idx + 1;
        });
    }

    // =============================================
    // PERSISTÊNCIA DE DADOS (TAREFAS E ITENS)
    // =============================================

    function salvarDados() {
        // Salvar tarefas
        const tarefas = [];
        document.querySelectorAll('#lista-tarefas tr').forEach(linha => {
            tarefas.push({
                texto: linha.cells[1].textContent,
                data: linha.cells[2].querySelector('input').value
            });
        });
        localStorage.setItem('tarefas-salvas', JSON.stringify(tarefas));

        // Salvar "Não Esquecer"
        const itens = [];
        document.querySelectorAll('#lista-nao-esquecer tr').forEach(linha => {
            itens.push({
                texto: linha.cells[1].textContent,
                data: linha.cells[2].querySelector('input').value
            });
        });
        localStorage.setItem('nao-esquecer-salvos', JSON.stringify(itens));
    }

    function carregarDados() {
        // Carregar tarefas
        const tarefas = JSON.parse(localStorage.getItem('tarefas-salvas') || '[]');
        tarefas.forEach(tarefa => {
            criarLinha(listaTarefas);
            const linhas = listaTarefas.querySelectorAll('tr');
            const ultimaLinha = linhas[linhas.length - 1];
            ultimaLinha.cells[1].textContent = tarefa.texto;
            ultimaLinha.cells[2].querySelector('input').value = tarefa.data;
        });

        // Carregar "Não Esquecer"
        const itens = JSON.parse(localStorage.getItem('nao-esquecer-salvos') || '[]');
        itens.forEach(item => {
            criarLinha(listaNaoEsquecer);
            const linhas = listaNaoEsquecer.querySelectorAll('tr');
            const ultimaLinha = linhas[linhas.length - 1];
            ultimaLinha.cells[1].textContent = item.texto;
            ultimaLinha.cells[2].querySelector('input').value = item.data;
        });
    }

    // =============================================
    // FUNÇÕES PARA NOTAS
    // =============================================

    function salvarNota() {
        const texto = textareaNotas.value.trim();
        if (!texto) {
            alert('A nota está vazia. Escreva algo antes de salvar.');
            return;
        }
        
        const title = prompt('Digite um título para a nota:');
        if (title && title.trim()) {
            if (localStorage.getItem('nota-' + title.trim())) {
                if (!confirm('Já existe uma nota com esse título. Deseja sobrescrever?')) {
                    return;
                }
            }
            localStorage.setItem('nota-' + title.trim(), texto);
            carregarNotas();
            textareaNotas.value = '';
            alert('Nota salva com sucesso!');
        } else {
            alert('O título da nota não pode estar vazio!');
        }
    }

    function carregarNotas() {
        noteList.innerHTML = '';

        for (let i = 0; i < localStorage.length; i++) {
            const key = localStorage.key(i);
            if (key.startsWith('nota-')) {
                const title = key.substring(5);
                const content = localStorage.getItem(key);

                const listItem = document.createElement('li');
                listItem.style.display = 'flex';
                listItem.style.alignItems = 'center';
                listItem.style.justifyContent = 'space-between';

                const spanTitle = document.createElement('span');
                spanTitle.textContent = title;
                spanTitle.style.cursor = 'pointer';
                spanTitle.style.flexGrow = '1';
                spanTitle.addEventListener('click', () => {
                    textareaNotas.value = content;
                });

                const divButtons = document.createElement('div');

                const btnBaixar = document.createElement('button');
                btnBaixar.textContent = 'Baixar PDF';
                btnBaixar.className = 'btn-pequeno';
                btnBaixar.style.marginRight = '8px';
                btnBaixar.addEventListener('click', () => {
                    baixarPdf(title, content);
                });

                const btnExcluir = document.createElement('button');
                btnExcluir.textContent = 'Excluir';
                btnExcluir.className = 'btn-excluir';
                btnExcluir.addEventListener('click', () => {
                    excluirNota(title);
                });

                divButtons.appendChild(btnBaixar);
                divButtons.appendChild(btnExcluir);

                listItem.appendChild(spanTitle);
                listItem.appendChild(divButtons);

                noteList.appendChild(listItem);
            }
        }
    }

    function excluirNota(title) {
        if (confirm(`Excluir a nota "${title}"?`)) {
            localStorage.removeItem('nota-' + title);
            carregarNotas();
        }
    }

    function baixarPdf(title, content) {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        const pageWidth = doc.internal.pageSize.getWidth();
        const margin = 10;
        let y = 10;

        // Cabeçalho
        doc.setFontSize(20);
        doc.setTextColor(40, 40, 120);
        doc.text('FOAG - Minhas Notas', pageWidth / 2, y, { align: 'center' });
        y += 8;

        doc.setFontSize(10);
        doc.text(`Exportado em: ${new Date().toLocaleString('pt-BR')}`, pageWidth / 2, y, { align: 'center' });
        y += 10;

        // Título da nota
        doc.setFontSize(16);
        doc.setTextColor(0);
        doc.text(title, margin, y);
        y += 10;

        // Conteúdo
        doc.setFontSize(12);
        const splitContent = doc.splitTextToSize(content, pageWidth - 2 * margin);
        doc.text(splitContent, margin, y);

        doc.save(`${title}.pdf`);
    }

    // =============================================
    // MODO ESCURO
    // =============================================
window.addEventListener('storage', () => {
    // Força a atualização do modo escuro quando houver mudanças em outra aba
    document.dispatchEvent(new CustomEvent('darkModeUpdated'));
});

// Atualiza o modo ao carregar a página
document.addEventListener('DOMContentLoaded', () => {
    const isDark = localStorage.getItem('darkMode') === 'true';
    document.body.classList.toggle('dark-mode', isDark);
});

    // =============================================
    // EVENT LISTENERS
    // =============================================

    // Tarefas e Itens
    document.getElementById('add-tarefa').addEventListener('click', () => {
        criarLinha(listaTarefas);
        salvarDados();
    });

    document.getElementById('add-nao-esquecer').addEventListener('click', () => {
        criarLinha(listaNaoEsquecer);
        salvarDados();
    });

    // Notas
    salvarNotaButton.addEventListener('click', salvarNota);

    // Modo Escuro
    if (themeToggle) {
        themeToggle.addEventListener('click', toggleDarkMode);
    }

    // Salvar automaticamente ao editar
    listaTarefas.addEventListener('input', salvarDados);
    listaNaoEsquecer.addEventListener('input', salvarDados);

    // Carregar tudo ao iniciar
    carregarDados();
    carregarNotas();
    verificarModoEscuro();

    // Logout Modal (código existente)
    const logoutModal = document.getElementById('logout-modal');
    if (logoutModal) {
        document.getElementById('icon-sair').addEventListener('click', () => {
            logoutModal.style.display = 'flex';
        });

        document.getElementById('confirm-logout').addEventListener('click', () => {
            window.location.href = '../index/index.php';
        });

        document.getElementById('cancel-logout').addEventListener('click', () => {
            logoutModal.style.display = 'none';
        });

        logoutModal.addEventListener('click', (e) => {
            if (e.target === logoutModal) {
                logoutModal.style.display = 'none';
            }
        });
    }
});
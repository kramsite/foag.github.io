document.addEventListener('DOMContentLoaded', function() {
    // Elementos do DOM
    const listaTarefas = document.getElementById('lista-tarefas');
    const listaNaoEsquecer = document.getElementById('lista-nao-esquecer');
    const salvarNotaButton = document.getElementById('btn-salvar-nota');
    const textareaNotas = document.querySelector('#notas textarea');
    const noteList = document.getElementById('noteList');
    
    // Verificação se os elementos existem
    if (!listaTarefas || !listaNaoEsquecer || !salvarNotaButton || !textareaNotas || !noteList) {
        console.error("Alguns elementos não foram encontrados no DOM");
        return;
    }

    // Função para criar linhas nas tabelas (Tarefas e Não Esquecer)
    function criarLinha(lista) {
        try {
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
                    // Atualiza os índices das linhas restantes
                    Array.from(lista.rows).forEach((row, idx) => {
                        row.cells[0].textContent = idx + 1;
                    });
                }
            });

            cellAcoes.appendChild(btnExcluir);
        } catch (error) {
            console.error("Erro ao criar linha:", error);
        }
    }

    // Função para salvar notas
    function salvarNota() {
        try {
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
        } catch (error) {
            console.error("Erro ao salvar nota:", error);
            alert('Ocorreu um erro ao salvar a nota.');
        }
    }

    // Função para carregar notas salvas
    function carregarNotas() {
        try {
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
                    spanTitle.addEventListener('click', function() {
                        textareaNotas.value = content;
                    });

                    const divButtons = document.createElement('div');

                    const btnBaixar = document.createElement('button');
                    btnBaixar.textContent = 'Baixar PDF';
                    btnBaixar.className = 'btn-pequeno';
                    btnBaixar.style.marginRight = '8px';
                    btnBaixar.addEventListener('click', function() {
                        baixarPdf(title, content);
                    });

                    const btnExcluir = document.createElement('button');
                    btnExcluir.textContent = 'Excluir';
                    btnExcluir.className = 'btn-excluir';
                    btnExcluir.addEventListener('click', function() {
                        excluirNota(title);
                    });

                    divButtons.appendChild(btnBaixar);
                    divButtons.appendChild(btnExcluir);

                    listItem.appendChild(spanTitle);
                    listItem.appendChild(divButtons);

                    noteList.appendChild(listItem);
                }
            }
        } catch (error) {
            console.error("Erro ao carregar notas:", error);
        }
    }

    // Função para excluir nota
    function excluirNota(title) {
        try {
            if (confirm(`Excluir a nota "${title}"?`)) {
                localStorage.removeItem('nota-' + title);
                carregarNotas();
            }
        } catch (error) {
            console.error("Erro ao excluir nota:", error);
        }
    }

    // Função para gerar PDF
    function baixarPdf(title, content) {
        try {
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
        } catch (error) {
            console.error("Erro ao gerar PDF:", error);
            alert('Ocorreu um erro ao gerar o PDF.');
        }
    }

    // Event Listeners
    document.getElementById('add-tarefa').addEventListener('click', function() {
        criarLinha(listaTarefas);
    });

    document.getElementById('add-nao-esquecer').addEventListener('click', function() {
        criarLinha(listaNaoEsquecer);
    });

    salvarNotaButton.addEventListener('click', salvarNota);

    // Carrega as notas ao iniciar
    carregarNotas();

    // Configuração do modal de logout
    const logoutModal = document.getElementById('logout-modal');
    if (logoutModal) {
        document.getElementById('icon-sair').addEventListener('click', function() {
            logoutModal.style.display = 'flex';
        });

        document.getElementById('confirm-logout').addEventListener('click', function() {
            window.location.href = '../index/index.php';
        });

        document.getElementById('cancel-logout').addEventListener('click', function() {
            logoutModal.style.display = 'none';
        });

        logoutModal.addEventListener('click', function(e) {
            if (e.target === logoutModal) {
                logoutModal.style.display = 'none';
            }
        });
    }
});
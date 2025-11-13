document.addEventListener('DOMContentLoaded', function() {
    // Elementos do DOM
    const listaTarefas = document.getElementById('lista-tarefas');
    const listaNaoEsquecer = document.getElementById('lista-nao-esquecer');
    const salvarNotaButton = document.getElementById('btn-salvar-nota');
    const textareaNotas = document.querySelector('#notas textarea');
    const noteList = document.getElementById('noteList');
    
    // Elementos do modal de nomear nota
    const modalNomearNota = document.getElementById('modal-nomear-nota');
    const inputNomeNota = document.getElementById('nome-nota');
    const btnConfirmarNomeNota = document.getElementById('confirmar-nome-nota');
    const btnCancelarNomeNota = document.getElementById('cancelar-nome-nota');
    
    let notaPendente = null;

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
    // FUNÇÕES PARA NOTAS (COM MODAL)
    // =============================================

    function abrirModalNomearNota() {
        modalNomearNota.style.display = 'flex';
        inputNomeNota.value = '';
        inputNomeNota.focus();
    }
    
    function fecharModalNomearNota() {
        modalNomearNota.style.display = 'none';
        notaPendente = null;
    }
    
    function salvarNotaComTitulo(texto, titulo) {
        if (!titulo.trim()) {
            alert('Por favor, dê um nome para sua nota.');
            return false;
        }
        
        // Recuperar notas existentes
        let notas = JSON.parse(localStorage.getItem('notas')) || [];
        
        // Verificar se já existe uma nota com o mesmo título
        const notaExistente = notas.find(nota => nota.titulo === titulo);
        if (notaExistente) {
            if (!confirm('Já existe uma nota com esse título. Deseja sobrescrever?')) {
                return false;
            }
            // Remover a nota existente
            notas = notas.filter(nota => nota.titulo !== titulo);
        }
        
        // Adicionar nova nota com título e data/hora
        const novaNota = {
            id: Date.now(),
            titulo: titulo,
            texto: texto,
            data: new Date().toLocaleString('pt-BR')
        };
        
        notas.push(novaNota);
        
        // Salvar no localStorage
        localStorage.setItem('notas', JSON.stringify(notas));
        
        // Atualizar a exibição
        carregarNotas();
        return true;
    }
    
    function carregarNotas() {
        const notas = JSON.parse(localStorage.getItem('notas')) || [];
        noteList.innerHTML = '';
        
        if (notas.length === 0) {
            noteList.innerHTML = '<div class="sem-notas">Nenhuma nota salva ainda.</div>';
            return;
        }
        
        // Ordenar notas por data (mais recente primeiro)
        notas.sort((a, b) => b.id - a.id);
        
        // Adicionar cada nota à lista
        notas.forEach(nota => {
            const notaElement = document.createElement('div');
            notaElement.className = 'nota-item';
            notaElement.innerHTML = `
                <span class="nota-titulo">${nota.titulo}</span>
                <span class="nota-data">${nota.data}</span>
                <div class="nota-conteudo">${nota.texto}</div>
                <div class="nota-acoes">
                    <button class="btn-nota btn-editar" data-id="${nota.id}">Editar</button>
                    <button class="btn-nota btn-excluir-nota" data-id="${nota.id}">Excluir</button>
                    <button class="btn-nota btn-pequeno" data-title="${nota.titulo}">Baixar PDF</button>
                </div>
            `;
            
            noteList.appendChild(notaElement);
        });
        
        // Adicionar eventos aos botões
        document.querySelectorAll('.btn-excluir-nota').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = parseInt(this.getAttribute('data-id'));
                excluirNota(id);
            });
        });
        
        document.querySelectorAll('.btn-editar').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = parseInt(this.getAttribute('data-id'));
                editarNota(id);
            });
        });
        
        document.querySelectorAll('.btn-pequeno').forEach(btn => {
            btn.addEventListener('click', function() {
                const title = this.getAttribute('data-title');
                const notas = JSON.parse(localStorage.getItem('notas')) || [];
                const nota = notas.find(n => n.titulo === title);
                if (nota) {
                    baixarPdf(nota.titulo, nota.texto);
                }
            });
        });
    }
    
    function excluirNota(id) {
        if (confirm('Tem certeza que deseja excluir esta nota?')) {
            let notas = JSON.parse(localStorage.getItem('notas')) || [];
            notas = notas.filter(nota => nota.id !== id);
            localStorage.setItem('notas', JSON.stringify(notas));
            carregarNotas();
        }
    }
    
    function editarNota(id) {
        let notas = JSON.parse(localStorage.getItem('notas')) || [];
        const nota = notas.find(nota => nota.id === id);
        
        if (nota) {
            // Preencher o textarea com o conteúdo da nota
            textareaNotas.value = nota.texto;
            
            // Preencher o campo de título no modal
            inputNomeNota.value = nota.titulo;
            
            // Abrir o modal para edição
            notaPendente = nota.texto;
            abrirModalNomearNota();
            
            // Excluir a nota antiga
            excluirNota(id);
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

    // Notas com modal
    salvarNotaButton.addEventListener('click', function() {
        const textoNota = textareaNotas.value.trim();
        
        if (textoNota) {
            // Salvar o texto da nota temporariamente
            notaPendente = textoNota;
            
            // Limpar o campo de texto
            textareaNotas.value = '';
            
            // Abrir modal para nomear a nota
            abrirModalNomearNota();
        } else {
            alert('Por favor, escreva algo na nota antes de salvar.');
        }
    });
    
    btnConfirmarNomeNota.addEventListener('click', function() {
        const nomeNota = inputNomeNota.value.trim();
        
        if (salvarNotaComTitulo(notaPendente, nomeNota)) {
            fecharModalNomearNota();
        }
    });
    
    btnCancelarNomeNota.addEventListener('click', function() {
        fecharModalNomearNota();
    });
    
    // Fechar modal ao clicar fora dele
    modalNomearNota.addEventListener('click', function(e) {
        if (e.target === modalNomearNota) {
            fecharModalNomearNota();
        }
    });

    // Salvar automaticamente ao editar
    listaTarefas.addEventListener('input', salvarDados);
    listaNaoEsquecer.addEventListener('input', salvarDados);

    // Carregar tudo ao iniciar
    carregarDados();
    carregarNotas();

    // Logout Modal
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
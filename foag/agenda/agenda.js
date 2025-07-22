document.addEventListener('DOMContentLoaded', () => {
  const listaTarefas = document.getElementById('lista-tarefas');
  const listaNaoEsquecer = document.getElementById('lista-nao-esquecer');
  const salvarNotaButton = document.getElementById('btn-salvar-nota');
  const textareaNotas = document.querySelector('#notas textarea');

  // Removido o botão global de baixar PDF pois agora será individual

  // Função para salvar uma nota
  function salvarNota() {
    const texto = textareaNotas.value.trim();
    if (!texto) {
      alert('A nota está vazia. Escreva algo antes de salvar.');
      return;
    }
    const title = prompt('Digite um título para a nota:');
    if (title && title.trim()) {
      // Evitar sobrescrever notas com título repetido
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

  // Função para excluir uma nota
  function excluirNota(title) {
    if (confirm(`Excluir a nota "${title}"?`)) {
      localStorage.removeItem('nota-' + title);
      carregarNotas();
    }
  }

  // Função para baixar PDF individual da nota
  function baixarPdf(title, content) {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    const pageWidth = doc.internal.pageSize.getWidth();
    const margin = 10;
    let y = 10;

    doc.setFontSize(16);
    doc.text(title, margin, y);
    y += 10;

    doc.setFontSize(12);
    const splitContent = doc.splitTextToSize(content, pageWidth - 2 * margin);
    doc.text(splitContent, margin, y);

    doc.save(`${title}.pdf`);
  }

  // Carrega as notas salvas no localStorage e cria a lista com botões individuais
  function carregarNotas() {
    const noteList = document.getElementById('noteList');
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

        // Título clicável que carrega o conteúdo no textarea
        const spanTitle = document.createElement('span');
        spanTitle.textContent = title;
        spanTitle.style.cursor = 'pointer';
        spanTitle.style.flexGrow = '1';
        spanTitle.addEventListener('click', () => {
          textareaNotas.value = content;
        });

        // Contêiner dos botões
        const divButtons = document.createElement('div');

        // Botão baixar PDF
        const btnBaixar = document.createElement('button');
        btnBaixar.textContent = 'Baixar PDF';
        btnBaixar.className = 'btn-pequeno';
        btnBaixar.style.marginRight = '8px';
        btnBaixar.onclick = () => baixarPdf(title, content);

        // Botão excluir
        const btnExcluir = document.createElement('button');
        btnExcluir.textContent = 'Excluir';
        btnExcluir.className = 'btn-excluir';
        btnExcluir.onclick = () => excluirNota(title);

        divButtons.appendChild(btnBaixar);
        divButtons.appendChild(btnExcluir);

        listItem.appendChild(spanTitle);
        listItem.appendChild(divButtons);

        noteList.appendChild(listItem);
      }
    }
  }

  // Função para criar linhas nas tabelas de tarefas/nao esquecer
  function criarLinha(lista) {
    const index = lista.rows.length + 1;
    const row = lista.insertRow();

    const cell1 = row.insertCell();
    const cell2 = row.insertCell();
    const cell3 = row.insertCell();
    const cell4 = row.insertCell();

    cell1.textContent = index;
    cell2.contentEditable = 'true';

    const inputData = document.createElement('input');
    inputData.type = 'date';
    cell3.appendChild(inputData);

    const btnExcluir = document.createElement('button');
    btnExcluir.textContent = 'Excluir';
    btnExcluir.className = 'btn-excluir';
    btnExcluir.onclick = () => {
      if (confirm('Deseja realmente excluir esta linha?')) {
        lista.deleteRow(row.rowIndex - 1);
        // Reajusta índices
        Array.from(lista.rows).forEach((r, i) => (r.cells[0].textContent = i + 1));
      }
    };

    cell4.appendChild(btnExcluir);
  }

  // Eventos dos botões
  document.getElementById('add-tarefa').addEventListener('click', () => criarLinha(listaTarefas));
  document.getElementById('add-nao-esquecer').addEventListener('click', () => criarLinha(listaNaoEsquecer));
  salvarNotaButton.addEventListener('click', salvarNota);

  // Inicialização
  carregarNotas();
});

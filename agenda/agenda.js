document.addEventListener('DOMContentLoaded', () => {
  const listaTarefas = document.getElementById('lista-tarefas');
  const listaNaoEsquecer = document.getElementById('lista-nao-esquecer');
  const salvarNotaButton = document.getElementById('btn-salvar-nota');
  const textareaNotas = document.querySelector('#notas textarea');

  // Função para salvar uma nota
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

  // Função para excluir uma nota
  function excluirNota(title) {
    if (confirm(`Excluir a nota "${title}"?`)) {
      localStorage.removeItem('nota-' + title);
      carregarNotas();
    }
  }

  // Função para gerar PDF com cabeçalho e rodapé
  function baixarPdf(title, content) {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    const pageWidth = doc.internal.pageSize.getWidth();
    const pageHeight = doc.internal.pageSize.getHeight();
    const margin = 10;
    let y = 10;

    // Cabeçalho fixo - FOAG + data
    const dataAtual = new Date().toLocaleString('pt-BR');
    doc.setFontSize(20);
    doc.setTextColor(40, 40, 120);
    doc.text('FOAG - Minhas Notas', pageWidth / 2, y, { align: 'center' });
    y += 8;

    doc.setFontSize(10);
    doc.setTextColor(100);
    doc.text(`Exportado em: ${dataAtual}`, pageWidth / 2, y, { align: 'center' });
    y += 6;

    // Linha separadora
    doc.setDrawColor(150);
    doc.line(margin, y, pageWidth - margin, y);
    y += 10;

    // Título da nota
    doc.setFontSize(16);
    doc.setTextColor(0);
    doc.text(title, margin, y);
    y += 10;

    // Conteúdo da nota (quebra automática de linha)
    doc.setFontSize(12);
    const splitContent = doc.splitTextToSize(content, pageWidth - 2 * margin);
    doc.text(splitContent, margin, y);

    // Rodapé (número de página)
    const totalPages = doc.internal.getNumberOfPages();
    for (let i = 1; i <= totalPages; i++) {
      doc.setPage(i);
      doc.setFontSize(10);
      doc.setTextColor(100);
      doc.text(`Página ${i} de ${totalPages}`, pageWidth / 2, pageHeight - 10, { align: 'center' });
    }

    // Salvar PDF
    doc.save(`${title}.pdf`);
  }

  // Carregar lista de notas
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
        btnBaixar.onclick = () => baixarPdf(title, content);

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

  // Criar linha nas tabelas de tarefas e não esquecer
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
        Array.from(lista.rows).forEach((r, i) => (r.cells[0].textContent = i + 1));
      }
    };

    cell4.appendChild(btnExcluir);
  }

  // Eventos
  document.getElementById('add-tarefa').addEventListener('click', () => criarLinha(listaTarefas));
  document.getElementById('add-nao-esquecer').addEventListener('click', () => criarLinha(listaNaoEsquecer));
  salvarNotaButton.addEventListener('click', salvarNota);

  carregarNotas();
});

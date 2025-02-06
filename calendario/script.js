// Função que expande o mês e mostra o calendário completo
function expandirMes(elemento, mes) {
    // Verifica se algum mês está expandido e o recolhe
    const expanded = document.querySelector('.mes.expanded');
    if (expanded && expanded !== elemento) {
      expanded.classList.remove('expanded');
      expanded.innerHTML = expanded.getAttribute('data-month');
    }
  
    // Alterna a classe 'expanded' para expandir/recolher o mês
    elemento.classList.toggle('expanded');
    
    // Se o mês foi expandido, substituímos seu conteúdo com o calendário
    if (elemento.classList.contains('expanded')) {
      elemento.setAttribute('data-month', elemento.innerHTML); // Armazena o nome do mês original
      elemento.innerHTML = criarCalendario(mes); // Cria o calendário do mês
    } else {
      elemento.innerHTML = elemento.getAttribute('data-month'); // Retorna ao nome do mês
    }
  }
  
  // Função para criar o calendário de um mês
  function criarCalendario(mes) {
    const diasDoMes = obterDiasDoMes(mes);
    let calendarioHTML = `
      <div class="calendario-expandido">
        <div class="header">Dom</div>
        <div class="header">Seg</div>
        <div class="header">Ter</div>
        <div class="header">Qua</div>
        <div class="header">Qui</div>
        <div class="header">Sex</div>
        <div class="header">Sáb</div>`;

    // Preenche os dias do mês
    diasDoMes.forEach(dia => {
      calendarioHTML += `<div class="day">${dia}</div>`;
    });
  
    calendarioHTML += `</div>`; // Fecha o contêiner do calendário
    return calendarioHTML;
  }
  
  // Função para obter o número de dias no mês
  function obterDiasDoMes(mes) {
    const meses = {
      Janeiro: 31,
      Fevereiro: 28, // Podemos melhorar isso para considerar anos bissextos
      Março: 31,
      Abril: 30,
      Maio: 31,
      Junho: 30,
      Julho: 31,
      Agosto: 31,
      Setembro: 30,
      Outubro: 31,
      Novembro: 30,
      Dezembro: 31
    };
  
    const dias = [];
    const diasNoMes = meses[mes];
  
    for (let i = 1; i <= diasNoMes; i++) {
      dias.push(i);
    }
  
    return dias;
  }

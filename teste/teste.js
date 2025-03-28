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
    
    // Se o mês foi expandido, substituímos seu conteúdo com o calendário completo
    if (elemento.classList.contains('expanded')) {
        elemento.setAttribute('data-month', elemento.innerHTML); // Armazena o nome do mês original
        elemento.innerHTML = criarCalendario(mes); // Cria o calendário expandido do mês
    } else {
        elemento.innerHTML = elemento.getAttribute('data-month'); // Retorna ao nome do mês
    }
}

// Função para preencher os dias do mês no formato não expandido
function preencherDiasDoMes(mes) {
    const diasDoMes = obterDiasDoMes(mes);
    const diasContainer = document.querySelector(`.mes[data-month="${mes}"] .dias`);
    
    // Limpa os dias anteriores
    diasContainer.innerHTML = '';
    
    // Preenche os dias do mês
    diasDoMes.forEach(dia => {
        const diaElement = document.createElement('div');
        diaElement.classList.add('dia');
        diaElement.textContent = dia || ''; // Adiciona um dia ou espaço vazio
        diasContainer.appendChild(diaElement);
    });
}

// Função para criar o calendário de um mês (com dias da semana) no modo expandido
function criarCalendario(mes) {
    const diasDoMes = obterDiasDoMes(mes);
    const diasDaSemana = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'];
    
    let calendarioHTML = `
      <div class="calendario-expandido">
        <div class="dias">`;

    // Adiciona os dias da semana como cabeçalho
    diasDaSemana.forEach(dia => {
        calendarioHTML += `<div class="header">${dia}</div>`;
    });

    // Preenche os dias do mês, respeitando o primeiro dia da semana
    diasDoMes.forEach(dia => {
        calendarioHTML += `<div class="dia">${dia}</div>`;
    });

    calendarioHTML += `</div></div>`; // Fecha o contêiner do calendário
    return calendarioHTML;
}

// Função para obter os dias no mês, considerando anos bissextos
function obterDiasDoMes(mes) {
    const anoAtual = new Date().getFullYear();
    const isBissexto = (anoAtual % 4 === 0 && anoAtual % 100 !== 0) || (anoAtual % 400 === 0);
  
    const meses = {
        Janeiro: 31,
        Fevereiro: isBissexto ? 29 : 28,
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
    const primeiroDia = new Date(anoAtual, Object.keys(meses).indexOf(mes), 1).getDay();
  
    // Adiciona espaços vazios antes do primeiro dia do mês
    for (let i = 0; i < primeiroDia; i++) {
        dias.push(''); // Espaços vazios para dias antes do primeiro do mês
    }
  
    // Preenche os dias do mês
    for (let i = 1; i <= meses[mes]; i++) {
        dias.push(i);
    }
  
    return dias;
}

// Inicializa o calendário preenchendo os dias
document.addEventListener('DOMContentLoaded', () => {
    const meses = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
    meses.forEach(mes => preencherDiasDoMes(mes)); // Preenche todos os meses
});

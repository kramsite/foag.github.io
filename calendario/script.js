function expandirMes(elemento) {
    // Verifica se algum mês está expandido e o recolhe
    const expanded = document.querySelector('.mes.expanded');
    if (expanded && expanded !== elemento) {
      expanded.classList.remove('expanded');
    }
  
    // Alterna a classe 'expanded' para expandir/recolher o mês
    elemento.classList.toggle('expanded');
  }
  
function salvarTeste() {
    const texto = document.getElementById('testeNotas').value;
    localStorage.setItem('testeNota', texto);
    alert('Nota salva!');
}

function carregarTeste() {
    const texto = localStorage.getItem('testeNota');
    document.getElementById('testeNotas').value = texto;
    alert('Nota carregada!');
}
document.getElementById("loginForm").addEventListener("submit", function(event) {
    event.preventDefault(); // Evita o envio padrão do formulário
    window.open("../calendario/calendario.html", "_blank"); // Abre a nova aba
  });
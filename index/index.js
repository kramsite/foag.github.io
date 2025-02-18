document.getElementById("loginForm").addEventListener("submit", function(event) {
    event.preventDefault(); // Evita o envio padrão do formulário
    window.open("../calendario/calendario.html", "_blank"); // Abre a nova aba
  });

  document.getElementById("loginForm").addEventListener("submit", function(event) {
    // Obtenha os valores dos campos
    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;

    // Verifique se os campos estão vazios
    if (email.trim() === "" || password.trim() === "") {
        event.preventDefault(); // Impede o envio do formulário
        alert("Por favor, preencha todos os campos.");
    }
});
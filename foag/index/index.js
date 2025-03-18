document.getElementById("loginForm").addEventListener("submit", function(event) {
    event.preventDefault(); // Evita o envio automático do formulário

    // Obtenha os valores dos campos de e-mail e senha
    const email = document.getElementById("email").value.trim();
    const password = document.getElementById("password").value.trim();

    // Verifique se os campos estão vazios
    if (email === "" || password === "") {
        alert("Por favor, preencha todos os campos.");
        return;
    }

    // Simulação de validação (substitua com sua lógica real, como uma API)
    if (email === "teste@exemplo.com" && password === "123456") {
        window.location.href = "../calendario/calendario.html"; // Redireciona para o calendário
    } else {
        alert("E-mail ou senha incorretos!"); // Exibe um alerta caso os dados sejam inválidos
    }
});

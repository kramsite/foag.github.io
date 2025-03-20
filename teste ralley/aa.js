document.getElementById("loginForm").addEventListener("submit", function(event) {
    event.preventDefault(); // Evita o envio automático do formulário

    // Obtenha os valores dos campos de e-mail e senha
    const email = document.getElementById("email").value.trim();
    const password = document.getElementById("password").value.trim();

    // Verifique se algum dos campos está vazio
    if (email === "" || password === "") {
        alert("Por favor, preencha todos os campos.");
        return;
    }

    // Desabilitar o botão enquanto o formulário é processado
    const submitButton = document.querySelector("button[type='submit']");
    submitButton.disabled = true;

    // Simulação de autenticação (substitua pela sua lógica real)
    setTimeout(() => {
        let loginSuccess = false;

        // Simulação de verificação de credenciais (substitua pela sua lógica real)
        if (email === "teste@exemplo.com" && password === "senha123") {
            loginSuccess = true;
        }

        if (loginSuccess) {
            // Login bem-sucedido, redireciona para a página desejada
            window.location.href = "../calendario/calendario.html"; // Ou "../inicio/inicio.html" se preferir
        } else {
            // Login falhou, exibe uma mensagem de erro
            alert("E-mail ou senha incorretos.");
        }

        submitButton.disabled = false; // Habilitar o botão novamente
    }, 1000); // Simula um delay de 1 segundo
});
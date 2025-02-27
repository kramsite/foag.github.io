document.getElementById("loginForm").addEventListener("submit", function(event) {
    // Obtenha os valores dos campos de e-mail e senha
    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;

    // Verifique se os campos estão vazios
    if (email.trim() === "" || password.trim() === "") {
        event.preventDefault(); // Impede o envio do formulário
        alert("Por favor, preencha todos os campos.");
    } else {
        // Caso os campos sejam preenchidos, o formulário pode ser enviado normalmente
        // Aqui você pode adicionar a lógica de redirecionamento ou processamento de login
    }
});

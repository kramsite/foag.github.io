document.getElementById('cadastro-form').addEventListener('submit', function(event) {
    event.preventDefault(); 

    let password = document.getElementById('password').value;
    let confirmPassword = document.getElementById('confirm_password').value;

    if (password.length < 6) {
        alert("A senha deve ter pelo menos 6 caracteres.");
        return;
    }

    if (password !== confirmPassword) {
        alert("As senhas não coincidem!");
        return;
    }

    alert("Cadastro realizado com sucesso!");
    window.location.href = "../login/Loginkram.html"; 
});
document.getElementById("contact-form").addEventListener("submit", function(event) { 
    let valido = true;

    let nome = document.getElementById("nome").value.trim();
    let email = document.getElementById("email").value.trim();
    let exp = document.getElementById("exp").value;
    let mensagem = document.getElementById("mensagem").value.trim();

    let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    function exibirErro(id, mensagem) {
        document.getElementById(id).textContent = mensagem;
        document.getElementById(id).style.color = "red";
    }

    if (nome === "") {
        exibirErro("erroNome", "Por favor, digite seu nome.");
        valido = false;
    } else {
        exibirErro("erroNome", "");
    }

    if (email === "" || !emailRegex.test(email)) {
        exibirErro("erroEmail", "Por favor, digite um email válido.");
        valido = false;
    } else {
        exibirErro("erroEmail", "");
    }

    if (exp === "") {
        exibirErro("erroExp", "Por favor, selecione um item da lista.");
        valido = false;
    } else {
        exibirErro("erroExp", "");
    }

    if (mensagem === "") {
        exibirErro("erroMensagem", "Preencha este campo.");
        valido = false;
    } else {
        exibirErro("erroMensagem", "");
    }

    if (!valido) {
        event.preventDefault();
    }
});
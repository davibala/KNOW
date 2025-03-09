function formatText(command) {
    document.execCommand(command, false, null);
    document.getElementById('resposta').focus(); // Mantém o foco no campo de resposta
}

// Adiciona eventos de clique aos botões de formatação
document.getElementById('btn-bold').addEventListener('click', () => formatText('bold'));
document.getElementById('btn-italic').addEventListener('click', () => formatText('italic'));
document.getElementById('btn-underline').addEventListener('click', () => formatText('underline'));

// Captura o conteúdo do div antes de enviar o formulário
document.querySelector('form').addEventListener('submit', function (e) {
    const respostaDiv = document.getElementById('resposta');
    const respostaInput = document.createElement('textarea');
    respostaInput.name = 'resposta';
    respostaInput.style.display = 'none';
    respostaInput.value = respostaDiv.innerHTML; // Captura o conteúdo formatado
    this.appendChild(respostaInput);
});

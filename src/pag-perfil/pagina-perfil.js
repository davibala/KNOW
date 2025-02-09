function menuDropdown(dropdownId) {
    // Seleciona o dropdown pelo ID único
    const dropdown = document.getElementById(dropdownId);

    // Alterna a visibilidade do dropdown
    if (dropdown.style.display === 'block') {
        dropdown.style.display = 'none';
    } else {
        dropdown.style.display = 'block';
    }
}

window.onclick = function (event) {
    if (!event.target.matches('.dropbtn')) {
        var dropdowns = document.getElementsByClassName("dropdown-conteudo");
        for (var i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
            }
        }
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const btnPerguntas = document.querySelector('.btn-minhas-perguntas');
    const btnRespostas = document.querySelector('.btn-minhas-respostas');
    const secaoPerguntas = document.getElementById('secao-perguntas');
    const secaoRespostas = document.getElementById('secao-respostas');
    const tituloSecao = document.getElementById('titulo-secao');

    btnPerguntas.classList.add('ativo'); // Adiciona a classe ativo ao botão de perguntas
    secaoRespostas.style.display = 'none'; // Oculta a seção de respostas


    // Exibir perguntas e ocultar respostas
    btnPerguntas.addEventListener('click', function () {
        secaoPerguntas.style.display = 'block';
        secaoRespostas.style.display = 'none';
        tituloSecao.textContent = 'Minhas perguntas';
        btnPerguntas.classList.add('ativo'); // Adiciona a classe ativo ao botão de perguntas
        btnRespostas.classList.remove('ativo'); // Remove a classe ativo do botão de respostas
    });

    // Exibir respostas e ocultar perguntas
    btnRespostas.addEventListener('click', function () {
        secaoPerguntas.style.display = 'none';
        secaoRespostas.style.display = 'block';
        tituloSecao.textContent = 'Minhas respostas';
        btnRespostas.classList.add('ativo'); // Adiciona a classe ativo ao botão de respostas
        btnPerguntas.classList.remove('ativo'); // Remove a classe ativo do botão de perguntas
    });
});

function msgErro(dropdownId) {
    // Seleciona o dropdown pelo ID único
    const dropdown = document.getElementById(dropdownId);

    // Alterna a visibilidade do dropdown
    if (dropdown.style.display === 'block') {
        dropdown.style.display = 'none';
    } else {
        dropdown.style.display = 'block';
    }
}
function menuDropdown(dropdownId) {
    // Fecha todos os dropdowns abertos
    const dropdowns = document.querySelectorAll('.dropdown-conteudo');
    dropdowns.forEach(dropdown => {
        if (dropdown.id !== dropdownId) {
            dropdown.style.display = 'none';
        }
    });

    // Abre ou fecha o dropdown clicado
    const dropdown = document.getElementById(dropdownId);
    if (dropdown.style.display === 'block') {
        dropdown.style.display = 'none';
    } else {
        dropdown.style.display = 'block';
    }
}

// Fecha o dropdown ao clicar fora dele
window.onclick = function (event) {
    if (!event.target.matches('.dropbtn')) {
        const dropdowns = document.querySelectorAll('.dropdown-conteudo');
        dropdowns.forEach(dropdown => {
            dropdown.style.display = 'none';
        });
    }
};

function mostrarMensagemErro(mensagem) {
    const mensagemErro = document.getElementById('mensagem-erro');
    mensagemErro.textContent = mensagem;
    mensagemErro.classList.add('mostrar');
    setTimeout(() => {
        mensagemErro.classList.remove('mostrar');
    }, 3000); // 3000ms = 3 segundos
}

document.addEventListener('DOMContentLoaded', function () {
    const btnPerguntas = document.querySelector('.btn-minhas-perguntas');
    const btnRespostas = document.querySelector('.btn-minhas-respostas');
    const btnEditarPerfil = document.querySelector('.btn-editar-perfil');
    const secaoEditarPerfil = document.getElementById('secao-editar-perfil');
    const secaoPerguntas = document.getElementById('secao-perguntas');
    const secaoRespostas = document.getElementById('secao-respostas');
    const tituloSecao = document.getElementById('titulo-secao');

    btnEditarPerfil.classList.add('ativo');
    tituloSecao.textContent = 'Meu perfil'; // Adiciona a classe ativo ao botão de perguntas
    secaoPerguntas.style.display = 'none'; // Oculta a seção de perguntas
    secaoRespostas.style.display = 'none'; // Oculta a seção de respostas

    btnEditarPerfil.addEventListener('click', function () {
        secaoEditarPerfil.style.display = 'block';
        secaoPerguntas.style.display = 'none';
        secaoRespostas.style.display = 'none';
        tituloSecao.textContent = 'Meu perfil';
        btnEditarPerfil.classList.add('ativo'); // Adiciona a classe ativo ao botão de perguntas
        btnPerguntas.classList.remove('ativo'); // Remove a classe ativo do botão de perguntas
        btnRespostas.classList.remove('ativo'); // Remove a classe ativo do botão de respostas
    });

    // Exibir perguntas e ocultar respostas
    btnPerguntas.addEventListener('click', function () {
        secaoPerguntas.style.display = 'block';
        secaoRespostas.style.display = 'none';
        secaoEditarPerfil.style.display = 'none';
        tituloSecao.textContent = 'Minhas perguntas';
        btnPerguntas.classList.add('ativo'); // Adiciona a classe ativo ao botão de perguntas
        btnRespostas.classList.remove('ativo'); // Remove a classe ativo do botão de respostas
        btnEditarPerfil.classList.remove('ativo'); // Remove a classe ativo do botão de editar perfil
    });

    // Exibir respostas e ocultar perguntas
    btnRespostas.addEventListener('click', function () {
        secaoRespostas.style.display = 'block';
        secaoPerguntas.style.display = 'none';
        secaoEditarPerfil.style.display = 'none';
        tituloSecao.textContent = 'Minhas respostas';
        btnRespostas.classList.add('ativo'); // Adiciona a classe ativo ao botão de respostas
        btnPerguntas.classList.remove('ativo'); // Remove a classe ativo do botão de perguntas
        btnEditarPerfil.classList.remove('ativo'); // Remove a classe ativo do botão de editar perfil
    });

    const disabledLinks = document.querySelectorAll('.disabled-link');
    disabledLinks.forEach(link => {
        link.addEventListener('click', function(event) {
            event.preventDefault(); // Impede o comportamento padrão do link
            event.stopPropagation(); // Impede a propagação do evento
        });
    });

    const inputFotoPerfil = document.getElementById('foto-perfil-input');
    const previewImagem = document.getElementById('preview-imagem');
    const fotoPadrao = previewImagem.getAttribute('data-foto-padrao'); // Caminho da imagem padrão

    inputFotoPerfil.addEventListener('change', function (event) {
        const arquivo = event.target.files[0]; // Pega o arquivo selecionado

        if (arquivo) {
            const leitor = new FileReader(); // Cria um FileReader para ler o arquivo

            leitor.onload = function (e) {
                // Atualiza o src da imagem de prévia
                previewImagem.src = e.target.result;
                previewImagem.style.display = 'block'; // Exibe a imagem
            };

            leitor.readAsDataURL(arquivo); // Lê o arquivo como uma URL de dados
        } else {
            // Se nenhum arquivo for selecionado, volta para a imagem padrão
            previewImagem.src = fotoPadrao;
        }
    });

    const mensagemErro = document.getElementById('mensagem-erro');
    const mensagem = mensagemErro.getAttribute('data-mensagem');

    if (mensagem) {
        mostrarMensagemErro(mensagem);
    }

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

document.getElementById('foto-perfil-input').addEventListener('change', function(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('foto-perfil').src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
});
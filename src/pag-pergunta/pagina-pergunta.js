// script.js
function menuDropdown() { // Função para abrir e fechar o dropdown
    document.getElementById("pergunta-dropdown").classList.toggle("show"); // Adicionar ou remover a classe 'show' do dropdown
}

// Fechar o dropdown se o usuário clicar fora dele
window.onclick = function(event) { // Adicionar um evento de clique na janela
    if (!event.target.matches('.dropbtn')) { // Se o usuário clicar fora do botão
        var dropdowns = document.getElementsByClassName("dropdown-conteudo"); // Encontrar todos os dropdowns
        for (var i = 0; i < dropdowns.length; i++) { // Para cada dropdown
            var openDropdown = dropdowns[i]; // Atribuir o dropdown atual
            if (openDropdown.classList.contains('show')) { // Se o dropdown estiver aberto
                openDropdown.classList.remove('show'); // Fechar o dropdown
            }
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const selectTags = document.getElementById('select-tags');
    const tagsSelecionadas = document.getElementById('tags-selecionadas');
    const tagsArray = []; // Array para armazenar as tags selecionadas

    // Adicionar tag ao array e exibir visualmente
    selectTags.addEventListener('change', function() {
        const tagId = this.value;
        const tagNome = this.options[this.selectedIndex].text;

        if (tagId && !tagsArray.includes(tagId)) {
            tagsArray.push(tagId);

            const tagElement = document.createElement('div');
            tagElement.className = 'tag';
            tagElement.innerHTML = `
                ${tagNome}
                <button type="button" onclick="removerTag('${tagId}')">X</button>
            `;
            tagsSelecionadas.appendChild(tagElement);
        }
    });

    // Função para remover tag
    window.removerTag = function(tagId) {
        const index = tagsArray.indexOf(tagId);
        if (index !== -1) {
            tagsArray.splice(index, 1);
            const tagElement = document.querySelector(`.tag button[onclick="removerTag('${tagId}')"]`).parentElement;
            tagsSelecionadas.removeChild(tagElement);
        }
    };

    // Adicionar as tags selecionadas ao formulário antes de enviar
    document.querySelector('form').addEventListener('submit', function(event) {
        // Cria um campo oculto para enviar as tags selecionadas
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'tags';
        hiddenInput.value = tagsArray.join(','); // Converte o array de tags em uma string separada por vírgulas
        this.appendChild(hiddenInput);
    });
});
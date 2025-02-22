

function menuDropdown() {
    document.getElementById("pergunta-dropdown").classList.toggle("show");
}

window.onclick = function (event) {
    if (!event.target.matches('.dropbtn')) {
        const dropdowns = document.querySelectorAll('.dropdown-conteudo');
        dropdowns.forEach(dropdown => {
            dropdown.style.display = 'none';
        });
    }
};

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
                <button type="button" onclick="removerTag('${tagId}')">×</button>
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
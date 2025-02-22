
document.addEventListener('DOMContentLoaded', function () {
    const selectTags = document.getElementById('select-tags');
    const tagsSelecionadas = document.getElementById('tags-selecionadas');
    const tagsSelecionadasInput = document.getElementById('tags-selecionadas-input');

    // Função para atualizar o campo oculto com as tags selecionadas
    function atualizarTagsInput() {
        const tags = Array.from(tagsSelecionadas.querySelectorAll('.tag-item')).map(tag => tag.getAttribute('data-tag-id'));
        tagsSelecionadasInput.value = tags.join(',');
    }

    // Adicionar tag selecionada
    selectTags.addEventListener('change', function () {
        const tagId = selectTags.value;
        const tagNome = selectTags.options[selectTags.selectedIndex].text;

        if (tagId && !tagsSelecionadas.querySelector(`[data-tag-id="${tagId}"]`)) {
            const tagItem = document.createElement('div');
            tagItem.className = 'tag-item';
            tagItem.setAttribute('data-tag-id', tagId);
            tagItem.innerHTML = `${tagNome} <span class="remover-tag">×</span>`;
            tagsSelecionadas.appendChild(tagItem);

            // Atualizar o campo oculto
            atualizarTagsInput();
        }

        // Resetar o select
        selectTags.value = '';
    });

    // Remover tag
    tagsSelecionadas.addEventListener('click', function (e) {
        if (e.target.classList.contains('remover-tag')) {
            e.target.parentElement.remove();
            atualizarTagsInput();
        }
    });
});
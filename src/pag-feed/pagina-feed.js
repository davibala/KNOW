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
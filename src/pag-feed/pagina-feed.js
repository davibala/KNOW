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
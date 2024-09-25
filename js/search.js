// search.js
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('searchInput');
    const localCards = document.querySelectorAll('.local-card');

    searchInput.addEventListener('input', function() {
        const searchTerm = searchInput.value.toLowerCase();

        localCards.forEach(localCard => {
            const localName = localCard.querySelector('h2').textContent.toLowerCase();
            if (localName.includes(searchTerm)) {
                localCard.style.display = 'block';  // Mostrar el local si coincide con la búsqueda
            } else {
                localCard.style.display = 'none';  // Ocultar el local si no coincide
            }
        });
    });
});

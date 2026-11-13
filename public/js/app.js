// Búsqueda en tiempo real (RF-01.2)
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('liveSearchInput');
    const resultsContainer = document.getElementById('liveSearchResults');

    if (searchInput && resultsContainer) {
        let debounceTimer;

        searchInput.addEventListener('input', (e) => {
            const term = e.target.value.trim();
            clearTimeout(debounceTimer);

            if (term.length < 2) {
                resultsContainer.classList.add('d-none');
                resultsContainer.innerHTML = '';
                return;
            }

            debounceTimer = setTimeout(() => {
                fetch(`index.php?c=home&a=searchAjax&term=${encodeURIComponent(term)}`)
                    .then(res => res.json())
                    .then(data => {
                        resultsContainer.innerHTML = '';
                        if (data.length === 0) {
                            resultsContainer.innerHTML = `
                                <div class="list-group-item text-muted small p-3 text-center">
                                    No se encontraron obras coincidentes con "${term}"
                                </div>
                            `;
                        } else {
                            data.forEach(book => {
                                const item = document.createElement('a');
                                item.href = `index.php?c=book&a=show&id=${book.id}`;
                                item.className = 'list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2';
                                item.innerHTML = `
                                    <div>
                                        <div class="fw-bold small text-dark">${book.titulo}</div>
                                        <small class="text-muted">${book.autor} • <span class="badge bg-light text-dark border">${book.formato}</span></small>
                                    </div>
                                    <span class="badge bg-umes">Q${parseFloat(book.precio_base).toFixed(2)}</span>
                                `;
                                resultsContainer.appendChild(item);
                            });
                        }
                        resultsContainer.classList.remove('d-none');
                    })
                    .catch(err => {
                        console.error('Error en búsqueda asíncrona:', err);
                    });
            }, 300);
        });

        // Ocultar resultados al hacer clic fuera
        document.addEventListener('click', (e) => {
            if (!searchInput.contains(e.target) && !resultsContainer.contains(e.target)) {
                resultsContainer.classList.add('d-none');
            }
        });
    }
});

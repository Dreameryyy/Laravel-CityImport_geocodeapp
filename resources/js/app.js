import './bootstrap';

document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('citySearch');
    const searchResults = document.getElementById('searchResults');

    searchInput.addEventListener('input', function () {
        const query = this.value;

        if (query.length > 1) {
            fetch(`/search?query=${query}`)
                .then(response => response.json())
                .then(data => {
                    searchResults.innerHTML = '';

                    data.forEach(city => {
                        const cityLink = document.createElement('a');
                        cityLink.href = `/city/${city.id}`;
                        cityLink.classList.add('list-group-item', 'list-group-item-action');
                        cityLink.textContent = city.name;

                        searchResults.appendChild(cityLink);
                    });
                });
        } else {
            searchResults.innerHTML = '';
        }
    });
});


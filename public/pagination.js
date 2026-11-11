function renderPagination(result) {

    const pagination = document.getElementById('pagination');

    const currentPage = result.current_page;
    const lastPage = result.last_page;

    pagination.innerHTML = '';

    // Previous button
    const prevButton = document.createElement('button');
    prevButton.textContent = 'Previous';
    prevButton.className = 'btn btn-secondary btn-sm me-2';
    prevButton.disabled = currentPage === 1;

    prevButton.addEventListener('click', () => {
        loadProducts(currentFilters, currentPage - 1);
    });

    pagination.appendChild(prevButton);

    // Page numbers
    for (let i = 1; i <= lastPage; i++) {

        const button = document.createElement('button');

        button.textContent = i;
        button.className = i === currentPage
            ? 'btn btn-primary btn-sm me-1'
            : 'btn btn-outline-primary btn-sm me-1';

        button.addEventListener('click', () => {
            loadProducts(currentFilters, i);
        });

        pagination.appendChild(button);
    }

    // Next button
    const nextButton = document.createElement('button');
    nextButton.textContent = 'Next';
    nextButton.className = 'btn btn-secondary btn-sm ms-1';
    nextButton.disabled = currentPage === lastPage;

    nextButton.addEventListener('click', () => {
        loadProducts(currentFilters, currentPage + 1);
    });

    pagination.appendChild(nextButton);
}
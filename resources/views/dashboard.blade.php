


<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light" style="display: none;">

    <div class="container py-5">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Product Management</h1>

            <button id="logoutButton" class="btn btn-danger">
                Logout
            </button>

        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between">
                <h5 class="mb-0">Product List</h5>
                <a href="{{ route('addNewProduct') }}" class="btn btn-primary">
                    Add New Product
                </a>


            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <div class="row g-3 align-items-end">

                        <!-- Category Filter -->
                        <div class="col-md-4">
                            <label for="categoryId" class="form-label">
                                Category
                            </label>
                            <x-category-dropdown />
                        </div>

                        <!-- Status Filter -->
                        <div class="col-md-4">
                            <label for="statusFilter" class="form-label">
                                Status
                            </label>
                            <select id="statusFilter" class="form-select">
                                <option value="">All Status</option>
                                <option value="1">Enabled</option>
                                <option value="0">Disabled</option>
                            </select>
                        </div>

                        <!-- Buttons -->
                        <div class="col-md-4">
                            <button class="btn btn-primary" onclick="applyFilters(currentPage)">
                                <i class="bi bi-funnel"></i> Filter
                            </button>

                            <button class="btn btn-outline-secondary" onclick="resetFilters()">
                                Reset
                            </button>
                        </div>

                    </div>
                </div>
            </div>
            <div class="mx-3 ">
                <button type="button" id="exportBtn" class="btn btn-success">
                    Export Excel
                </button>
            </div>
            <div class="mx-3 ">
                <button id="bulkDeleteBtn" class="btn btn-danger btn-sm mb-3  col-12"
                    style="display: none;" onclick="bulkDelete()">
                    <i class="bi bi-trash"></i> Delete Selected
                </button>
            </div>

            <div class="card-body">

                <div id="errorMessage" class="alert alert-danger d-none"></div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">

                        <thead class="table-dark">
                            <tr>
                                <th>Action</th>
                                <th>Product Name</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody id="productTable">
                            <tr>
                                <td colspan="6" class="text-center">
                                    Loading products...
                                </td>
                            </tr>
                        </tbody>

                    </table>
                    <div id="pagination" class="mt-3" value =1></div>
                </div>

            </div>
        </div>


    </div>

    <script src="{{ asset('auth.js') }}"></script>
    
    <script>
        let currentPage = 1;
        async function loadProducts(filters = {}) {
            const token = localStorage.getItem('token');
            const table = document.getElementById('productTable');
            const errorMessage = document.getElementById('errorMessage');

            try {

                const params = new URLSearchParams();
                if (filters.category_id) {
                    params.append('category_id', filters.category_id);
                }

                if (filters.enabled !== undefined && filters.enabled !== '') {
                    params.append('enabled', filters.enabled);
                }
                params.append('page', filters.page || 1);
                const response = await fetch(`${API_URL}/products?${params.toString()}`, {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`
                    }
                });

                const result = await response.json();

                if (!response.ok) {
                    throw new Error(result.message || 'Failed to load products.');
                }

                table.replaceChildren();

                if (result.data.length === 0) {
                    const row = document.createElement('tr');
                    const cell = document.createElement('td');

                    cell.colSpan = 6;
                    cell.className = 'text-center';
                    cell.textContent = 'No products found.';

                    row.appendChild(cell);
                    table.appendChild(row);
                    return;
                }
                result.data.forEach(product => {
                    
                    table.innerHTML += `
                        <tr>
                            <td class="text-center align-middle ">
                                <input type="checkbox" class="product-checkbox mx-1 "  style="cursor:pointer"
                                    value="${product.id}">
                                <button class="btn btn-success btn-sm mx-1 edit-btn"
                                    onclick="window.location.href='/products/ProductDetails?id=${product.id}'">
                                    Edit
                                </button>
                                <button class="btn btn-danger btn-sm mx-1 edit-btn"
                                    onclick="deleteProduct(${product.id})">
                                    delete
                                </button>
                            </td>

                            <td>${product.name}</td>
                            <td>${product.category?.name ?? 'N/A'}</td>
                            <td>${Number(product.price).toFixed(2)}</td>
                            <td>${product.stock}</td>

                            <td>
                                <span class="badge ${product.enabled ? 'bg-success' : 'bg-secondary'}">
                                    ${product.enabled ? 'Enabled' : 'Disabled'}
                                </span>
                            </td>
                        </tr>
                    `;
                });
                currentPage = result.current_page
                renderPagination(result);
            } catch (error) {
                errorMessage.textContent = error.message;
                errorMessage.classList.remove('d-none');
                table.replaceChildren();
                console.error(error);
            }
        }

        function applyFilters(page) {
            loadProducts({
                category_id: document.getElementById('categoryId').value,
                enabled: document.getElementById('statusFilter').value,
                page: page
            });
        }

        function resetFilters() {
            document.getElementById('categoryId').value = '';
            document.getElementById('statusFilter').value = '';
            loadProducts();
        }

        loadProducts();
        document.getElementById('logoutButton').addEventListener('click', () => {
            localStorage.removeItem('token');
            window.location.href = '/login';
        });

        document.getElementById('logoutButton').addEventListener('click', () => {
            localStorage.removeItem('token');
            window.location.href = '/login';
        });

        document.addEventListener('change', function (event) {
            if (event.target.classList.contains('product-checkbox')) {
                const selected = document.querySelectorAll(
                    '.product-checkbox:checked'
                );

                document.getElementById('bulkDeleteBtn').style.display =
                    selected.length > 0 ? 'inline-block' : 'none';
            }
        });
    
    async function bulkDelete() {

        const selectedIds = Array.from(
            document.querySelectorAll('.product-checkbox:checked')
        ).map(checkbox => Number(checkbox.value));

        if (selectedIds.length === 0) {
            alert('Please select products first.');
            return;
        }

        if (!confirm(`Delete ${selectedIds.length} selected products?`)) {
            return;
        }

        const token = localStorage.getItem('token');

        try {
            const response = await fetch(`${API_URL}/products/bulk-delete`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${token}`
                },
                body: JSON.stringify({
                    ids: selectedIds
                })
            });

            const result = await response.json();

            if (!response.ok) {
                throw new Error(result.message || 'Bulk delete failed.');
            }
            else{
                document.getElementById('bulkDeleteBtn').style.display = 'none';
                applyFilters(currentPage);
            }
            alert(result.message);

        } catch (error) {
            console.error(error);
            alert(error.message);
        }
    }
    async function deleteProduct(id) {
        if (!confirm('Are you sure you want to delete this product?')) {
            return;
        }

        const token = localStorage.getItem('token');

        try {
            const response = await fetch(`${API_URL}/products`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${token}`
                },
                body: JSON.stringify({
                    id: id
                })
            });

            const result = await response.json();

            if (!response.ok) {
                throw new Error(result.message || 'Failed to delete product.');
            }else{
                applyFilters(currentPage);
            }

            alert(result.message);
            // Reload products using the current filters

        } catch (error) {
            console.error(error);
            alert(error.message);
        }
    }
    
    function renderPagination(result) {
        console.log(result);
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
            applyFilters(currentPage-1);
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
                applyFilters(i);
            });
            pagination.appendChild(button);
        }

        // Next button
        const nextButton = document.createElement('button');
        nextButton.textContent = 'Next';
        nextButton.className = 'btn btn-secondary btn-sm ms-1';
        nextButton.disabled = currentPage === lastPage;

        nextButton.addEventListener('click', () => {
            applyFilters(currentPage + 1);
        });

        pagination.appendChild(nextButton);
    }
    // export exel
    document.getElementById('exportBtn').addEventListener('click', async function () {

    const token = localStorage.getItem('token');

    try {
        const response = await fetch(`${API_URL}/products/export`, {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            }
        });

        if (!response.ok) {
            throw new Error('Excel export failed.');
        }

        // Get Excel file
        const blob = await response.blob();

        // Create download URL
        const url = window.URL.createObjectURL(blob);

        // Create temporary download link
        const link = document.createElement('a');
        link.href = url;
        link.download = 'products.xlsx';

        document.body.appendChild(link);
        link.click();

        // Clean up
        link.remove();
        window.URL.revokeObjectURL(url);

    } catch (error) {
        console.error(error);
        alert(error.message);
    }
});
    </script>
    
</body>
</html>






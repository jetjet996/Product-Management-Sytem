
<!DOCTYPE html>
<html>
<head>
    <title>Add New Product</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container py-5">

        <div class="card shadow-sm">

            <div class="card-header bg-white">
                <h3 class="mb-0">Add New Product</h3>
            </div>

            <div class="card-body">

                <form id="productForm">

                    <div class="mb-3">
                        <label class="form-label">Product Name</label>
                        <input type="text" id="productName" class="form-control" required>
                    </div>

                   <div class="mb-3">
                        <label class="form-label">Category ID</label>
                        <!-- <input type="number" id="categoryId" class="form-control" min="1" required>-->
                        <x-category-dropdown />
                    </div>
                    

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea id="description" class="form-control"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Price</label>
                        <input type="number" id="price" class="form-control" min="0" step="0.01" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Stock</label>
                        <input type="number" id="stock" class="form-control" min="0" required>
                    </div>

                    <div class="form-check mb-3">
                        <input type="checkbox" id="enabled" class="form-check-input" checked>
                        <label class="form-check-label" for="enabled">
                            Enabled
                        </label>
                    </div>

                    
                    <div class="d-flex justify-content-between">

                        <a href="/dashboard" class="btn btn-secondary">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            Save Product
                        </button>

                    </div>
                </form>
            </div>
        </div>
    </div>
<script src="{{ asset('auth.js') }}"></script>

<script>

    document.getElementById('productForm').addEventListener('submit', async (event) => {
        event.preventDefault();

        const token = localStorage.getItem('token');

        const product = {
            name: document.getElementById('productName').value,
            category_id: Number(document.getElementById('categoryId').value),
            description: document.getElementById('description').value,
            price: document.getElementById('price').value,
            stock: Number(document.getElementById('stock').value),
            enabled: document.getElementById('enabled').checked
        };

        try {
            const response = await fetch(`${API_URL}/products`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${token}`
                },
                body: JSON.stringify(product)
            });

            const data = await response.json();

            if (!response.ok) {
                console.log(data);
                alert(data.message || 'Failed to create product.');
                return;
            }

            alert('Product created successfully!');
            document.getElementById('productForm').reset();
            //window.location.href = '/dashboard';

        } catch (error) {
            console.error(error);
            alert('Unable to connect to server.');
        }
    });

</script>
</body>
</html>

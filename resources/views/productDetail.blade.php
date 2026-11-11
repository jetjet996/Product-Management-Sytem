
<!DOCTYPE html>
<html>
<head>
    <title>Product Details</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container py-5">

        <div class="card shadow-sm">

            <div class="card-header bg-white">
                <h3 class="mb-0">Product Details</h3>
            </div>

            <div class="card-body">

                <form id="productForm">
                        <input type="hidden" id="id" class="form-control" value ="">
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
                        <button type="button" class="btn btn-danger" onclick='deleteProduct()'>
                            Delete Product
                        </button>
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
    document.addEventListener('DOMContentLoaded', async function () {

        await window.categoriesLoaded;

        await loadProduct();

    });
    document.getElementById('productForm').addEventListener('submit', async (event) => {
        event.preventDefault();

        const token = localStorage.getItem('token');
        const productId = document.getElementById('id').value;
        console.log(productId);
        const product = {
            id: document.getElementById('id').value,
            name: document.getElementById('productName').value,
            category_id: Number(document.getElementById('categoryId').value),
            description: document.getElementById('description').value,
            price: document.getElementById('price').value,
            stock: Number(document.getElementById('stock').value),
            enabled: document.getElementById('enabled').checked
        };

        try {
            const response = await fetch(`${API_URL}/products/${productId}`, {
                method: 'PUT',
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
            }else{
                alert('Product updated successfully!');
            }
            
        } catch (error) {
            console.error(error);
            alert('Unable to connect to server.');
        }
    });

    async function loadProduct() {
        const token = localStorage.getItem('token');
        const productId = new URLSearchParams(window.location.search).get('id');
        try {
            const response = await fetch(`${API_URL}/products/${productId}`, {
                headers: {
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${token}`
                }
            });

            const result = await response.json();

            if (!response.ok) {
                throw new Error(result.message || 'Failed to load product.');
            }else{
                document.getElementById('id').value = result.id;
                document.getElementById('productName').value = result.name;
                document.getElementById('categoryId').value = result.category_id;
                document.getElementById('description').value = result.description ?? '';
                document.getElementById('price').value = result.price;
                document.getElementById('stock').value = result.stock;
                document.getElementById('enabled').checked = Boolean(result.enabled);
            }

            console.log(result);

        } catch (error) {
            console.error(error);
            alert(error.message);
        }
        }

        async function deleteProduct() {
        if (!confirm('Are you sure you want to delete this product?')) {
            return;
        }
        const id = document.getElementById('id').value; 
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
                alert(result.message);
                window.location.href = '/dashboard';
            }

        } catch (error) {
            console.error(error);
            alert(error.message);
        }
    }  


</script>
</body>
</html>

@props(['selected' => ''])

    <select id="categoryId"  name="category_id" class="form-select" data-selected="{{ $selected }}" required>
        <option value="">Loading categories...</option>
    </select>

@once
<script>
//document.addEventListener('DOMContentLoaded', async function () {
window.categoriesLoaded = (async function () {
    const dropdown = document.getElementById('categoryId');
    const token = localStorage.getItem('token');

    try {
        const response = await fetch('/api/categories', {
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`
            }
        });

        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.message || 'Failed to load categories.');
        }

        dropdown.innerHTML = '<option value="">Select a category</option>';

        result.data.forEach(category => {
            const option = document.createElement('option');

            option.value = category.id;
            option.textContent = category.name;

            if (String(category.id) === dropdown.dataset.selected) {
                option.selected = true;
            }

            dropdown.appendChild(option);
        });
    } catch (error) {
        console.error(error);
        dropdown.innerHTML = '<option value="">Failed to load categories</option>';
    }
})();
//});
</script>
@endonce
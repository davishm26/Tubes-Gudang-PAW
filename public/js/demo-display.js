/**
 * Demo Mode - Data Display Script
 * Loads and displays data from localStorage on index pages
 */

(function() {
    'use strict';

    const isDemoMode = sessionStorage.getItem('demo_mode') === 'true';

    if (!isDemoMode) {
        return;
    }

    console.log('📊 Demo Display Mode Active');

    /**
     * Get data from localStorage
     */
    function getDemoData(entity) {
        const data = localStorage.getItem(`demo_${entity}`);
        return data ? JSON.parse(data) : [];
    }

    /**
     * Render table for products
     */
    function renderProductsTable() {
        const tbody = document.querySelector('table tbody');
        if (!tbody || !window.location.pathname.includes('/products')) return;

        const products = getDemoData('products');
        const categories = getDemoData('categories');
        const suppliers = getDemoData('suppliers');

        if (products.length === 0) {
            tbody.innerHTML = '<tr><td colspan="8" class="px-6 py-4 text-center text-gray-500">Belum ada data produk</td></tr>';
            return;
        }

        tbody.innerHTML = products.map((product, index) => {
            const category = categories.find(c => c.id == product.category_id);
            const supplier = suppliers.find(s => s.id == product.supplier_id);
            const stockClass = product.stock <= product.min_stock ? 'text-red-600 font-bold' : 'text-green-600';

            return `
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-6 py-4">${index + 1}</td>
                    <td class="px-6 py-4 font-medium">${product.name}</td>
                    <td class="px-6 py-4">${product.sku || '-'}</td>
                    <td class="px-6 py-4">${category ? category.name : '-'}</td>
                    <td class="px-6 py-4">${supplier ? supplier.name : '-'}</td>
                    <td class="px-6 py-4 ${stockClass}">${product.stock || 0}</td>
                    <td class="px-6 py-4">Rp ${parseInt(product.price || 0).toLocaleString('id-ID')}</td>
                    <td class="px-6 py-4">
                        <div class="flex space-x-2">
                            <a href="/products/${product.id}/edit" class="text-blue-600 hover:text-blue-800">Edit</a>
                            <form method="POST" action="/products/${product.id}" style="display:inline">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="text-red-600 hover:text-red-800">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            `;
        }).join('');
    }

    /**
     * Render table for categories
     */
    function renderCategoriesTable() {
        const tbody = document.querySelector('table tbody');
        if (!tbody || !window.location.pathname.includes('/categories')) return;

        const categories = getDemoData('categories');

        if (categories.length === 0) {
            tbody.innerHTML = '<tr><td colspan="4" class="px-6 py-4 text-center text-gray-500">Belum ada data kategori</td></tr>';
            return;
        }

        tbody.innerHTML = categories.map((category, index) => `
            <tr class="border-b hover:bg-gray-50">
                <td class="px-6 py-4">${index + 1}</td>
                <td class="px-6 py-4 font-medium">${category.name}</td>
                <td class="px-6 py-4">${category.description || '-'}</td>
                <td class="px-6 py-4">
                    <div class="flex space-x-2">
                        <a href="/categories/${category.id}/edit" class="text-blue-600 hover:text-blue-800">Edit</a>
                        <form method="POST" action="/categories/${category.id}" style="display:inline">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="text-red-600 hover:text-red-800">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
        `).join('');
    }

    /**
     * Render table for suppliers
     */
    function renderSuppliersTable() {
        const tbody = document.querySelector('table tbody');
        if (!tbody || !window.location.pathname.includes('/suppliers')) return;

        const suppliers = getDemoData('suppliers');

        if (suppliers.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada data pemasok</td></tr>';
            return;
        }

        tbody.innerHTML = suppliers.map((supplier, index) => `
            <tr class="border-b hover:bg-gray-50">
                <td class="px-6 py-4">${index + 1}</td>
                <td class="px-6 py-4 font-medium">${supplier.name}</td>
                <td class="px-6 py-4">${supplier.contact || '-'}</td>
                <td class="px-6 py-4">${supplier.address || '-'}</td>
                <td class="px-6 py-4">
                    <div class="flex space-x-2">
                        <a href="/suppliers/${supplier.id}/edit" class="text-blue-600 hover:text-blue-800">Edit</a>
                        <form method="POST" action="/suppliers/${supplier.id}" style="display:inline">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="text-red-600 hover:text-red-800">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
        `).join('');
    }

    /**
     * Render inventory in table
     */
    function renderInventoryInTable() {
        const tbody = document.querySelector('table tbody');
        if (!tbody || !window.location.pathname.includes('/inventory-in')) return;

        const inventoryIn = getDemoData('inventory_in');
        const products = getDemoData('products');
        const suppliers = getDemoData('suppliers');

        if (inventoryIn.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">Belum ada transaksi stok masuk</td></tr>';
            return;
        }

        tbody.innerHTML = inventoryIn.map((item, index) => {
            const product = products.find(p => p.id == item.product_id);
            const supplier = suppliers.find(s => s.id == item.supplier_id);

            return `
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-6 py-4">${index + 1}</td>
                    <td class="px-6 py-4">${item.date || new Date(item.created_at).toLocaleDateString('id-ID')}</td>
                    <td class="px-6 py-4 font-medium">${product ? product.name : '-'}</td>
                    <td class="px-6 py-4">${supplier ? supplier.name : '-'}</td>
                    <td class="px-6 py-4 text-green-600 font-bold">${item.quantity}</td>
                    <td class="px-6 py-4">${item.description || '-'}</td>
                </tr>
            `;
        }).join('');
    }

    /**
     * Render inventory out table
     */
    function renderInventoryOutTable() {
        const tbody = document.querySelector('table tbody');
        if (!tbody || !window.location.pathname.includes('/inventory-out')) return;

        const inventoryOut = getDemoData('inventory_out');
        const products = getDemoData('products');

        if (inventoryOut.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada transaksi stok keluar</td></tr>';
            return;
        }

        tbody.innerHTML = inventoryOut.map((item, index) => {
            const product = products.find(p => p.id == item.product_id);

            return `
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-6 py-4">${index + 1}</td>
                    <td class="px-6 py-4">${item.date || new Date(item.created_at).toLocaleDateString('id-ID')}</td>
                    <td class="px-6 py-4 font-medium">${product ? product.name : '-'}</td>
                    <td class="px-6 py-4 text-red-600 font-bold">${item.quantity}</td>
                    <td class="px-6 py-4">${item.description || '-'}</td>
                </tr>
            `;
        }).join('');
    }

    /**
     * Render users table
     */
    function renderUsersTable() {
        const tbody = document.querySelector('table tbody');
        if (!tbody || !window.location.pathname.includes('/users')) return;

        const users = getDemoData('users');

        if (users.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada data user</td></tr>';
            return;
        }

        tbody.innerHTML = users.map((user, index) => `
            <tr class="border-b hover:bg-gray-50">
                <td class="px-6 py-4">${index + 1}</td>
                <td class="px-6 py-4 font-medium">${user.name}</td>
                <td class="px-6 py-4">${user.email}</td>
                <td class="px-6 py-4">
                    <span class="px-2 py-1 rounded text-xs ${user.role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800'}">
                        ${user.role === 'admin' ? 'Admin' : 'Staf'}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <div class="flex space-x-2">
                        <a href="/users/${user.id}/edit" class="text-blue-600 hover:text-blue-800">Edit</a>
                        <form method="POST" action="/users/${user.id}" style="display:inline">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="text-red-600 hover:text-red-800">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
        `).join('');
    }

    /**
     * Populate dashboard stats
     */
    function populateDashboardStats() {
        if (!window.location.pathname.includes('/dashboard')) return;

        const products = getDemoData('products');
        const categories = getDemoData('categories');
        const suppliers = getDemoData('suppliers');
        const inventoryIn = getDemoData('inventory_in');
        const inventoryOut = getDemoData('inventory_out');

        // Update stat cards if they exist
        const statCards = document.querySelectorAll('.bg-white.shadow');

        if (statCards.length >= 3) {
            // Total Products
            const totalProducts = products.length;
            const productStat = statCards[0].querySelector('.text-3xl');
            if (productStat) productStat.textContent = totalProducts;

            // Low Stock Products
            const lowStock = products.filter(p => p.stock <= p.min_stock).length;
            const lowStockStat = statCards[1].querySelector('.text-3xl');
            if (lowStockStat) lowStockStat.textContent = lowStock;

            // Total Categories
            const totalCategories = categories.length;
            const categoryStat = statCards[2].querySelector('.text-3xl');
            if (categoryStat) categoryStat.textContent = totalCategories;
        }
    }

    /**
     * Populate select options in forms
     */
    function populateFormSelects() {
        // Populate product select
        const productSelects = document.querySelectorAll('select[name="product_id"]');
        if (productSelects.length > 0) {
            const products = getDemoData('products');
            productSelects.forEach(select => {
                select.innerHTML = '<option value="">-- Pilih Produk --</option>' +
                    products.map(p => `<option value="${p.id}">${p.name} (Stok: ${p.stock})</option>`).join('');
            });
        }

        // Populate category select
        const categorySelects = document.querySelectorAll('select[name="category_id"]');
        if (categorySelects.length > 0) {
            const categories = getDemoData('categories');
            categorySelects.forEach(select => {
                select.innerHTML = '<option value="">-- Pilih Kategori --</option>' +
                    categories.map(c => `<option value="${c.id}">${c.name}</option>`).join('');
            });
        }

        // Populate supplier select
        const supplierSelects = document.querySelectorAll('select[name="supplier_id"]');
        if (supplierSelects.length > 0) {
            const suppliers = getDemoData('suppliers');
            supplierSelects.forEach(select => {
                select.innerHTML = '<option value="">-- Pilih Pemasok --</option>' +
                    suppliers.map(s => `<option value="${s.id}">${s.name}</option>`).join('');
            });
        }
    }

    // =====================
    // Initialize on page load
    // =====================

    function init() {
        renderProductsTable();
        renderCategoriesTable();
        renderSuppliersTable();
        renderInventoryInTable();
        renderInventoryOutTable();
        renderUsersTable();
        populateDashboardStats();
        populateFormSelects();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    console.log('✅ Demo Display Script Loaded');

})();

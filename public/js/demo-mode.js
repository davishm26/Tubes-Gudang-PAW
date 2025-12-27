/**
 * Demo Mode JavaScript
 * Handles localStorage operations and form interceptions for demo mode
 */

(function() {
    'use strict';

    // Check if demo mode is active
    const isDemoMode = sessionStorage.getItem('demo_mode') === 'true';

    if (!isDemoMode) {
        return; // Exit if not in demo mode
    }

    console.log('🎭 Demo Mode Active - Role:', sessionStorage.getItem('demo_role'));

    // =====================
    // localStorage Helper Functions
    // =====================

    /**
     * Get data from localStorage for specific entity
     */
    function getDemoData(entity) {
        const data = localStorage.getItem(`demo_${entity}`);
        return data ? JSON.parse(data) : [];
    }

    /**
     * Save data to localStorage
     */
    function saveDemoData(entity, data) {
        localStorage.setItem(`demo_${entity}`, JSON.stringify(data));
    }

    /**
     * Get next ID for entity
     */
    function getNextId(entity) {
        const counter = parseInt(localStorage.getItem(`demo_${entity}_counter`) || '1');
        localStorage.setItem(`demo_${entity}_counter`, (counter + 1).toString());
        return counter;
    }

    /**
     * Add new item to entity
     */
    function addDemoItem(entity, item) {
        const data = getDemoData(entity);
        item.id = getNextId(entity);
        item.created_at = new Date().toISOString();
        item.updated_at = new Date().toISOString();
        data.push(item);
        saveDemoData(entity, data);
        return item;
    }

    /**
     * Update item in entity
     */
    function updateDemoItem(entity, id, updates) {
        const data = getDemoData(entity);
        const index = data.findIndex(item => item.id == id);
        if (index !== -1) {
            data[index] = { ...data[index], ...updates, updated_at: new Date().toISOString() };
            saveDemoData(entity, data);
            return data[index];
        }
        return null;
    }

    /**
     * Delete item from entity
     */
    function deleteDemoItem(entity, id) {
        let data = getDemoData(entity);
        data = data.filter(item => item.id != id);
        saveDemoData(entity, data);
        return true;
    }

    // =====================
    // Form Interception
    // =====================

    /**
     * Intercept form submissions
     */
    function interceptForms() {
        document.addEventListener('submit', function(e) {
            const form = e.target;

            // Skip if not a demo mode form
            if (!form.closest('main')) return;

            // Check if it's a delete form
            const isDeleteForm = form.querySelector('input[name="_method"][value="DELETE"]');

            if (isDeleteForm) {
                e.preventDefault();
                handleDelete(form);
                return;
            }

            // Check if it's a create/update form
            const isPutMethod = form.querySelector('input[name="_method"][value="PUT"]');
            const isPostMethod = form.method.toLowerCase() === 'post' && !isPutMethod;

            if (isPostMethod || isPutMethod) {
                e.preventDefault();
                handleFormSubmit(form, isPutMethod);
            }
        }, true);
    }

    /**
     * Handle form submission (create/update)
     */
    function handleFormSubmit(form, isUpdate) {
        const formData = new FormData(form);
        const data = {};

        // Convert FormData to object
        for (let [key, value] of formData.entries()) {
            if (key !== '_token' && key !== '_method') {
                data[key] = value;
            }
        }

        // Determine entity from URL
        const url = form.action;
        let entity = null;
        let id = null;

        if (url.includes('/products')) {
            entity = 'products';
            if (isUpdate) {
                const matches = url.match(/\/products\/(\d+)/);
                id = matches ? matches[1] : null;
            }
        } else if (url.includes('/categories')) {
            entity = 'categories';
            if (isUpdate) {
                const matches = url.match(/\/categories\/(\d+)/);
                id = matches ? matches[1] : null;
            }
        } else if (url.includes('/suppliers')) {
            entity = 'suppliers';
            if (isUpdate) {
                const matches = url.match(/\/suppliers\/(\d+)/);
                id = matches ? matches[1] : null;
            }
        } else if (url.includes('/inventory-in')) {
            entity = 'inventory_in';
        } else if (url.includes('/inventory-out')) {
            entity = 'inventory_out';
        } else if (url.includes('/users')) {
            entity = 'users';
            if (isUpdate) {
                const matches = url.match(/\/users\/(\d+)/);
                id = matches ? matches[1] : null;
            }
        }

        if (!entity) {
            console.warn('Unknown entity for form:', url);
            return;
        }

        // Handle create or update
        if (isUpdate && id) {
            updateDemoItem(entity, id, data);
            showSuccessMessage('Data berhasil diperbarui!');
        } else {
            // For inventory, update product stock
            if (entity === 'inventory_in') {
                const products = getDemoData('products');
                const product = products.find(p => p.id == data.product_id);
                if (product) {
                    product.stock = parseInt(product.stock || 0) + parseInt(data.quantity || 0);
                    saveDemoData('products', products);
                }
            } else if (entity === 'inventory_out') {
                const products = getDemoData('products');
                const product = products.find(p => p.id == data.product_id);
                if (product) {
                    product.stock = parseInt(product.stock || 0) - parseInt(data.quantity || 0);
                    saveDemoData('products', products);
                }
            }

            addDemoItem(entity, data);
            showSuccessMessage('Data berhasil ditambahkan!');
        }

        // Redirect back to index page
        setTimeout(() => {
            const basePath = url.split('/').slice(0, -1).join('/');
            if (entity === 'inventory_in' || entity === 'inventory_out') {
                window.location.href = `/${entity.replace('_', '-')}`;
            } else {
                window.location.href = `/${entity}`;
            }
        }, 1000);
    }

    /**
     * Handle delete action
     */
    function handleDelete(form) {
        const url = form.action;
        let entity = null;
        let id = null;

        // Extract entity and ID from URL
        const productMatch = url.match(/\/products\/(\d+)/);
        const categoryMatch = url.match(/\/categories\/(\d+)/);
        const supplierMatch = url.match(/\/suppliers\/(\d+)/);
        const userMatch = url.match(/\/users\/(\d+)/);

        if (productMatch) {
            entity = 'products';
            id = productMatch[1];
        } else if (categoryMatch) {
            entity = 'categories';
            id = categoryMatch[1];
        } else if (supplierMatch) {
            entity = 'suppliers';
            id = supplierMatch[1];
        } else if (userMatch) {
            entity = 'users';
            id = userMatch[1];
        }

        if (!entity || !id) {
            console.warn('Could not determine entity/id for delete');
            return;
        }

        if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
            deleteDemoItem(entity, id);
            showSuccessMessage('Data berhasil dihapus!');

            setTimeout(() => {
                window.location.href = `/${entity}`;
            }, 1000);
        }
    }

    /**
     * Show success message
     */
    function showSuccessMessage(message) {
        const alert = document.createElement('div');
        alert.className = 'fixed top-20 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg z-50 animate-pulse';
        alert.innerHTML = `
            <div class="flex items-center space-x-2">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span class="font-semibold">${message}</span>
            </div>
        `;
        document.body.appendChild(alert);

        setTimeout(() => {
            alert.remove();
        }, 3000);
    }

    // =====================
    // Initialize
    // =====================

    // Wait for DOM to be ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', interceptForms);
    } else {
        interceptForms();
    }

    // Expose demo functions to window for debugging
    window.demoMode = {
        getData: getDemoData,
        saveData: saveDemoData,
        addItem: addDemoItem,
        updateItem: updateDemoItem,
        deleteItem: deleteDemoItem,
        getNextId: getNextId
    };

    console.log('✅ Demo Mode Script Loaded. Use window.demoMode for debugging.');

})();

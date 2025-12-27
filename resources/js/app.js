import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Demo Mode: expose services globally for Blade-driven pages
import { DemoStore } from './demo/store';
import { createInventoryService, createTransactionService, resetDemoData } from './demo/serviceFactory';
import { useInventory } from './demo/hooks/useInventory';
import { useTransaction } from './demo/hooks/useTransaction';

// Attach to window for easy access in inline scripts
window.DemoStore = DemoStore;
window.InventoryService = () => createInventoryService();
window.TransactionService = () => createTransactionService();
window.useInventory = useInventory;
window.useTransaction = useTransaction;
window.resetDemoData = resetDemoData;


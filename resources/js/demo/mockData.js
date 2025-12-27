// Initial mock data (two items each) used when Demo Mode is ON

export const mockInventory = [
  { id: 1, sku: 'DEM-001', name: 'Laptop Demo', stock: 10, price: 8500000 },
  { id: 2, sku: 'DEM-002', name: 'Mouse Demo', stock: 50, price: 150000 },
];

export const mockTransactions = [
  { id: 1, type: 'in', productId: 1, quantity: 5, date: new Date().toISOString(), note: 'Contoh pemasukan' },
  { id: 2, type: 'out', productId: 2, quantity: 2, date: new Date().toISOString(), note: 'Contoh pengeluaran' },
];

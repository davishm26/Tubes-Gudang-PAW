// Service factory that switches between real API (Axios) and demo mock data
// based on DemoStore state. Keeps in-memory arrays in demo mode.

import axios from 'axios';
import { DemoStore } from './store';
import { mockInventory, mockTransactions } from './mockData';

// Local in-memory state for demo mode (cloned from initial mocks)
let demoInventory = [...mockInventory];
let demoTransactions = [...mockTransactions];

// Helper to generate incremental IDs in demo
const nextId = (arr) => (arr.length ? Math.max(...arr.map((i) => i.id)) + 1 : 1);

// Reset demo data to initial baseline (two records each)
export function resetDemoData() {
  demoInventory = [...mockInventory];
  demoTransactions = [...mockTransactions];
}

// Subscribe reset when user exits demo mode
DemoStore.subscribe((state) => {
  if (!state.isDemoMode) {
    resetDemoData();
  }
});

export function createInventoryService() {
  const { isDemoMode } = DemoStore.getState();

  if (isDemoMode) {
    return {
      async list(params = {}) {
        // Simple filter by q (name or sku)
        const q = (params.q || '').toLowerCase();
        const filtered = q
          ? demoInventory.filter(
              (p) => p.name.toLowerCase().includes(q) || p.sku.toLowerCase().includes(q)
            )
          : demoInventory;
        return { data: filtered };
      },
      async get(id) {
        const item = demoInventory.find((p) => p.id === Number(id));
        return { data: item || null };
      },
      async create(payload) {
        const item = { id: nextId(demoInventory), ...payload };
        demoInventory.push(item);
        return { data: item };
      },
      async update(id, payload) {
        const idx = demoInventory.findIndex((p) => p.id === Number(id));
        if (idx === -1) return { data: null };
        demoInventory[idx] = { ...demoInventory[idx], ...payload };
        return { data: demoInventory[idx] };
      },
      async remove(id) {
        demoInventory = demoInventory.filter((p) => p.id !== Number(id));
        return { data: true };
      },
    };
  }

  // Real API mode via Axios
  return {
    async list(params = {}) {
      return axios.get('/inventory', { params });
    },
    async get(id) {
      return axios.get(`/inventory/${id}`);
    },
    async create(payload) {
      return axios.post('/inventory', payload);
    },
    async update(id, payload) {
      return axios.put(`/inventory/${id}`, payload);
    },
    async remove(id) {
      return axios.delete(`/inventory/${id}`);
    },
  };
}

export function createTransactionService() {
  const { isDemoMode } = DemoStore.getState();

  if (isDemoMode) {
    return {
      async list(params = {}) {
        const type = params.type; // optional 'in' | 'out'
        const filtered = type ? demoTransactions.filter((t) => t.type === type) : demoTransactions;
        return { data: filtered };
      },
      async get(id) {
        const item = demoTransactions.find((t) => t.id === Number(id));
        return { data: item || null };
      },
      async create(payload) {
        const item = { id: nextId(demoTransactions), date: new Date().toISOString(), ...payload };
        demoTransactions.push(item);
        return { data: item };
      },
      async update(id, payload) {
        const idx = demoTransactions.findIndex((t) => t.id === Number(id));
        if (idx === -1) return { data: null };
        demoTransactions[idx] = { ...demoTransactions[idx], ...payload };
        return { data: demoTransactions[idx] };
      },
      async remove(id) {
        demoTransactions = demoTransactions.filter((t) => t.id !== Number(id));
        return { data: true };
      },
    };
  }

  // Real API mode
  return {
    async list(params = {}) {
      return axios.get('/transactions', { params });
    },
    async get(id) {
      return axios.get(`/transactions/${id}`);
    },
    async create(payload) {
      return axios.post('/transactions', payload);
    },
    async update(id, payload) {
      return axios.put(`/transactions/${id}`, payload);
    },
    async remove(id) {
      return axios.delete(`/transactions/${id}`);
    },
  };
}

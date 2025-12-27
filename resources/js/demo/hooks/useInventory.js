import { createInventoryService } from '../../demo/serviceFactory';
import { DemoStore } from '../../demo/store';

// Simple helper to always get the latest service respecting current Demo Mode
export function useInventory() {
  const state = DemoStore.getState();
  // Return a fresh service each call so it reflects mode changes
  return createInventoryService(state);
}

import { createTransactionService } from '../../demo/serviceFactory';
import { DemoStore } from '../../demo/store';

export function useTransaction() {
  const state = DemoStore.getState();
  return createTransactionService(state);
}

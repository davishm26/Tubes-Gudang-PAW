// Demo Mode Store (in-memory, resets on refresh)
// Manages global flags: isDemoMode, currentRole

export const DemoStore = (() => {
  let isDemoMode = false;
  let currentRole = null; // 'admin' | 'staff'
  const subscribers = new Set();

  function notify() {
    const state = { isDemoMode, currentRole };
    subscribers.forEach((fn) => {
      try { fn(state); } catch {}
    });
  }

  return {
    getState: () => ({ isDemoMode, currentRole }),
    setDemoMode: (flag) => { isDemoMode = !!flag; notify(); },
    setRole: (role) => { currentRole = role; notify(); },
    subscribe: (fn) => { subscribers.add(fn); return () => subscribers.delete(fn); },
    reset: () => { isDemoMode = false; currentRole = null; notify(); },
  };
})();

// Convenience API for window access if needed
if (typeof window !== 'undefined') {
  window.DemoStore = DemoStore;
}

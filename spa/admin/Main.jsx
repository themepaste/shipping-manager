import React from 'react';
import ReactDOM from 'react-dom/client';
import Admin from './components/Admin';
import './assets/style.css';

const CONTAINER_ID = 'tpsm-shipping-rules-wrapper';

function Main() {
  return <Admin />;
}

// The container this app mounts into lives inside a shipping method's instance
// settings. WooCommerce renders those in a Backbone modal that is injected long
// after DOMContentLoaded, so a single mount attempt on load silently did nothing
// in that flow. Track the container instead, and mount/unmount as it comes and
// goes.
let currentContainer = null;
let currentRoot = null;

const sync = () => {
  const container = document.getElementById(CONTAINER_ID);

  if (container === currentContainer) {
    return;
  }

  if (currentRoot) {
    currentRoot.unmount();
    currentRoot = null;
  }

  currentContainer = container;

  if (container) {
    currentRoot = ReactDOM.createRoot(container);
    currentRoot.render(<Main />);
  }
};

// Defer out of the MutationObserver callback so we never unmount a root while
// React is mid-render.
let scheduled = false;
const scheduleSync = () => {
  if (scheduled) {
    return;
  }
  scheduled = true;
  setTimeout(() => {
    scheduled = false;
    sync();
  }, 0);
};

const initApp = () => {
  sync();

  if (document.body) {
    new MutationObserver(scheduleSync).observe(document.body, {
      childList: true,
      subtree: true,
    });
  }
};

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initApp);
} else {
  initApp();
}

export default Main;

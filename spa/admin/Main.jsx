import React from 'react';
import ReactDOM from 'react-dom/client';
import Admin from './components/Admin';
import './assets/style.css';

function Main() {
  return <Admin />;
}

const initApp = () => {
  const container = document.getElementById('tpsm-shipping-rules-wrapper');

  if (!container) return;

  const root = ReactDOM.createRoot(container);
  root.render(<Main />);
};

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initApp);
} else {
  initApp();
}

export default Main;

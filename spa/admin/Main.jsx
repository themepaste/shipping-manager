import React from 'react';
import ReactDOM from 'react-dom/client';
import Admin from './components/Admin';

function Main() {
    return <Admin />;
}

ReactDOM.createRoot(
    document.getElementById('tpsm-shipping-rules-wrapper')
).render(<Main />);

export default Main;

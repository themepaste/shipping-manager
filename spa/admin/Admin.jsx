import React from 'react';
import ReactDOM from 'react-dom/client';

function Admin() {
    return (
        <>
            <h1>Hello This is from react Component</h1>
        </>
    );
}

ReactDOM.createRoot(
    document.getElementById('tpsm-shipping-rules-wrapper')
).render(<Admin />);

export default Admin;

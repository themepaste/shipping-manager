import React, { useEffect, useState } from 'react';
import ReactDOM from 'react-dom/client';

function Admin() {
    const [rows, setRows] = useState([{ condition: 'Weight', cost: '' }]);

    // Update the hidden input whenever rows change
    useEffect(() => {
        const data = {};
        rows.forEach((row) => {
            data[row.condition.toLowerCase()] = row.cost;
        });
        document.getElementById(
            'woocommerce_shipping-manager_tpsm-hidden'
        ).value = JSON.stringify(data);
    }, [rows]);

    const handleChange = (index, field, value) => {
        const updatedRows = [...rows];
        updatedRows[index][field] = value;
        setRows(updatedRows);
    };

    const addRow = () => {
        setRows([...rows, { condition: '', cost: '' }]);
    };

    const removeRow = (index) => {
        const updatedRows = [...rows];
        updatedRows.splice(index, 1);
        setRows(updatedRows);
    };

    return (
        <>
            <table border="1" cellPadding="5" cellSpacing="0">
                <thead>
                    <tr>
                        <th>Conditions</th>
                        <th>Costs</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    {rows.map((row, index) => (
                        <tr key={index}>
                            <td>
                                <input
                                    type="text"
                                    value={row.condition}
                                    onChange={(e) =>
                                        handleChange(
                                            index,
                                            'condition',
                                            e.target.value
                                        )
                                    }
                                />
                            </td>
                            <td>
                                <input
                                    type="number"
                                    value={row.cost}
                                    onChange={(e) =>
                                        handleChange(
                                            index,
                                            'cost',
                                            e.target.value
                                        )
                                    }
                                />
                            </td>
                            <td>
                                <button
                                    type="button"
                                    onClick={() => removeRow(index)}
                                >
                                    Delete
                                </button>
                            </td>
                        </tr>
                    ))}
                </tbody>
            </table>

            <button
                type="button"
                onClick={addRow}
                style={{ marginTop: '10px' }}
            >
                Add New Row
            </button>

            {/* Hidden field that will contain JSON string of data */}
        </>
    );
}

ReactDOM.createRoot(
    document.getElementById('tpsm-shipping-rules-wrapper')
).render(<Admin />);

export default Admin;

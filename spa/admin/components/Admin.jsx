import React, { useEffect, useState } from 'react';
import parse from 'html-react-parser';

TPSM_ADMIN.woocommerce_data.currency_symbol;

function Admin() {
    const [rows, setRows] = useState([{ condition: 'Weight', cost: '' }]);

    useEffect(() => {
        const hiddenField = document.getElementById(
            'woocommerce_shipping-manager_tpsm-hidden'
        );
        if (hiddenField && hiddenField.value) {
            try {
                const parsed = JSON.parse(hiddenField.value);
                const restoredRows = Object.entries(parsed).map(
                    ([key, value]) => ({
                        condition: key.charAt(0).toUpperCase() + key.slice(1),
                        cost: value,
                    })
                );
                setRows(restoredRows);
            } catch (error) {
                console.warn('Invalid JSON in hidden field:', error);
                // fallback to default
            }
        }
    }, []);

    // Update the hidden input whenever rows change
    useEffect(() => {
        const data = {};
        rows.forEach((row) => {
            if (row.condition.trim() !== '') {
                data[row.condition.toLowerCase()] = row.cost;
            }
        });

        const hiddenField = document.getElementById(
            'woocommerce_shipping-manager_tpsm-hidden'
        );
        if (hiddenField) {
            hiddenField.value = JSON.stringify(data);
        }
    }, [rows]);

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
            <table className="tpsm-shipping-rule-table-wrapper">
                <thead>
                    <tr>
                        <th></th>
                        <th>
                            <input type="checkbox" />
                        </th>
                        <th>Conditions</th>
                        <th>Costs</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    {rows.map((row, index) => (
                        <tr key={index}>
                            <td>
                                <span>{index + 1}</span>
                            </td>
                            <td>
                                <input type="checkbox" />
                            </td>
                            <td>
                                <select name="" id="">
                                    <option value="total-price">
                                        Total Price
                                    </option>
                                    <option value="product-weight">
                                        Product Weight
                                    </option>
                                </select>
                            </td>
                            <td>
                                <input type="number" />
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
            <button type="button" style={{ marginTop: '10px' }}>
                Duplicate Row
            </button>
            <button type="button" style={{ marginTop: '10px' }}>
                Delete Selected Row
            </button>
        </>
    );
}
export default Admin;

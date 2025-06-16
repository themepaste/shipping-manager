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
        </>
    );
}
export default Admin;

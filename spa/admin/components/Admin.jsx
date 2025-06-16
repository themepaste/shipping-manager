import React, { useEffect, useState } from 'react';

function Admin() {
    const [rows, setRows] = useState([{ condition: 'Weight', cost: '' }]);
    const [selectedRows, setSelectedRows] = useState([]);

    useEffect(() => {
        const hiddenField = document.getElementById(
            'woocommerce_shipping-manager_tpsm_hidden'
        );
        if (hiddenField && hiddenField.value) {
            try {
                const parsed = JSON.parse(hiddenField.value);
                setRows(parsed);
            } catch (e) {
                console.error('Invalid JSON in hidden field');
            }
        }
    }, []);

    useEffect(() => {
        const hiddenField = document.getElementById(
            'woocommerce_shipping-manager_tpsm_hidden'
        );
        if (hiddenField) {
            hiddenField.value = JSON.stringify(rows);
        }
    }, [rows]);

    const handleRowChange = (index, field, value) => {
        const updatedRows = [...rows];
        updatedRows[index][field] = value;
        setRows(updatedRows);
    };

    const addRow = () => {
        setRows([...rows, { condition: '', cost: '' }]);
    };

    const deleteRow = (index) => {
        const updatedRows = rows.filter((_, i) => i !== index);
        setRows(updatedRows);
        setSelectedRows(selectedRows.filter((i) => i !== index));
    };

    const deleteSelectedRows = () => {
        const updatedRows = rows.filter((_, i) => !selectedRows.includes(i));
        setRows(updatedRows);
        setSelectedRows([]);
    };

    const duplicateSelectedRows = () => {
        const duplicates = selectedRows.map((index) => ({ ...rows[index] }));
        setRows([...rows, ...duplicates]);
    };

    const handleCheckboxChange = (index) => {
        setSelectedRows((prev) =>
            prev.includes(index)
                ? prev.filter((i) => i !== index)
                : [...prev, index]
        );
    };

    return (
        <>
            <table className="tpsm-shipping-rule-table-wrapper">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>
                            <input
                                type="checkbox"
                                checked={selectedRows.length === rows.length}
                                onChange={(e) => {
                                    if (e.target.checked) {
                                        setSelectedRows(rows.map((_, i) => i));
                                    } else {
                                        setSelectedRows([]);
                                    }
                                }}
                            />
                        </th>
                        <th>Conditions</th>
                        <th>Costs</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    {rows.map((row, index) => (
                        <tr key={index}>
                            <td>{index + 1}</td>
                            <td>
                                <input
                                    type="checkbox"
                                    checked={selectedRows.includes(index)}
                                    onChange={() => handleCheckboxChange(index)}
                                />
                            </td>
                            <td>
                                <select
                                    value={row.condition}
                                    onChange={(e) =>
                                        handleRowChange(
                                            index,
                                            'condition',
                                            e.target.value
                                        )
                                    }
                                >
                                    <option value="always">Always</option>
                                    <option value="total-price">
                                        Total Price
                                    </option>
                                    <option value="Weight">Weight</option>
                                </select>
                            </td>
                            <td>
                                <input
                                    type="number"
                                    value={row.cost}
                                    onChange={(e) =>
                                        handleRowChange(
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
                                    onClick={() => deleteRow(index)}
                                >
                                    Delete
                                </button>
                            </td>
                        </tr>
                    ))}
                </tbody>
            </table>

            <div style={{ marginTop: '10px' }}>
                <button type="button" onClick={addRow}>
                    Add New Row
                </button>
                <button
                    type="button"
                    onClick={duplicateSelectedRows}
                    style={{ marginLeft: '10px' }}
                >
                    Duplicate Selected
                </button>
                <button
                    type="button"
                    onClick={deleteSelectedRows}
                    style={{ marginLeft: '10px' }}
                >
                    Delete Selected
                </button>
            </div>
        </>
    );
}

export default Admin;

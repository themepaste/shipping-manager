import React, { useEffect, useState } from 'react';
import parse from 'html-react-parser';
import Select from 'react-select';

function Admin() {
    const [rows, setRows] = useState([
        { condition: 'Weight', cost: '', min: '', max: '' },
    ]);
    const [selectedRows, setSelectedRows] = useState([]);

    const conditionsValues = Object.keys(TPSM_ADMIN.shipping_rules_select);
    const conditionsLabel = Object.values(TPSM_ADMIN.shipping_rules_select);

    console.log(TPSM_ADMIN);
    const classOptions = [
        { value: 'class-1', label: 'Class 1' },
        { value: 'class-2', label: 'Class 2' },
        { value: 'class-3', label: 'Class 3' },
        { value: 'class-4', label: 'Class 4' },
    ];

    // useEffect(() => {
    //     const hiddenField = document.getElementById(
    //         'woocommerce_shipping-manager_tpsm_hidden'
    //     );
    //     if (hiddenField && hiddenField.value) {
    //         try {
    //             const parsed = JSON.parse(hiddenField.value);
    //             setRows(parsed);
    //         } catch (e) {
    //             console.error('Invalid JSON in hidden field');
    //         }
    //     }
    // }, []);
    useEffect(() => {
        const hiddenField = document.getElementById(
            'woocommerce_shipping-manager_tpsm_hidden'
        );
        if (hiddenField && hiddenField.value) {
            try {
                const parsed = JSON.parse(hiddenField.value);
                // Ensure 'multi' is added if missing
                const normalized = parsed.map((row) => ({
                    ...row,
                    multi: row.multi || [],
                }));
                setRows(normalized);
            } catch (e) {
                console.error('Invalid JSON in hidden field');
            }
        }
    }, []);

    const handleMultiSelectChange = (index, selectedOptions) => {
        const updatedRows = [...rows];
        updatedRows[index].multi = selectedOptions.map((opt) => opt.value);
        setRows(updatedRows);
    };

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
        setRows([
            ...rows,
            {
                condition: conditionsValues[0],
                cost: '',
                min: '',
                max: '',
                multi: [],
            },
        ]);
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
                                    {conditionsValues.map((value, index) => (
                                        <option key={index} value={value}>
                                            {conditionsLabel[index]}
                                        </option>
                                    ))}
                                </select>
                                {row.condition === 'total-price' && (
                                    <>
                                        {parse(
                                            TPSM_ADMIN.woocommerce_data
                                                .currency_symbol
                                        )}
                                        <input
                                            type="number"
                                            placeholder="Min"
                                            value={row.min}
                                            onChange={(e) =>
                                                handleRowChange(
                                                    index,
                                                    'min',
                                                    e.target.value
                                                )
                                            }
                                        />
                                        {parse(
                                            TPSM_ADMIN.woocommerce_data
                                                .currency_symbol
                                        )}
                                        <input
                                            type="number"
                                            placeholder="Max"
                                            value={row.max}
                                            onChange={(e) =>
                                                handleRowChange(
                                                    index,
                                                    'max',
                                                    e.target.value
                                                )
                                            }
                                        />
                                    </>
                                )}
                                {row.condition === 'sub-total-price' && (
                                    <>
                                        {parse(
                                            TPSM_ADMIN.woocommerce_data
                                                .currency_symbol
                                        )}
                                        <input
                                            type="number"
                                            placeholder="Min"
                                            value={row.min}
                                            onChange={(e) =>
                                                handleRowChange(
                                                    index,
                                                    'min',
                                                    e.target.value
                                                )
                                            }
                                        />
                                        {parse(
                                            TPSM_ADMIN.woocommerce_data
                                                .currency_symbol
                                        )}
                                        <input
                                            type="number"
                                            placeholder="Max"
                                            value={row.max}
                                            onChange={(e) =>
                                                handleRowChange(
                                                    index,
                                                    'max',
                                                    e.target.value
                                                )
                                            }
                                        />
                                    </>
                                )}
                                {row.condition === 'weight' && (
                                    <>
                                        {parse(
                                            TPSM_ADMIN.woocommerce_data
                                                .weight_unit
                                        )}
                                        <input
                                            type="number"
                                            placeholder="Min"
                                            value={row.min}
                                            onChange={(e) =>
                                                handleRowChange(
                                                    index,
                                                    'min',
                                                    e.target.value
                                                )
                                            }
                                        />
                                        {parse(
                                            TPSM_ADMIN.woocommerce_data
                                                .weight_unit
                                        )}
                                        <input
                                            type="number"
                                            placeholder="Max"
                                            value={row.max}
                                            onChange={(e) =>
                                                handleRowChange(
                                                    index,
                                                    'max',
                                                    e.target.value
                                                )
                                            }
                                        />
                                    </>
                                )}
                                {row.condition === 'shipping-class' && (
                                    <Select
                                        options={classOptions}
                                        isMulti
                                        placeholder="Select classes..."
                                        value={classOptions.filter((opt) =>
                                            row.multi.includes(opt.value)
                                        )}
                                        onChange={(selectedOptions) =>
                                            handleMultiSelectChange(
                                                index,
                                                selectedOptions
                                            )
                                        }
                                    />
                                )}
                            </td>

                            <td>
                                {parse(
                                    TPSM_ADMIN.woocommerce_data.currency_symbol
                                )}
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

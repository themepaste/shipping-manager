import React, { useEffect, useState } from 'react';
import parse from 'html-react-parser';
import Select from 'react-select';

/**
 * The main component for the shipping rules table.
 *
 * This component renders a table with a row for each shipping rule, and
 * columns for the condition, cost, and action. The condition column is a
 * select dropdown with options for the different conditions, and the cost
 * column is a text input. The action column has a button to delete the row.
 *
 * The component also has a button to add a new row, and buttons to duplicate
 * and delete the selected rows.
 *
 * @return {ReactElement} - A JSX element representing the shipping rules table.
 */
function Admin() {
    const [rows, setRows] = useState([
        { condition: 'Weight', cost: '', min: '', max: '' },
    ]);
    const [selectedRows, setSelectedRows] = useState([]);

    const conditionsValues = Object.keys(TPSM_ADMIN.shipping_rules_select);
    const conditionsLabel = Object.values(TPSM_ADMIN.shipping_rules_select);

    console.log(TPSM_ADMIN);
    const classOptions = TPSM_ADMIN.wc_shipping_classess;

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

    /**
     * Updates the 'multi' property of the row at the specified index with
     * the values of the selected options.
     *
     * @param {number} index - The index of the row to update.
     * @param {Array<Object>} selectedOptions - An array of objects with
     *                                          'value' and 'label' properties.
     */
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

    /**
     * Updates the row at the specified index by setting the specified field to the given value.
     *
     * @param {number} index - The index of the row to update.
     * @param {string} field - The field to update (one of 'condition', 'cost', 'min', 'max', or 'multi').
     * @param {string|number|Array<string|number>} value - The new value for the specified field.
     */

    const handleRowChange = (index, field, value) => {
        const updatedRows = [...rows];
        updatedRows[index][field] = value;
        setRows(updatedRows);
    };

    /**
     * Adds a new row to the rows state with default values.
     *
     * The new row has the default condition set to the first value
     * in conditionsValues and initializes cost, min, max as empty strings,
     * and multi as an empty array.
     */

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

    /**
     * Deletes the row at the specified index and removes it from the selectedRows state.
     *
     * @param {number} index - The index of the row to delete.
     */
    const deleteRow = (index) => {
        const updatedRows = rows.filter((_, i) => i !== index);
        setRows(updatedRows);
        setSelectedRows(selectedRows.filter((i) => i !== index));
    };

    /**
     * Deletes the selected rows and resets the selectedRows state.
     *
     * Loops over the rows and filters out the ones that are selected.
     * Updates the rows state with the new array and resets the selectedRows
     * state to an empty array.
     */
    const deleteSelectedRows = () => {
        const updatedRows = rows.filter((_, i) => !selectedRows.includes(i));
        setRows(updatedRows);
        setSelectedRows([]);
    };

    /**
     * Duplicates the selected rows and adds them to the rows state.
     *
     * Only has an effect if selectedRows is not empty.
     */
    const duplicateSelectedRows = () => {
        const duplicates = selectedRows.map((index) => ({ ...rows[index] }));
        setRows([...rows, ...duplicates]);
    };

    /**
     * Toggles the selection state of a row at the specified index.
     *
     * @param {number} index - The index of the row to toggle.
     *
     * Updates the selectedRows state by adding the index to the selection
     * if it is not already selected, or removing it if it is.
     */
    const handleCheckboxChange = (index) => {
        setSelectedRows((prev) =>
            prev.includes(index)
                ? prev.filter((i) => i !== index)
                : [...prev, index]
        );
    };

    /**
     * Returns an optgroup element with options populated from
     * conditionsValues and conditionsLabel, starting from index
     * `start` and ending at index `end`.
     *
     * @param {string} label - The label for the optgroup element.
     * @param {number} start - The starting index (inclusive).
     * @param {number} end - The ending index (exclusive).
     *
     * @returns {ReactElement} - An optgroup element.
     */
    const renderOptGroup = (label, start, end) => (
        <optgroup label={label} key={label}>
            {conditionsValues.slice(start, end).map((value, idx) => (
                <option key={start + idx} value={value}>
                    {conditionsLabel[start + idx]}
                </option>
            ))}
        </optgroup>
    );

    return (
        <>
            <div className="tpsm-shipping-rule-table-wrapper">
                <table className="tpsm-shipping-rule-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>
                                <input
                                    type="checkbox"
                                    checked={
                                        selectedRows.length === rows.length
                                    }
                                    onChange={(e) => {
                                        if (e.target.checked) {
                                            setSelectedRows(
                                                rows.map((_, i) => i)
                                            );
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
                                        onChange={() =>
                                            handleCheckboxChange(index)
                                        }
                                    />
                                </td>
                                <td>
                                    <select
                                        className="tpsm-shipping-rule-select"
                                        value={row.condition}
                                        onChange={(e) =>
                                            handleRowChange(
                                                index,
                                                'condition',
                                                e.target.value
                                            )
                                        }
                                    >
                                        {renderOptGroup('General', 0, 1)}
                                        {renderOptGroup('Cart', 1, 3)}
                                        {renderOptGroup('Product', 3)}
                                    </select>

                                    {row.condition === 'tpsm-total-price' && (
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
                                    {row.condition ===
                                        'tpsm-sub-total-price' && (
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
                                    {row.condition === 'tpsm-total-weight' && (
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
                                    {row.condition ===
                                        'tpsm-shipping-class' && (
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
                                    <div className="tpsm-costs-column-data">
                                        {parse(
                                            TPSM_ADMIN.woocommerce_data
                                                .currency_symbol
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
                                    </div>
                                </td>
                                <td>
                                    <button
                                        type="button"
                                        onClick={() => deleteRow(index)}
                                    >
                                        <img
                                            src={
                                                TPSM_ADMIN.assets_url +
                                                '/admin/img/delete.png'
                                            }
                                            with="20"
                                            height="20"
                                        />
                                    </button>
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>

                <div className="tpsm-shipping-rule-actions">
                    <button
                        className="tpsm-button"
                        type="button"
                        onClick={addRow}
                    >
                        Add New Row
                    </button>
                    <button
                        className="tpsm-button"
                        type="button"
                        onClick={duplicateSelectedRows}
                    >
                        Duplicate Selected
                    </button>
                    <button
                        className="tpsm-button"
                        type="button"
                        onClick={deleteSelectedRows}
                    >
                        Delete Selected
                    </button>
                </div>
            </div>
        </>
    );
}

export default Admin;

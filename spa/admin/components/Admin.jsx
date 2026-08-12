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
const HIDDEN_FIELD_ID = 'woocommerce_shipping-manager_tpsm_hidden';

/**
 * A brand new, empty rule row.
 *
 * `equal` defaults to 'equals' so a quantity rule that the merchant never
 * touched still matches the operator the UI shows for it.
 *
 * @param {string} condition - Condition slug for the new row.
 * @return {Object} The new row.
 */
const emptyRow = (condition) => ({
  condition,
  cost: '',
  equal: 'equals',
  value: '',
  min: '',
  max: '',
  multi: [],
});

function Admin() {
  // TPSM_ADMIN is injected via wp_localize_script; fall back to empty data so a
  // missing/failed localisation degrades instead of throwing.
  const adminData = typeof TPSM_ADMIN !== 'undefined' ? TPSM_ADMIN : {};
  const conditions = adminData.shipping_rules_select || {};
  const conditionsValues = Object.keys(conditions);
  const conditionsLabel = Object.values(conditions);
  const classOptions = adminData.wc_shipping_classess || [];
  const operators = adminData.operators || [];
  const wooData = adminData.woocommerce_data || {};
  const currencySymbol = wooData.currency_symbol || '';
  const weightUnit = wooData.weight_unit || '';

  const [rows, setRows] = useState([
    emptyRow(conditionsValues[0] || 'tpsm-flat-rate'),
  ]);
  const [selectedRows, setSelectedRows] = useState([]);

  useEffect(() => {
    const hiddenField = document.getElementById(HIDDEN_FIELD_ID);
    if (hiddenField && hiddenField.value) {
      try {
        const parsed = JSON.parse(hiddenField.value);

        if (!Array.isArray(parsed)) {
          return;
        }

        // Backfill anything a row saved by an older version may be missing.
        const normalized = parsed.map((row) => ({
          ...emptyRow(conditionsValues[0] || 'tpsm-flat-rate'),
          ...row,
          multi: Array.isArray(row.multi) ? row.multi : [],
          equal: row.equal || 'equals',
        }));
        setRows(normalized);
      } catch (e) {
        console.error('Invalid JSON in hidden field');
      }
    }
    // eslint-disable-next-line react-hooks/exhaustive-deps
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
    const values = (selectedOptions || []).map((opt) => opt.value);
    setRows((prev) =>
      prev.map((row, i) => (i === index ? { ...row, multi: values } : row)),
    );
  };

  useEffect(() => {
    const hiddenField = document.getElementById(HIDDEN_FIELD_ID);
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
    // Copy the row rather than mutating it in place.
    setRows((prev) =>
      prev.map((row, i) => (i === index ? { ...row, [field]: value } : row)),
    );
  };

  /**
   * Adds a new row to the rows state with default values.
   */

  const addRow = () => {
    setRows((prev) => [
      ...prev,
      emptyRow(conditionsValues[0] || 'tpsm-flat-rate'),
    ]);
  };

  /**
   * Deletes the row at the specified index and removes it from the selectedRows state.
   *
   * @param {number} index - The index of the row to delete.
   */
  const deleteRow = (index) => {
    setRows((prev) => prev.filter((_, i) => i !== index));
    // Every selection above the removed row shifts down by one; without this
    // the checkboxes ended up pointing at the wrong rules.
    setSelectedRows((prev) =>
      prev.filter((i) => i !== index).map((i) => (i > index ? i - 1 : i)),
    );
  };

  /**
   * Deletes the selected rows and resets the selectedRows state.
   *
   * Loops over the rows and filters out the ones that are selected.
   * Updates the rows state with the new array and resets the selectedRows
   * state to an empty array.
   */
  const deleteSelectedRows = () => {
    setRows((prev) => prev.filter((_, i) => !selectedRows.includes(i)));
    setSelectedRows([]);
  };

  /**
   * Duplicates the selected rows and adds them to the rows state.
   *
   * Only has an effect if selectedRows is not empty.
   */
  const duplicateSelectedRows = () => {
    const duplicates = selectedRows
      .filter((index) => rows[index])
      .map((index) => ({ ...rows[index], multi: [...rows[index].multi] }));
    setRows((prev) => [...prev, ...duplicates]);
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
      prev.includes(index) ? prev.filter((i) => i !== index) : [...prev, index],
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
                    rows.length > 0 && selectedRows.length === rows.length
                  }
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
                    className="tpsm-shipping-rule-select"
                    value={row.condition}
                    onChange={(e) =>
                      handleRowChange(index, 'condition', e.target.value)
                    }
                  >
                    {renderOptGroup('General', 0, 1)}
                    {renderOptGroup('Cart', 1, 4)}
                    {renderOptGroup('Product', 4, 7)}
                  </select>

                  {row.condition === 'tpsm-cart-quantity' && (
                    <>
                      {/* select operator — per row, not shared across rows */}
                      <div className="tpsm-max-min-field-wrapper">
                        <select
                          className="tpsm-shipping-rule-select"
                          value={row.equal || 'equals'}
                          onChange={(e) =>
                            handleRowChange(index, 'equal', e.target.value)
                          }
                        >
                          {operators.map((op) => (
                            <option key={op.value} value={op.value}>
                              {op.label}
                            </option>
                          ))}
                        </select>
                      </div>
                      <div className="tpsm-max-min-field-wrapper">
                        <input
                          type="number"
                          placeholder="Value"
                          value={row.value}
                          onChange={(e) =>
                            handleRowChange(index, 'value', e.target.value)
                          }
                        />
                      </div>
                    </>
                  )}

                  {row.condition === 'tpsm-total-price' && (
                    <>
                      <div className="tpsm-max-min-field-wrapper">
                        {parse(currencySymbol)}
                        <input
                          type="number"
                          placeholder="Min"
                          value={row.min}
                          onChange={(e) =>
                            handleRowChange(index, 'min', e.target.value)
                          }
                        />
                      </div>
                      <div className="tpsm-max-min-field-wrapper">
                        {parse(currencySymbol)}
                        <input
                          type="number"
                          placeholder="Max"
                          value={row.max}
                          onChange={(e) =>
                            handleRowChange(index, 'max', e.target.value)
                          }
                        />
                      </div>
                    </>
                  )}
                  {row.condition === 'tpsm-sub-total-price' && (
                    <>
                      <div className="tpsm-max-min-field-wrapper">
                        {parse(currencySymbol)}
                        <input
                          type="number"
                          placeholder="Min"
                          value={row.min}
                          onChange={(e) =>
                            handleRowChange(index, 'min', e.target.value)
                          }
                        />
                      </div>
                      <div className="tpsm-max-min-field-wrapper">
                        {parse(currencySymbol)}
                        <input
                          type="number"
                          placeholder="Max"
                          value={row.max}
                          onChange={(e) =>
                            handleRowChange(index, 'max', e.target.value)
                          }
                        />
                      </div>
                    </>
                  )}
                  {row.condition === 'tpsm-total-weight' && (
                    <>
                      <div className="tpsm-max-min-field-wrapper">
                        {weightUnit}
                        <input
                          type="number"
                          placeholder="Min"
                          value={row.min}
                          onChange={(e) =>
                            handleRowChange(index, 'min', e.target.value)
                          }
                        />
                      </div>
                      <div className="tpsm-max-min-field-wrapper">
                        {weightUnit}
                        <input
                          type="number"
                          placeholder="Max"
                          value={row.max}
                          onChange={(e) =>
                            handleRowChange(index, 'max', e.target.value)
                          }
                        />
                      </div>
                    </>
                  )}
                  {row.condition === 'tpsm-shipping-class' && (
                    <Select
                      options={classOptions}
                      isMulti
                      placeholder="Select classes..."
                      value={classOptions.filter((opt) =>
                        row.multi.includes(opt.value),
                      )}
                      onChange={(selectedOptions) =>
                        handleMultiSelectChange(index, selectedOptions)
                      }
                      styles={{
                        container: (base) => ({
                          ...base,
                          minWidth: '350px',
                        }),
                      }}
                    />
                  )}
                </td>

                <td>
                  <div className="tpsm-costs-column-data">
                    {parse(currencySymbol)}
                    <input
                      type="number"
                      value={row.cost}
                      onChange={(e) =>
                        handleRowChange(index, 'cost', e.target.value)
                      }
                      placeholder="10.00"
                    />
                  </div>
                </td>
                <td>
                  <button type="button" onClick={() => deleteRow(index)}>
                    <img
                      src={(adminData.assets_url || '') + '/admin/img/delete.png'}
                      alt="Delete row"
                      width="20"
                      height="20"
                    />
                  </button>
                </td>
              </tr>
            ))}
          </tbody>
        </table>

        <div className="tpsm-shipping-rule-actions">
          <button className="tpsm-button" type="button" onClick={addRow}>
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

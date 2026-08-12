import React, { useEffect, useState } from 'react';
import parse from 'html-react-parser';
import Select from 'react-select';
import AsyncSelect from 'react-select/async';
import ImportExport from './ImportExport';

const HIDDEN_FIELD_ID = 'woocommerce_shipping-manager_tpsm_hidden';

// Conditions whose cost is multiplied out rather than matched on a comparison.
const MULTIPLIER_CONDITIONS = ['tpsm-per-item', 'tpsm-per-weight-unit'];
// Conditions driven by an operator + single value.
const OPERATOR_CONDITIONS = ['tpsm-cart-quantity', 'tpsm-line-items'];
// Conditions driven by a min/max range.
const RANGE_CONDITIONS = [
  'tpsm-sub-total-price',
  'tpsm-total-price',
  'tpsm-total-weight',
  'tpsm-cart-volume',
];
// Conditions driven by a multi-select of a fixed option list.
const MULTI_CONDITIONS = [
  'tpsm-shipping-class',
  'tpsm-product-category',
  'tpsm-product-tag',
];
// Conditions driven by a free-text list.
const TEXT_LIST_CONDITIONS = ['tpsm-postcode', 'tpsm-state', 'tpsm-coupon'];

/**
 * A brand new, empty rule row.
 *
 * `equal` defaults to 'equals' so a rule the merchant never touched still
 * matches the operator the UI shows for it.
 *
 * @param {string} condition - Condition slug for the new row.
 * @return {Object} The new row.
 */
const emptyRow = (condition) => ({
  condition,
  label: '',
  enabled: true,
  cost: '',
  equal: 'equals',
  value: '',
  min: '',
  max: '',
  multi: [],
  multiLabels: {},
});

/**
 * Normalise a row loaded from storage, backfilling anything a row saved by an
 * older version is missing.
 *
 * @param {Object} row       Stored row.
 * @param {string} fallback  Condition to use when the row has none.
 * @return {Object} A complete row.
 */
const normalizeRow = (row, fallback) => ({
  ...emptyRow(fallback),
  ...row,
  multi: Array.isArray(row.multi) ? row.multi : [],
  multiLabels: row.multiLabels && typeof row.multiLabels === 'object' ? row.multiLabels : {},
  equal: row.equal || 'equals',
  // Rows predate the per-rule toggle; absent means enabled.
  enabled: row.enabled === undefined ? true : Boolean(row.enabled),
  label: typeof row.label === 'string' ? row.label : '',
});

function Admin() {
  // TPSM_ADMIN is injected via wp_localize_script; fall back to empty data so a
  // missing/failed localisation degrades instead of throwing.
  const adminData = typeof TPSM_ADMIN !== 'undefined' ? TPSM_ADMIN : {};
  const conditions = adminData.shipping_rules_select || {};
  const conditionGroups = adminData.condition_groups || {};
  const conditionHelp = adminData.condition_help || {};
  const classOptions = adminData.wc_shipping_classess || [];
  const categoryOptions = adminData.product_categories || [];
  const tagOptions = adminData.product_tags || [];
  const productSearch = adminData.product_search || {};
  const operators = adminData.operators || [];
  const wooData = adminData.woocommerce_data || {};
  const currencySymbol = wooData.currency_symbol || '';
  const weightUnit = wooData.weight_unit || '';
  const i18n = adminData.i18n || {};

  const firstCondition = Object.keys(conditions)[0] || 'tpsm-flat-rate';

  const [rows, setRows] = useState([]);
  const [selectedRows, setSelectedRows] = useState([]);

  useEffect(() => {
    const hiddenField = document.getElementById(HIDDEN_FIELD_ID);

    // The raw JSON field is now driven entirely by this app, so hide the row
    // WooCommerce renders for it rather than showing merchants a blob of JSON.
    if (hiddenField && hiddenField.closest('tr')) {
      hiddenField.closest('tr').style.display = 'none';
    }

    if (hiddenField && hiddenField.value) {
      try {
        const parsed = JSON.parse(hiddenField.value);
        if (Array.isArray(parsed)) {
          setRows(parsed.map((row) => normalizeRow(row, firstCondition)));
        }
      } catch (e) {
        console.error('Invalid JSON in hidden field');
      }
    }
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  useEffect(() => {
    const hiddenField = document.getElementById(HIDDEN_FIELD_ID);
    if (hiddenField) {
      hiddenField.value = JSON.stringify(rows);
    }
  }, [rows]);

  const updateRow = (index, patch) =>
    setRows((prev) => prev.map((row, i) => (i === index ? { ...row, ...patch } : row)));

  const handleRowChange = (index, field, value) => updateRow(index, { [field]: value });

  const handleMultiSelectChange = (index, selectedOptions) =>
    updateRow(index, { multi: (selectedOptions || []).map((opt) => opt.value) });

  /**
   * Store selected product IDs plus their labels.
   *
   * The engine only reads the IDs, but keeping the labels on the row means the
   * builder can render chips without a second lookup, and they survive
   * export/import to another site.
   *
   * @param {number} index    Row index.
   * @param {Array}  selected Selected options.
   */
  const handleProductChange = (index, selected) => {
    const chosen = selected || [];
    const labels = {};
    chosen.forEach((opt) => {
      labels[opt.value] = opt.label;
    });
    updateRow(index, { multi: chosen.map((opt) => opt.value), multiLabels: labels });
  };

  /**
   * Look products up through WooCommerce's own admin search endpoint.
   *
   * @param {string} term Search term.
   * @return {Promise<Array>} react-select options.
   */
  const searchProducts = (term) => {
    if (!term || term.length < 2 || !productSearch.url) {
      return Promise.resolve([]);
    }

    const url = `${productSearch.url}?action=woocommerce_json_search_products_and_variations&security=${encodeURIComponent(
      productSearch.nonce || '',
    )}&term=${encodeURIComponent(term)}`;

    return fetch(url, { credentials: 'same-origin' })
      .then((res) => (res.ok ? res.json() : {}))
      .then((data) =>
        Object.keys(data || {}).map((id) => ({
          value: parseInt(id, 10),
          label: String(data[id]).replace(/&(#\d+|[a-z]+);/gi, (m) => {
            const el = document.createElement('textarea');
            el.innerHTML = m;
            return el.value;
          }),
        })),
      )
      .catch(() => []);
  };

  const addRow = () => setRows((prev) => [...prev, emptyRow(firstCondition)]);

  const deleteRow = (index) => {
    setRows((prev) => prev.filter((_, i) => i !== index));
    // Every selection above the removed row shifts down by one; without this
    // the checkboxes end up pointing at the wrong rules.
    setSelectedRows((prev) =>
      prev.filter((i) => i !== index).map((i) => (i > index ? i - 1 : i)),
    );
  };

  const duplicateRow = (index) =>
    setRows((prev) => {
      const copy = { ...prev[index], multi: [...prev[index].multi] };
      const next = [...prev];
      next.splice(index + 1, 0, copy);
      return next;
    });

  const deleteSelectedRows = () => {
    setRows((prev) => prev.filter((_, i) => !selectedRows.includes(i)));
    setSelectedRows([]);
  };

  const duplicateSelectedRows = () => {
    const duplicates = selectedRows
      .filter((index) => rows[index])
      .map((index) => ({ ...rows[index], multi: [...rows[index].multi] }));
    setRows((prev) => [...prev, ...duplicates]);
  };

  const handleCheckboxChange = (index) =>
    setSelectedRows((prev) =>
      prev.includes(index) ? prev.filter((i) => i !== index) : [...prev, index],
    );

  const importRules = (rules) => {
    setRows(rules.map((row) => normalizeRow(row, firstCondition)));
    setSelectedRows([]);
  };

  const allSelected = rows.length > 0 && selectedRows.length === rows.length;
  const enabledCount = rows.filter((row) => row.enabled).length;

  /**
   * The inputs a given condition needs, beside its cost.
   *
   * @param {Object} row   Rule row.
   * @param {number} index Row index.
   * @return {ReactElement|null} The inputs.
   */
  const renderConditionInputs = (row, index) => {
    if (MULTIPLIER_CONDITIONS.includes(row.condition)) {
      return (
        <span className="tpsm-rule-hint">
          × {row.condition === 'tpsm-per-weight-unit' ? weightUnit : i18n.value || 'qty'}
        </span>
      );
    }

    if (OPERATOR_CONDITIONS.includes(row.condition)) {
      return (
        <>
          <select
            className="tpsm-input tpsm-input-select"
            value={row.equal || 'equals'}
            onChange={(e) => handleRowChange(index, 'equal', e.target.value)}
          >
            {operators.map((op) => (
              <option key={op.value} value={op.value}>
                {op.label}
              </option>
            ))}
          </select>
          <input
            className="tpsm-input"
            type="number"
            placeholder={i18n.value || 'Value'}
            value={row.value}
            onChange={(e) => handleRowChange(index, 'value', e.target.value)}
          />
        </>
      );
    }

    if (RANGE_CONDITIONS.includes(row.condition)) {
      let unit = parse(currencySymbol || '');
      if (row.condition === 'tpsm-total-weight') {
        unit = weightUnit;
      } else if (row.condition === 'tpsm-cart-volume') {
        unit = '';
      }
      return (
        <>
          <span className="tpsm-input-group">
            <span className="tpsm-input-affix">{unit}</span>
            <input
              className="tpsm-input"
              type="number"
              placeholder={i18n.min || 'Min'}
              value={row.min}
              onChange={(e) => handleRowChange(index, 'min', e.target.value)}
            />
          </span>
          <span className="tpsm-input-group">
            <span className="tpsm-input-affix">{unit}</span>
            <input
              className="tpsm-input"
              type="number"
              placeholder={i18n.max || 'Max'}
              value={row.max}
              onChange={(e) => handleRowChange(index, 'max', e.target.value)}
            />
          </span>
        </>
      );
    }

    if (MULTI_CONDITIONS.includes(row.condition)) {
      const byCondition = {
        'tpsm-product-category': [categoryOptions, i18n.selectCategories],
        'tpsm-product-tag': [tagOptions, i18n.selectTags],
        'tpsm-shipping-class': [classOptions, i18n.selectClasses],
      };
      const [options, placeholder] = byCondition[row.condition] || [classOptions, ''];
      return (
        <Select
          className="tpsm-rule-multi"
          classNamePrefix="tpsm-select"
          options={options}
          isMulti
          placeholder={placeholder}
          value={options.filter((opt) => row.multi.includes(opt.value))}
          onChange={(selected) => handleMultiSelectChange(index, selected)}
        />
      );
    }

    // Specific products: searched live through WooCommerce's own endpoint, so
    // this works on catalogs far too large to localise up front.
    if (row.condition === 'tpsm-product') {
      return (
        <AsyncSelect
          className="tpsm-rule-multi"
          classNamePrefix="tpsm-select"
          isMulti
          cacheOptions
          defaultOptions={false}
          placeholder={i18n.searchProducts}
          loadOptions={searchProducts}
          noOptionsMessage={({ inputValue }) =>
            inputValue ? i18n.noResults : i18n.typeToSearch
          }
          value={(row.multi || []).map((id) => ({
            value: id,
            label: (row.multiLabels && row.multiLabels[id]) || `#${id}`,
          }))}
          onChange={(selected) => handleProductChange(index, selected)}
        />
      );
    }

    if (TEXT_LIST_CONDITIONS.includes(row.condition)) {
      const placeholders = {
        'tpsm-postcode': i18n.postcodes,
        'tpsm-state': i18n.states,
        'tpsm-coupon': i18n.coupons,
      };
      return (
        <input
          className="tpsm-input tpsm-input-wide"
          type="text"
          placeholder={placeholders[row.condition]}
          value={row.value}
          onChange={(e) => handleRowChange(index, 'value', e.target.value)}
        />
      );
    }

    return null;
  };

  return (
    <div className="tpsm-rules">
      <ImportExport rows={rows} onImport={importRules} i18n={i18n} />

      <div className="tpsm-rules-toolbar">
        <label className="tpsm-check">
          <input
            type="checkbox"
            checked={allSelected}
            onChange={(e) => setSelectedRows(e.target.checked ? rows.map((_, i) => i) : [])}
          />
          <span>
            {selectedRows.length
              ? `${selectedRows.length} / ${rows.length}`
              : (i18n.ruleCount || '%d rules').replace('%d', rows.length)}
          </span>
        </label>

        <span className="tpsm-rules-meta">
          {enabledCount !== rows.length && (
            <span className="tpsm-badge tpsm-badge-muted">
              {rows.length - enabledCount} {i18n.disabled || 'disabled'}
            </span>
          )}
          <span className="tpsm-rules-note">{i18n.totalNote}</span>
        </span>

        <span className="tpsm-rules-toolbar-actions">
          <button
            type="button"
            className="tpsm-btn"
            disabled={!selectedRows.length}
            onClick={duplicateSelectedRows}
          >
            {i18n.duplicate}
          </button>
          <button
            type="button"
            className="tpsm-btn tpsm-btn-danger"
            disabled={!selectedRows.length}
            onClick={deleteSelectedRows}
          >
            {i18n.deleteSelected}
          </button>
        </span>
      </div>

      {rows.length === 0 ? (
        <div className="tpsm-empty">
          <p>{i18n.noRules}</p>
          <button type="button" className="tpsm-btn tpsm-btn-primary" onClick={addRow}>
            {i18n.addRule}
          </button>
        </div>
      ) : (
        <ul className="tpsm-rule-list">
          {rows.map((row, index) => (
            <li
              key={index}
              className={`tpsm-rule ${row.enabled ? '' : 'is-disabled'} ${
                selectedRows.includes(index) ? 'is-selected' : ''
              }`}
            >
              <div className="tpsm-rule-select">
                <input
                  type="checkbox"
                  aria-label={`Select rule ${index + 1}`}
                  checked={selectedRows.includes(index)}
                  onChange={() => handleCheckboxChange(index)}
                />
                <span className="tpsm-rule-index">{index + 1}</span>
              </div>

              <div className="tpsm-rule-main">
                <div className="tpsm-rule-top">
                  <input
                    className="tpsm-input tpsm-rule-label"
                    type="text"
                    placeholder={i18n.labelPlaceholder}
                    value={row.label}
                    onChange={(e) => handleRowChange(index, 'label', e.target.value)}
                  />

                  <label className="tpsm-toggle" title={i18n.enabled}>
                    <input
                      type="checkbox"
                      checked={row.enabled}
                      onChange={(e) => handleRowChange(index, 'enabled', e.target.checked)}
                    />
                    <span className="tpsm-toggle-track" aria-hidden="true" />
                    <span className="tpsm-toggle-text">
                      {row.enabled ? i18n.enabled : i18n.disabled}
                    </span>
                  </label>
                </div>

                <div className="tpsm-rule-body">
                  <select
                    className="tpsm-input tpsm-input-select tpsm-rule-condition"
                    value={row.condition}
                    onChange={(e) => handleRowChange(index, 'condition', e.target.value)}
                  >
                    {Object.keys(conditionGroups).map((group) => (
                      <optgroup label={group} key={group}>
                        {(conditionGroups[group] || [])
                          .filter((slug) => conditions[slug])
                          .map((slug) => (
                            <option key={slug} value={slug}>
                              {conditions[slug]}
                            </option>
                          ))}
                      </optgroup>
                    ))}
                  </select>

                  {renderConditionInputs(row, index)}

                  <span className="tpsm-rule-cost">
                    <span className="tpsm-input-affix">{parse(currencySymbol || '')}</span>
                    <input
                      className="tpsm-input"
                      type="number"
                      step="0.01"
                      value={row.cost}
                      onChange={(e) => handleRowChange(index, 'cost', e.target.value)}
                      placeholder="0.00"
                    />
                  </span>

                  <span className="tpsm-rule-actions">
                    <button
                      type="button"
                      className="tpsm-icon-btn"
                      title={i18n.duplicate}
                      onClick={() => duplicateRow(index)}
                    >
                      <svg viewBox="0 0 16 16" width="15" height="15" fill="none" stroke="currentColor" strokeWidth="1.4" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
                        <rect x="5.5" y="5.5" width="8" height="8" rx="1.5" />
                        <path d="M10.5 3.5h-7a1 1 0 0 0-1 1v7" />
                      </svg>
                    </button>
                    <button
                      type="button"
                      className="tpsm-icon-btn tpsm-icon-btn-danger"
                      title={i18n.deleteSelected}
                      onClick={() => deleteRow(index)}
                    >
                      <svg viewBox="0 0 16 16" width="15" height="15" fill="none" stroke="currentColor" strokeWidth="1.4" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
                        <path d="M2.5 4.5h11M6 4.5V3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v1.5M4 4.5l.6 8a1 1 0 0 0 1 .9h4.8a1 1 0 0 0 1-.9l.6-8" />
                      </svg>
                    </button>
                  </span>
                </div>

                {conditionHelp[row.condition] && (
                  <p className="tpsm-rule-help">{conditionHelp[row.condition]}</p>
                )}
              </div>
            </li>
          ))}
        </ul>
      )}

      {rows.length > 0 && (
        <div className="tpsm-rules-footer">
          <button type="button" className="tpsm-btn tpsm-btn-primary" onClick={addRow}>
            + {i18n.addRule}
          </button>
        </div>
      )}
    </div>
  );
}

export default Admin;

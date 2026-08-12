import React, { useMemo, useRef, useState } from 'react';

/**
 * Import / export panel for the shipping rules.
 *
 * Replaces the single-line text field that used to hold raw JSON: rules can now
 * be copied to the clipboard, pasted back, downloaded as a .json file, or
 * imported from one — either replacing the current rules or appending to them.
 *
 * @param {Object}   props
 * @param {Array}    props.rows      Current rule rows.
 * @param {Function} props.onImport  Called with the parsed rule array.
 * @param {Object}   props.i18n      Translated strings.
 * @return {ReactElement} The panel.
 */
function ImportExport({ rows, onImport, i18n }) {
  const [open, setOpen] = useState(false);
  const [draft, setDraft] = useState('');
  const [mode, setMode] = useState('replace');
  const [status, setStatus] = useState(null);
  const fileInput = useRef(null);
  const textarea = useRef(null);

  const exported = useMemo(() => JSON.stringify(rows, null, 2), [rows]);

  const say = (text, tone = 'info') => {
    setStatus({ text, tone });
    window.clearTimeout(say.timer);
    say.timer = window.setTimeout(() => setStatus(null), 4000);
  };

  /**
   * Pull an array of rules out of arbitrary pasted text.
   *
   * @param {string} text Raw text.
   * @return {Array|null} Rules, or null when the text is not usable.
   */
  const parseRules = (text) => {
    try {
      const parsed = JSON.parse(text);
      if (!Array.isArray(parsed)) {
        return null;
      }
      // Every entry has to look like a rule, or this is someone else's JSON.
      const valid = parsed.filter(
        (row) => row && typeof row === 'object' && typeof row.condition === 'string',
      );
      return valid.length ? valid : null;
    } catch (e) {
      return null;
    }
  };

  const applyText = (text) => {
    const rules = parseRules(text);

    if (!rules) {
      say(i18n.invalidJson, 'error');
      return;
    }

    onImport(mode === 'append' ? [...rows, ...rules] : rules);
    say((i18n.imported || 'Imported %d rules.').replace('%d', rules.length), 'success');
    setDraft('');
  };

  const copy = async () => {
    try {
      await navigator.clipboard.writeText(exported);
      say(i18n.copied, 'success');
    } catch (e) {
      // Clipboard API needs a secure context; fall back to selecting the text.
      if (textarea.current) {
        setOpen(true);
        textarea.current.value = exported;
        textarea.current.select();
      }
      say(i18n.clipboardFailed, 'error');
    }
  };

  const paste = async () => {
    try {
      const text = await navigator.clipboard.readText();
      applyText(text);
    } catch (e) {
      setOpen(true);
      say(i18n.clipboardFailed, 'error');
    }
  };

  const download = () => {
    const blob = new Blob([exported], { type: 'application/json' });
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.download = 'shipping-manager-rules.json';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    URL.revokeObjectURL(url);
  };

  const readFile = (event) => {
    const file = event.target.files && event.target.files[0];
    if (!file) {
      return;
    }
    const reader = new FileReader();
    reader.onload = () => applyText(String(reader.result));
    reader.onerror = () => say(i18n.invalidJson, 'error');
    reader.readAsText(file);
    // Allow re-picking the same file.
    event.target.value = '';
  };

  return (
    <div className="tpsm-io">
      <div className="tpsm-io-bar">
        <div className="tpsm-io-title">
          <span className="tpsm-io-heading">{i18n.importExport}</span>
          <span className="tpsm-io-count">
            {(i18n.ruleCount || '%d rules').replace('%d', rows.length)}
          </span>
        </div>

        <div className="tpsm-io-actions">
          <button type="button" className="tpsm-btn" onClick={copy}>
            {i18n.copy}
          </button>
          <button type="button" className="tpsm-btn" onClick={paste}>
            {i18n.paste}
          </button>
          <button type="button" className="tpsm-btn" onClick={download}>
            {i18n.download}
          </button>
          <button
            type="button"
            className="tpsm-btn"
            onClick={() => fileInput.current && fileInput.current.click()}
          >
            {i18n.importFile}
          </button>
          <button
            type="button"
            className="tpsm-btn tpsm-btn-quiet"
            aria-expanded={open}
            onClick={() => setOpen((v) => !v)}
          >
            {open ? '▲' : '▼'}
          </button>
          <input
            ref={fileInput}
            type="file"
            accept="application/json,.json"
            className="tpsm-io-file"
            onChange={readFile}
          />
        </div>
      </div>

      {status && (
        <p className={`tpsm-io-status is-${status.tone}`} role="status">
          {status.text}
        </p>
      )}

      {open && (
        <div className="tpsm-io-body">
          <div className="tpsm-io-modes">
            <label>
              <input
                type="radio"
                name="tpsm-io-mode"
                checked={mode === 'replace'}
                onChange={() => setMode('replace')}
              />
              {i18n.replaceAll}
            </label>
            <label>
              <input
                type="radio"
                name="tpsm-io-mode"
                checked={mode === 'append'}
                onChange={() => setMode('append')}
              />
              {i18n.appendRules}
            </label>
          </div>

          <textarea
            ref={textarea}
            className="tpsm-io-textarea"
            spellCheck="false"
            value={draft || exported}
            onChange={(e) => setDraft(e.target.value)}
          />

          <button
            type="button"
            className="tpsm-btn tpsm-btn-primary"
            onClick={() => applyText(draft || exported)}
          >
            {i18n.importFile}
          </button>
        </div>
      )}
    </div>
  );
}

export default ImportExport;

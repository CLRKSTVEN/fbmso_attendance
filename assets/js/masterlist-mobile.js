(function () {
  function applyDataLabels(table) {
    if (!table) return;
    const headers = Array.from(table.querySelectorAll('thead th')).map((th) =>
      th.textContent.replace(/\s+/g, ' ').trim()
    );

    table.querySelectorAll('tbody tr').forEach((row) => {
      Array.from(row.children).forEach((cell, idx) => {
        const label = headers[idx] || '';
        if (label) {
          cell.setAttribute('data-label', label);
        } else {
          cell.removeAttribute('data-label');
        }
      });
    });
  }

  function observeTable(table) {
    if (!table || typeof MutationObserver === 'undefined') return;
    const tbody = table.tBodies && table.tBodies[0];
    if (!tbody) return;

    const observer = new MutationObserver(() => applyDataLabels(table));
    observer.observe(tbody, { childList: true, subtree: true });
  }

  function initMasterlistTables() {
    if (!document.body.classList.contains('masterlist-page')) return;

    const tables = Array.from(
      document.querySelectorAll('.masterlist-page table')
    );

    tables.forEach((table) => {
      table.classList.add('masterlist-table-ready');
      applyDataLabels(table);
      observeTable(table);
    });
  }

  if (document.readyState !== 'loading') {
    initMasterlistTables();
  } else {
    document.addEventListener('DOMContentLoaded', initMasterlistTables);
  }
})();

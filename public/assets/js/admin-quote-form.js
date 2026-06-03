(function () {
    var body = document.getElementById('quote-items-body');
    var tpl = document.getElementById('quote-item-row-tpl');
    var taxEl = document.getElementById('quote-tax');
    var feeEl = document.getElementById('quote-fee');
    if (!body || !tpl) return;

    function reindexRows() {
        body.querySelectorAll('.quote-item-row').forEach(function (row, idx) {
            row.querySelectorAll('[name^="items["]').forEach(function (input) {
                var field = input.name.replace(/items\[\d+\]/, '');
                input.name = 'items[' + idx + ']' + field;
            });
        });
    }

    function lineTotal(row) {
        var qty = parseFloat(row.querySelector('.quote-qty')?.value || 0);
        var unit = parseFloat(row.querySelector('.quote-unit')?.value || 0);
        var total = Math.max(0, qty) * Math.max(0, unit);
        var cell = row.querySelector('.quote-line-total');
        if (cell) cell.textContent = total.toFixed(2);
        return total;
    }

    function recalc() {
        var subtotal = 0;
        body.querySelectorAll('.quote-item-row').forEach(function (row) {
            subtotal += lineTotal(row);
        });
        var tax = parseFloat(taxEl?.value || 0);
        var fee = parseFloat(feeEl?.value || 0);
        var grand = subtotal + tax + fee;
        var subEl = document.getElementById('quote-subtotal');
        var grandEl = document.getElementById('quote-grand-total');
        if (subEl) subEl.textContent = subtotal.toFixed(2);
        if (grandEl) grandEl.textContent = grand.toFixed(2);
    }

    function bindRow(row) {
        row.querySelectorAll('.quote-qty, .quote-unit').forEach(function (el) {
            el.addEventListener('input', recalc);
        });
        var remove = row.querySelector('.quote-remove-item');
        if (remove) {
            remove.addEventListener('click', function () {
                if (body.querySelectorAll('.quote-item-row').length <= 1) return;
                row.remove();
                reindexRows();
                recalc();
            });
        }
    }

    document.getElementById('quote-add-item')?.addEventListener('click', function () {
        var clone = tpl.content.cloneNode(true);
        var row = clone.querySelector('tr');
        body.appendChild(row);
        reindexRows();
        var idx = body.querySelectorAll('.quote-item-row').length - 1;
        row.querySelectorAll('[data-name]').forEach(function (input) {
            input.name = 'items[' + idx + '][' + input.getAttribute('data-name') + ']';
            input.removeAttribute('data-name');
            if (input.classList.contains('quote-qty')) input.classList.add('quote-qty');
            if (input.classList.contains('quote-unit')) input.classList.add('quote-unit');
        });
        bindRow(row);
        recalc();
    });

    body.querySelectorAll('.quote-item-row').forEach(bindRow);
    taxEl?.addEventListener('input', recalc);
    feeEl?.addEventListener('input', recalc);
    recalc();
})();

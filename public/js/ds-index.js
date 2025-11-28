document.querySelectorAll('.ds-table tbody tr:not(.empty-row)').forEach(row => {
    row.addEventListener('mouseenter', function () {
        this.style.backgroundColor = '#e8f5e9';
    });
    row.addEventListener('mouseleave', function () {
        this.style.backgroundColor = '';
    });
});

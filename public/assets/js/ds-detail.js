function filterStudents() {
    const searchInput = document.getElementById('student-search').value.toLowerCase();
    const tbody = document.querySelector('.students-table tbody');
    const rows = tbody.getElementsByTagName('tr');

    for (let i = 0; i < rows.length; i++) {
        const row = rows[i];
        if (row.classList.contains('empty-row')) continue;

        const text = row.textContent.toLowerCase();

        if (text.includes(searchInput)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    }
}

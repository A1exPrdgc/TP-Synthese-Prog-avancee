function filterStudents() {
    const searchInput = document.getElementById('student-search').value.toLowerCase();
    const tbody = document.getElementById('students-tbody');
    const rows = tbody.getElementsByTagName('tr');

    for (let i = 0; i < rows.length; i++) {
        const row = rows[i];
        const text = row.textContent.toLowerCase();

        if (text.includes(searchInput)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    }
}

function toggleJustified(checkbox) {
    const studentId = checkbox.id.replace('absent_', '');
    const justifiedCheckbox = document.getElementById('justifie_' + studentId);

    if (checkbox.checked) {
        justifiedCheckbox.disabled = false;
    } else {
        justifiedCheckbox.disabled = true;
        justifiedCheckbox.checked = false;
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const tbody = document.getElementById('students-tbody');
    const absentCheckboxes = tbody.querySelectorAll('[id^="absent_"]');

    absentCheckboxes.forEach(function (checkbox) {
        const studentId = checkbox.id.replace('absent_', '');
        const justifiedCheckbox = document.getElementById('justifie_' + studentId);

        if (checkbox.checked) {
            justifiedCheckbox.disabled = false;
        } else {
            justifiedCheckbox.disabled = true;
        }
    });
});

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

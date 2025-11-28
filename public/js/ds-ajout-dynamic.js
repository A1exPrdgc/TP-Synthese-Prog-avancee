document.getElementById('semester').addEventListener('change', function () {
    const semester = this.value;
    const baseUrl = document.body.getAttribute('data-base-url') || '';

    fetch(baseUrl + 'DS/getResourcesBySemester?semester=' + encodeURIComponent(semester))
        .then(response => response.json())
        .then(data => {
            const resourceSelect = document.getElementById('resource');
            resourceSelect.innerHTML = '';

            if (data.length > 0) {
                data.forEach(resource => {
                    const option = document.createElement('option');
                    option.value = resource.code;
                    option.textContent = resource.nom;
                    resourceSelect.appendChild(option);
                });

                resourceSelect.dispatchEvent(new Event('change'));
            } else {
                const option = document.createElement('option');
                option.value = '';
                option.textContent = 'Aucune ressource disponible';
                resourceSelect.appendChild(option);

                const teacherSelect = document.getElementById('teacher');
                teacherSelect.innerHTML = '<option value="">Aucun professeur disponible</option>';
            }
        })
        .catch(error => {
            console.error('Erreur lors du chargement des ressources:', error);
        });

    const keyword = document.getElementById('student-search').value;
    fetch(baseUrl + 'DS/getStudentsBySemester?semester=' + encodeURIComponent(semester) + '&keyword=' + encodeURIComponent(keyword))
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('students-tbody');
            tbody.innerHTML = '';

            if (data.length > 0) {
                data.forEach(student => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${escapeHtml(student.id)}</td>
                        <td>${escapeHtml(student.nom)}</td>
                        <td>${escapeHtml(student.prenom)}</td>
                        <td>${escapeHtml(student.classe)}</td>
                        <td>
                            <input type="checkbox" name="absent[${escapeHtml(student.id)}]" value="1" id="absent_${escapeHtml(student.id)}" onchange="toggleJustified(this)">
                        </td>
                        <td>
                            <input type="checkbox" name="justifie[${escapeHtml(student.id)}]" value="1" id="justifie_${escapeHtml(student.id)}" disabled>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            } else {
                const row = document.createElement('tr');
                row.innerHTML = '<td colspan="6" style="text-align:center;">Aucun étudiant trouvé</td>';
                tbody.appendChild(row);
            }
        })
        .catch(error => {
            console.error('Erreur lors du chargement des étudiants:', error);
        });
});

document.getElementById('resource').addEventListener('change', function () {
    const resource = this.value;
    const baseUrl = document.body.getAttribute('data-base-url') || '';

    if (!resource) {
        const teacherSelect = document.getElementById('teacher');
        teacherSelect.innerHTML = '<option value="">Aucun professeur disponible</option>';
        return;
    }

    fetch(baseUrl + 'DS/getTeachersByResource?resource=' + encodeURIComponent(resource))
        .then(response => response.json())
        .then(data => {
            const teacherSelect = document.getElementById('teacher');
            teacherSelect.innerHTML = '';

            if (data.length > 0) {
                data.forEach(teacher => {
                    const option = document.createElement('option');
                    option.value = teacher.nom;
                    option.textContent = teacher.nom;
                    teacherSelect.appendChild(option);
                });
            } else {
                const option = document.createElement('option');
                option.value = '';
                option.textContent = 'Aucun professeur disponible';
                teacherSelect.appendChild(option);
            }
        })
        .catch(error => {
            console.error('Erreur lors du chargement des professeurs:', error);
        });
});

document.addEventListener('DOMContentLoaded', () => {
    const semesterSelect = document.getElementById('semester-select');
    const resourcesList = document.getElementById('resources-list');
    const baseUrl = document.body.getAttribute('data-base-url');

    function loadResourcesForSemester(semester) {
        fetch(`${baseUrl}profil/getResourcesBySemester?semester=${encodeURIComponent(semester)}`)
            .then(response => response.json())
            .then(resources => {
                resourcesList.innerHTML = ''; 

                if (resources.length === 0) {
                    resourcesList.innerHTML = '<li>Aucune ressource disponible pour ce semestre.</li>';
                    return;
                }

                resources.forEach(resource => {
                    const li = document.createElement('li');
                    const label = document.createElement('label');

                    const checkbox = document.createElement('input');
                    checkbox.type = 'checkbox';
                    checkbox.name = 'resources[]';
                    checkbox.value = resource.code;

                    // coche si dans teacherResources
                    if (teacherResources.includes(resource.code)) {
                        checkbox.checked = true;
                    }

                    label.appendChild(checkbox);
                    label.appendChild(document.createTextNode(` ${resource.code} - ${resource.nom}`));

                    li.appendChild(label);
                    resourcesList.appendChild(li);
                });
            })
            .catch(err => {
                console.error('Erreur chargement ressources:', err);
            });
    }

    semesterSelect.addEventListener('change', () => {
        loadResourcesForSemester(semesterSelect.value);
    });

    // Chargement initial
    loadResourcesForSemester(semesterSelect.value);
});

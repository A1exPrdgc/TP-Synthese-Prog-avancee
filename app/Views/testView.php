<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
Liste des DS
<?= $this->endSection() ?>


<?= $this->section('navbarTitle') ?>
MySGRDS | Liste des DS

<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<!-- Style spécifique si besoin -->
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="search-container">
    <div class="search-columns">
        <!-- Colonne 1: Recherche et Filtres -->
        <div class="search-col">
            <div class="search-input-group">
                <span class="search-icon"></span>
                <input type="text" placeholder="Rechercher">
            </div>
            <div class="filter-row">
                <select class="filter-select">
                    <option>Ressource</option>
                </select>
                <select class="filter-select">
                    <option>Semestre</option>
                </select>
                <select class="filter-select">
                    <option>Enseignant</option>
                </select>
            </div>
        </div>

        <!-- Colonne 2: Dates -->
        <div class="search-col align-items-end">
            <div class="date-group"> 
                <label>Debut :</label>
                <input type="text" class="date-input" placeholder="01/01/26">
            </div>
            <div class="date-group">
                <label>Fin :</label>
                <input type="text" class="date-input" placeholder="01/01/27">
            </div>
        </div>

        <!-- Colonne 3: Boutons -->
        <div class="search-row">
            <button class="btn-filter-reset">Retirer les filtres</button>
            <button class="btn-filter-action">Rechercher</button>
        </div>
    </div>

</div>

<div class="table-container">
    
    <div class="table-underground">
        <table class="custom-table">
            <thead>
            <tr>
                <th>Ressource</th>
                <th>Enseignant</th>
                <th>Date</th>
                <th>Durée</th>
                <th>Type</th>
                <th>Nombre absences</th>
                <th>Etat</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>R5.01</td>
                <td>ISUVI MYRA ONDISO</td>
                <td>R5.01</td>
                <td>R5.01</td>
                <td>R5.01</td>
                <td>R5.01</td>
                <td>Rattraper</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            </tbody>
        </table>
        <div class="pagination-container">
            <a href="#" class="pagination-btn"><<</a>
            <a href="#" class="pagination-btn"><</a>
            <span style="font-weight:bold; font-size:1.2rem; margin:0 5px;">1</span>
            <span style="font-weight:bold; font-size:1.2rem; margin:0 5px;">2</span>
            <a href="#" style="font-weight:bold; font-size:1.2rem; margin:0 5px;" class="active">3</a>
            <span style="font-weight:bold; font-size:1.2rem; margin:0 5px;">4</span>
            <span style="font-weight:bold; font-size:1.2rem; margin:0 5px;">5</span>
            <a href="#" class="pagination-btn">></a>
            <a href="#" class="pagination-btn">>></a>
        </div>
    </div>
 

</div>

<!-- Pagination -->


<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Scripts JS éventuels -->
<?= $this->endSection() ?>

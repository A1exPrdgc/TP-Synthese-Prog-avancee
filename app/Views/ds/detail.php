<?= $this->extend('layouts/default') ?>
<?= $this->section('pageType'); ?>ds<?= $this->endSection(); ?>

<?= $this->section('title') ?>
Ajouter DS
<?= $this->endSection() ?>

<?= $this->section('navbarTitle') ?>
MySGRDS | Afficher DS
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<link href="<?= base_url('assets/css/ds-ajout.css') ?>" rel="stylesheet" />
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="ds-ajout-container">
    <div class="ds-ajout-layout">
        <!-- Colonne gauche: Évaluation -->
        <div class="evaluation-section">
            <h2 class="section-title">Évaluation</h2>
            
            <div class="form-group">
                <label>Semestre</label>
                <!-- afffiche un seul semestre, selection désactivée -->
                <select class="form-select" disabled>
                    <option>S5</option>
            </div>

            <div class="form-group">
                <label>Ressource</label>
                <select class="form-select">
                    <option>R5.01 Initiation au management d'une équipe de projet informatique</option>
                </select>
            </div>

            <div class="form-group">
                <label>Professeur</label>
                <select class="form-select" disabled>
                    <option>Isuvi Myra Ondiso</option>
                </select>
            </div>

            <div class="form-group">
                <!-- Date de l'évaluation en dure non modifiable sans nouvelle div -->
                <label>Date de l'évaluation</label>
                <div class="date-input-group">
                    <input type="date" class="form-input" value="2024-10-15" disabled>
                    <span class="calendar-icon">&#128197;</span>
                </div>
            </div>

            <div class="form-group">
                <label>Type</label>
                <!-- affiche le type de DS, selection désactivée -->
                <select class="form-select" disabled>
                    <option>Machine</option>
                </select>
            </div>

            <div class="form-group">
                <label>Durée de l'évaluation</label>
                <!-- affiche la durée du DS en dure non modifiable -->
                <input type="time" class="form-input" value="01:30" disabled>
            </div>
        </div>

        <!-- Colonne droite: Étudiants -->
        <div class="students-section">
            <h2 class="section-title">Étudiants</h2>
            
            <div class="search-input-group">
                <input type="text" placeholder="Rechercher">
            </div>

            <div class="students-table-container">
                <table class="students-table">
                    <thead>
                        <tr>
                            <th>Identifiant</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Classe</th>
                            <th>Absent</th>
                            <th>Justifié</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>gc230811</td>
                            <td>GUELLE</td>
                            <td>Clément</td>
                            <td>L2</td>
                            <td><input type="checkbox"></td>
                            <td><input type="checkbox"></td>
                        </tr>
                        <tr>
                            <td>vr232906</td>
                            <td>VIEZ</td>
                            <td>Rémi</td>
                            <td>L2</td>
                            <td><input type="checkbox"></td>
                            <td><input type="checkbox"></td>
                        </tr>
                        <tr>
                            <td>pa230262</td>
                            <td>PRADIGNAC</td>
                            <td>Alexandre</td>
                            <td>L2</td>
                            <td><input type="checkbox"></td>
                            <td><input type="checkbox"></td>
                        </tr>
                        <!-- Lignes vides pour l'exemple -->
                        <tr>
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
                        </tr>
                    </tbody>
                </table>

                <div class="pagination-container">
                    <a href="#" class="pagination-btn"><<</a>
                    <a href="#" class="pagination-btn"><</a>
                    <span style="font-weight:bold; font-size:1.2rem; margin:0 5px;">1</span>
                    <span style="font-weight:bold; font-size:1.2rem; margin:0 5px;">2</span>
                    <span style="font-weight:bold; font-size:1.2rem; margin:0 5px;">3</span>
                    <span style="font-weight:bold; font-size:1.2rem; margin:0 5px;">4</span>
                    <span style="font-weight:bold; font-size:1.2rem; margin:0 5px;">5</span>
                    <a href="#" class="pagination-btn">></a>
                    <a href="#" class="pagination-btn">>></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bouton Ajouter le DS -->
    <div class="submit-container">
        <button type="submit" class="btn-submit">Modifier le DS</button>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Scripts JS éventuels -->
<?= $this->endSection() ?>

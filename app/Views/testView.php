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
<div class="mb-4">
  <form class="d-flex gap-2">
    <input type="text" class="form-control" placeholder="Rechercher">
    <button type="submit" class="btn btn-custom">Rechercher</button>
  </form>
</div>
<div class="table-responsive">
  <table class="table table-bordered">
    <thead class="table-success">
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
        <td>01/01/26</td>
        <td>01:30</td>
        <td>Machine</td>
        <td>2</td>
        <td><button class="btn btn-custom">Rattraper</button></td>
      </tr>
      <!-- Ajoute d'autres lignes selon besoin -->
    </tbody>
  </table>
</div>
<nav aria-label="Pagination">
  <ul class="pagination justify-content-center">
    <li class="page-item"><a class="page-link btn-custom" href="#">«</a></li>
    <li class="page-item active"><span class="page-link">1</span></li>
    <li class="page-item"><a class="page-link btn-custom" href="#">2</a></li>
    <li class="page-item"><a class="page-link btn-custom" href="#">3</a></li>
    <li class="page-item"><a class="page-link btn-custom" href="#">»</a></li>
  </ul>
</nav>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Scripts JS éventuels -->
<?= $this->endSection() ?>

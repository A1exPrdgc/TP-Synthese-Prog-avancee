<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réinitialisation du mot de passe</title>
</head>
<body>

<h1>Nouveau mot de passe</h1>

<?php if (!empty($error)) : ?>
    <p style="color:red;"><?= $error ?></p>
<?php endif; ?>

<form action="<?= site_url('reset-password/' . $token) ?>" method="post">
    <p>
        <label for="password">Nouveau mot de passe :</label><br>
        <input type="password" name="password" id="password" required>
    </p>

    <p>
        <label for="password_confirm">Confirmer le mot de passe :</label><br>
        <input type="password" name="password_confirm" id="password_confirm" required>
    </p>

    <p>
        <button type="submit">Valider</button>
    </p>
</form>

<p>
    <a href="<?= site_url('login') ?>">Retour au login</a>
</p>

</body>
</html>

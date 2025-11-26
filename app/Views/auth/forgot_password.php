<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mot de passe oublié</title>
</head>
<body>

<h1>Mot de passe oublié</h1>

<?php if (!empty($error)) : ?>
    <p style="color:red;"><?= $error ?></p>
<?php endif; ?>

<?php if (!empty($message)) : ?>
    <p style="color:green;"><?= $message ?></p>
<?php endif; ?>

<form action="<?= site_url('forgot-password') ?>" method="post">
    <p>
        <label for="login">Identifiant ou email :</label><br>
        <input type="text" name="login" id="login" required>
    </p>

    <p>
        <button type="submit">Envoyer le lien de réinitialisation</button>
    </p>
</form>

<p>
    <a href="<?= site_url('login') ?>">Retour au login</a>
</p>

</body>
</html>
    
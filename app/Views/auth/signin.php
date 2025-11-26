<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Création de compte</title>
</head>
<body>

<h1>Créer un compte</h1>

<?php if (!empty($error)) : ?>
    <p style="color:red;"><?= esc($error) ?></p>
<?php endif; ?>

<?php if (!empty($message)) : ?>
    <p style="color:green;"><?= esc($message) ?></p>
<?php endif; ?>

<form method="post" action="<?= site_url('signin') ?>">
    <table>
        <tr>
            <th><label for="nom">Nom :</label></th>
            <td><input type="text" name="nom" id="nom" required></td>
        </tr>
        <tr>
            <th><label for="prenom">Prénom :</label></th>
            <td><input type="text" name="prenom" id="prenom" required></td>
        </tr>
        <tr>
            <th><label for="email">Email :</label></th>
            <td><input type="email" name="email" id="email" required></td>
        </tr>
        <tr>
            <th><label for="username">Identifiant universitaire :</label></th>
            <td><input type="text" name="username" id="username" required></td>
        </tr>
        <tr>
            <th><label for="password">Mot de passe :</label></th>
            <td><input type="password" name="password" id="password" required></td>
        </tr>
        <tr>
            <th><label for="password_confirm">Confirmation :</label></th>
            <td><input type="password" name="password_confirm" id="password_confirm" required></td>
        </tr>
        <tr>
            <td colspan="2">
                <button type="submit">Valider</button>
            </td>
        </tr>
    </table>
</form>

<p>
    <a href="<?= site_url('login') ?>">Retour au login</a>
</p>

</body>
</html>

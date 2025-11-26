<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réinitialisation du mot de passe</title>
</head>
<body>

<h1>Nouveau mot de passe</h1>

<?php if (!empty($error)) : ?>
    <p style="color:red;"><?= esc($error) ?></p>
<?php endif; ?>

<form method="post" action="<?= site_url('reset-password/' . $token) ?>">
    <table>
        <tr>
            <th><label for="password">Nouveau mot de passe :</label></th>
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
    <a href="<?= site_url('login'); ?>">Retour au login</a>
</p>

</body>
</html>

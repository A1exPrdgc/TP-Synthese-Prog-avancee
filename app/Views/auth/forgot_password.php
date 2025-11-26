<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mot de passe oublié</title>
</head>
<body>

<h1>Mot de passe oublié</h1>

<?php if (!empty($error)) : ?>
    <p style="color:red;"><?= esc($error) ?></p>
<?php endif; ?>

<?php if (!empty($message)) : ?>
    <p style="color:green;"><?= esc($message) ?></p>
<?php endif; ?>

<form method="post" action="<?= site_url('forgot-password') ?>">
    <table>
        <tr>
            <th><label for="login">Identifiant ou email :</label></th>
            <td><input type="text" name="login" id="login" required></td>
        </tr>
        <tr>
            <td colspan="2">
                <button type="submit">Envoyer le lien de réinitialisation</button>
            </td>
        </tr>
    </table>
</form>

<p>
    <a href="<?= site_url('login') ?>">Retour au login</a>
</p>

</body>
</html>

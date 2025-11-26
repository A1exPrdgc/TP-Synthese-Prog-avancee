<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
</head>
<body>

<h1>Connexion</h1>

<?php if (!empty($error)) : ?>
    <p style="color:red;"><?= esc($error) ?></p>
<?php endif; ?>

<?php if (!empty($message)) : ?>
    <p style="color:green;"><?= esc($message) ?></p>
<?php endif; ?>

<form method="post" action="<?= site_url('login') ?>">
    <table>
        <tr>
            <th><label for="username">Identifiant :</label></th>
            <td><input type="text" name="username" id="username" required></td>
        </tr>
        <tr>
            <th><label for="password">Mot de passe :</label></th>
            <td><input type="password" name="password" id="password" required></td>
        </tr>
        <tr>
            <th>Se souvenir de moi :</th>
            <td>
                <input type="checkbox" name="remember" value="1" id="remember">
                <label for="remember">Créer un cookie</label>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <button type="submit">Se connecter</button>
            </td>
        </tr>
    </table>
</form>

<p>
    <a href="<?= site_url('forgot-password') ?>">Mot de passe oublié ?</a><br>
    <a href="<?= site_url('signin') ?>">Créer un compte</a>
</p>

</body>
</html>

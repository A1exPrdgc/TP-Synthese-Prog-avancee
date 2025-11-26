<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>

<div class="auth-container">
    <p class="welcome-text">Bienvenue sur</p>
    <h1 class="logo-text">MySGRDS</h1>
    <p class="subtitle">Veuillez vous connecter.</p>

    <?php if (!empty($error)) : ?>
        <p class="alert-error"><?= esc($error) ?></p>
    <?php endif; ?>

    <?php if (!empty($message)) : ?>
        <p class="alert-success"><?= esc($message) ?></p>
    <?php endif; ?>

    <form method="post" action="<?= site_url('login') ?>">
        <table>
            <tr>
                <th><label for="username">Identifiant /Mail</label></th>
                <td><input type="text" name="username" id="username" required></td>
            </tr>
            <tr>
                <th><label for="password">Mot de passe</label></th>
                <td><input type="password" name="password" id="password" required></td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: left;">
                    <a href="<?= site_url('forgot-password') ?>">Mot de passe oublié</a>
                </td>
            </tr>
            <tr class="remember-row">
                <th>Se souvenir de moi :</th>
                <td>
                    <input type="checkbox" name="remember" value="1" id="remember">
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <button type="submit">Connexion</button>
                </td>
            </tr>
        </table>
    </form>

    <div class="links">
        <p>Pas encore de compte ?</p>
        <a href="<?= site_url('signin') ?>">Créer un compte</a>
    </div>
</div>

</body>
</html>

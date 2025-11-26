<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mot de passe oublié</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>

<div class="auth-container">
    <h1 class="logo-text">Mot de passe oublié</h1>
    <p class="subtitle">Entrez votre email pour réinitialiser votre mot de passe</p>

    <?php if (!empty($error)) : ?>
        <p class="alert-error"><?= esc($error) ?></p>
    <?php endif; ?>

    <?php if (!empty($message)) : ?>
        <p class="alert-success"><?= esc($message) ?></p>
    <?php endif; ?>

    <form method="post" action="<?= site_url('forgot-password') ?>">
        <table>
            <tr>
                <th><label for="email">Email</label></th>
                <td><input type="email" name="email" id="email" required></td>
            </tr>
            <tr>
                <td colspan="2">
                    <button type="submit">Envoyer</button>
                </td>
            </tr>
        </table>
    </form>

    <div class="links">
        <a href="<?= site_url('login') ?>">Retour à la connexion</a>
    </div>
</div>

</body>
</html>

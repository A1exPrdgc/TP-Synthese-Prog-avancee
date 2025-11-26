<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Création de compte</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>

<div class="auth-container">
    <h1 class="logo-text">Créer votre compte</h1>
    <p class="subtitle">Entrez les informations demandés</p>

    <?php if (!empty($error)) : ?>
        <p class="alert-error"><?= esc($error) ?></p>
    <?php endif; ?>

    <?php if (!empty($message)) : ?>
        <p class="alert-success"><?= esc($message) ?></p>
    <?php endif; ?>

    <form method="post" action="<?= site_url('signin') ?>">
        <table>
            <tr class="inline-fields-row">
                <td>
                    <label for="nom">Nom</label>
                    <input type="text" name="nom" id="nom" required>
                </td>
                <td>
                    <label for="prenom">Prenom</label>
                    <input type="text" name="prenom" id="prenom" required>
                </td>
            </tr>
            <tr>
                <th><label for="email">Email</label></th>
                <td><input type="email" name="email" id="email" required></td>
            </tr>
            <tr>
                <th><label for="username">Identifiant</label></th>
                <td><input type="text" name="username" id="username" required></td>
            </tr>
            <tr>
                <th><label for="password">Mot de passe</label></th>
                <td><input type="password" name="password" id="password" required></td>
            </tr>
            <tr>
                <th><label for="password_confirm">Confirmation de mot de passe</label></th>
                <td><input type="password" name="password_confirm" id="password_confirm" required></td>
            </tr>
            <tr>
                <td colspan="2">
                    <button type="submit">Créer</button>
                </td>
            </tr>
        </table>
    </form>

    <div class="links">
        <p>Déjà un compte ?</p>
        <a href="<?= site_url('login') ?>">Connectez-vous</a>
    </div>
</div>

</body>
</html>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
</head>
<body>
    <h1>Inscription</h1>

    <?php if (!empty($error)) : ?>
        <p style="color:red;"><?= esc($error) ?></p>
    <?php endif; ?>

    <form action="<?= site_url('signin') ?>" method="post">
        <p>
            <label for="nom">Nom :</label><br>
            <input type="text" name="nom" id="nom" required>
        </p>

        <p>
            <label for="prenom">Prénom :</label><br>
            <input type="text" name="prenom" id="prenom" required>
        </p>

        <p>
            <label for="email">Email :</label><br>
            <input type="email" name="email" id="email" required>
        </p>

        <p>
            <label for="username">Identifiant :</label><br>
            <input type="text" name="username" id="username" required>
        </p>

        <p>
            <label for="password">Mot de passe :</label><br>
            <input type="password" name="password" id="password" required>
        </p>

        <p>
            <label for="password_confirm">Confirmation du mot de passe :</label><br>
            <input type="password" name="password_confirm" id="password_confirm" required>
        </p>

        <p>
            <button type="submit">Créer le compte</button>
        </p>
    </form>

    <p>
        <a href="<?= site_url('login') ?>">Déjà inscrit ? Se connecter</a>
    </p>
</body>
</html>

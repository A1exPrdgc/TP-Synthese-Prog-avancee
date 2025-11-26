<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
</head>
<body>
    <h1>Login</h1>

    <?php if (!empty($error)) : ?>
        <p style="color:red;"><?= esc($error) ?></p>
    <?php endif; ?>

    <form action="<?= site_url('login') ?>" method="post">
        <p>
            <label for="username">Identifiant :</label><br>
            <input type="text" name="username" id="username" required>
        </p>

        <p>
            <label for="password">Mot de passe :</label><br>
            <input type="password" name="password" id="password" required>
        </p>

        <p>
            <label>
                <input type="checkbox" name="remember" value="1">
                Se souvenir de moi
            </label>
        </p>

        <p>
            <a href="<?= site_url('forgot-password') ?>">Mot de passe oublié ?</a>
        </p>


        <p>
            <button type="submit">Connexion</button>
        </p>
    </form>

    <p>
        <a href="<?= site_url('signin') ?>">Créer un compte</a>
        <!-- lien "mot de passe oublié" à implémenter plus tard -->
    </p>
</body>
</html>

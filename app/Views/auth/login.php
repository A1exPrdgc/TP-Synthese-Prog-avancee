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

    <?php helper('form'); ?>
    <?= form_open('connecter'); ?>
        <table>
            <tr>
                <th>
                    <?= form_label('Identifiant /Mail', 'username'); ?>
                </th>
                <td>
                    <?= form_input(
                        'username',
                        set_value('username'),
                        'id="username" required'
                    ); ?>
                </td>
            </tr>

            <tr>
                <th>
                    <?= form_label('Mot de passe', 'password'); ?>
                </th>
                <td>
                    <?= form_password(
                        'password',
                        '',
                        'id="password" required'
                    ); ?>
                </td>
            </tr>

            <tr>
                <td colspan="2" style="text-align: left;">
                    <a href="<?= site_url('mot-de-passe-oublie') ?>">Mot de passe oublié</a>
                </td>
            </tr>

            <tr class="remember-row">
                <th>Se souvenir de moi :</th>
                <td>
                    <?= form_checkbox(
                        'remember',
                        '1',
                        set_checkbox('remember', '1', false),
                        'id="remember"'
                    ); ?>
                </td>
            </tr>

            <tr>
                <td colspan="2">
                    <?= form_submit('connexion', 'Connexion'); ?>
                </td>
            </tr>
        </table>
    <?= form_close(); ?>
</div>

</body>
</html>

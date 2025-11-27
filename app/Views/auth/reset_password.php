<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réinitialiser le mot de passe</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>

<div class="auth-container">

    <h1>Réinitialiser le mot de passe</h1>

    <?php if (!empty($error)) : ?>
        <p style="color:red;"><?= esc($error) ?></p>
    <?php endif; ?>

    <?php helper('form'); ?>
    <?= form_open('reset-password/' . esc($token)); ?>

        <table>
            <tr>
                <th>
                    <?= form_label('Nouveau mot de passe', 'password'); ?>
                </th>
                <td>
                    <?= form_password('password', '', 'id="password" required'); ?>
                </td>
            </tr>

            <tr>
                <th>
                    <?= form_label('Confirmation du mot de passe', 'password_confirm'); ?>
                </th>
                <td>
                    <?= form_password('password_confirm', '', 'id="password_confirm" required'); ?>
                </td>
            </tr>

            <tr>
                <td colspan="2">
                    <?= form_submit('valider', 'Valider'); ?>
                </td>
            </tr>
        </table>

    <?= form_close(); ?>

    <p>
        <a href="<?= site_url('login'); ?>">Retour au login</a>
    </p>
</div>

</body>
</html>

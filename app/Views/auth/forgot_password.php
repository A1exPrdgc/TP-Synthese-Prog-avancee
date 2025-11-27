<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mot de passe oublié</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>

<div class="auth-container">

    <h1 class="welcome-text">Mot de passe oublié</h1>

    <?php if (!empty($error)) : ?>
        <p style="color:red;"><?= esc($error) ?></p>
    <?php endif; ?>

    <?php if (!empty($message)) : ?>
        <p style="color:green;"><?= esc($message) ?></p>
    <?php endif; ?>

    <?php helper('form'); ?>
    <?= form_open('forgot-password'); ?>

        <table>
            <tr>
                <th>
                    <?= form_label('Identifiant universitaire ou email', 'login'); ?>
                </th>
                <td>
                    <?= form_input('login', set_value('login'), 'id="login" required'); ?>
                </td>
            </tr>

            <tr>
                <td colspan="2">
                    <?= form_submit('envoyer', 'Envoyer le lien de réinitialisation'); ?>
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

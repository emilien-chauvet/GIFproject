<?php
if (isset($_SESSION['user_id']) && $_SESSION['is_activated'] === 0) : ?>
    <div style="background-color: red; color: white; padding: 10px; text-align: center;">
        Votre compte n'est pas activé. Veuillez vérifier vos e-mails pour le lien d'activation.
    </div>
<?php endif; ?>
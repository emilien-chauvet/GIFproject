<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Paiement Premium</title>
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body>
<div class="container">
    <form id="payment-form" class="payment-form">
        <h2>Paiement Premium</h2>
        <div id="card-element">
            <!-- Un champ de carte sera inséré ici. -->
        </div>
        <div id="error-message" role="alert"></div>
        <button id="submit">Payer</button>
    </form>
</div>

<script>
    const stripe = Stripe('pk_test_51N3euXAHSEL6wsVfmRhEschsc9rcSAIH1zjWwOUNGFbDmoJuosalime7SQXc3CMu7zQ91pIYmhx6ixtWwGMQfkL600y1wyP65h');
    const elements = stripe.elements();
    const cardElement = elements.create('card');
    cardElement.mount('#card-element');

    // Gérer la soumission du formulaire et créer un token
    const form = document.getElementById('payment-form');
    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        const { token, error } = await stripe.createToken(cardElement);

        if (error) {
            // Afficher l'erreur dans l'élément #error-message
            const errorElement = document.getElementById('error-message');
            errorElement.textContent = error.message;
        } else {
            // Envoyer le token à votre serveur pour effectuer le paiement
            const response = await fetch('process_payment.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `stripeToken=${token.id}`,
            });

            const result = await response.json();

            if (result.status === 'success') {
                // Le paiement a réussi, informez l'utilisateur
                alert(result.message);
                // Redirigez l'utilisateur vers une page de confirmation, mettez à jour l'interface utilisateur ou effectuez d'autres actions en fonction de votre application
                window.location.href = 'success.html';
            } else {
                // Le paiement a échoué, affichez un message d'erreur
                const errorElement = document.getElementById('error-message');
                errorElement.textContent = result.message;
            }
        }
    });
</script>
</body>
</html>
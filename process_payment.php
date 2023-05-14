<?php
session_start();
include "db.php";
$user_id = $_SESSION['user_id'];

require_once 'stripe_config.php';

// Récupérez le token de paiement envoyé par le formulaire
$token = $_POST['stripeToken'];
$amount = 5000; // Montant en centimes (50.00 euros)
$currency = 'eur';
$description = 'Abonnement Premium';

try {
    // Créez un paiement avec l'API de Stripe
    $charge = \Stripe\Charge::create([
        'amount' => $amount,
        'currency' => $currency,
        'description' => $description,
        'source' => $token,
    ]);

    // Vérifiez si le paiement a réussi
    if ($charge->status === 'succeeded') {
        // Mettez à jour le statut de l'utilisateur dans la base de données
        // Par exemple, passez de 'user' à 'premium'
        // Assurez-vous d'utiliser des transactions pour garantir la cohérence des données
        $user_id = $_SESSION['user_id']; // Récupérez l'ID de l'utilisateur à partir de la session ou d'une autre source

        $conn->autocommit(false);
        try {
            $update_status = $conn->prepare("UPDATE users SET status = 'premium' WHERE id = ?");
            $update_status->bind_param('i', $user_id);
            $update_status->execute();

            $conn->commit();

            // Envoyez une réponse de succès au client
            http_response_code(200);
            echo json_encode(['status' => 'success', 'message' => 'Paiement réussi.']);
        } catch (Exception $e) {
            $conn->rollback();
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Erreur lors de la mise à jour du statut de l\'utilisateur.']);
        }

    } else {
        // Le paiement a échoué, envoyez une réponse d'erreur au client
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Paiement échoué.']);
    }
} catch (\Stripe\Exception\CardException $e) {
    // Une exception liée à la carte s'est produite, envoyez une réponse d'erreur au client
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
} catch (\Exception $e) {
    // Une autre exception s'est produite, envoyez une réponse d'erreur au client
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Erreur lors du traitement du paiement.']);
}
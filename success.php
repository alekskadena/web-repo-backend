<?php
session_start();
require_once 'db.php';
require __DIR__ . '/vendor/autoload.php';

use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;

header('Content-Type: application/json');

if (!isset($_GET['paymentId']) || !isset($_GET['PayerID'])) {
    echo json_encode(["success" => false, "message" => "Të dhënat e pagesës mungojnë."]);
    exit;
}

$paymentId = $_GET['paymentId'];
$payerId = $_GET['PayerID'];

$apiContext = new ApiContext(
    new OAuthTokenCredential('CLIENT_ID', 'SECRET')
);
$apiContext->setConfig(['mode' => 'sandbox']);

try {
    $payment = Payment::get($paymentId, $apiContext);
    $execution = new PaymentExecution();
    $execution->setPayerId($payerId);
    $payment->execute($execution, $apiContext);

    // Optional: kontrollo nëse është approved
    if ($payment->getState() !== 'approved') {
        throw new Exception("Pagesa nuk është aprovuar.");
    }

    $stmt = $conn->prepare("UPDATE payments SET status = 'completed' WHERE payment_id = ?");
    $stmt->bind_param("s", $paymentId);
    $stmt->execute();

    echo json_encode(["success" => true, "message" => "Pagesa u përfundua me sukses!", "paymentId" => $paymentId]);

} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Gabim gjatë pagesës: " . $e->getMessage()]);
}
?>

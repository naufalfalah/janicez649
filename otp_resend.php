<?php

require_once 'database.php';
require_once 'helper_2chat.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method',
    ]);
    exit();
}

$leadId = isset($_POST['lead_id']) ? $_POST['lead_id'] : null;
if (!$leadId) {
    echo json_encode([
        'success' => false,
        'message' => 'Lead ID is required',
    ]);
    exit();
}

$otp = mt_rand(100000, 999999);

try {
    $stmt = $pdo->prepare("UPDATE user_otp SET otp = :otp, created_at = NOW() WHERE lead_id = :lead_id");
    $stmt->execute([
        ':lead_id' => $leadId,
        ':otp' => $otp,
    ]);

    if ($stmt->rowCount() === 0) {
        echo json_encode([
            'success' => false,
            'message' => 'No OTP record found for this lead',
        ]);
        exit();
    }

    $message = "Your OTP code is: $otp";
    // sendWpMessage($phone, $message);

    error_log("New OTP for $leadId: $otp");

    echo json_encode([
        'success' => true,
        'message' => 'OTP resent successfully',
    ]);
    exit();
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage(),
    ]);
    exit();
}

?>

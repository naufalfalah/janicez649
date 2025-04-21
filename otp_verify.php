<?php

require_once 'database.php';
require_once 'helper_discord.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method',
    ]);
    exit();
}

$leadId = $_POST['lead_id'] ?? '';
$otp = $_POST['otp'] ?? '';

if (empty($leadId) || empty($otp)) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid input'
    ]);
    exit();
}

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("SELECT * FROM user_otp WHERE lead_id = :lead_id AND otp = :otp ORDER BY created_at DESC LIMIT 1");
    $stmt->execute([
        ':lead_id' => $leadId,
        ':otp' => $otp
    ]);
    $stmt->execute();
    $otpData = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$otpData) {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid OTP',
        ]);
        $pdo->rollBack();
        exit();
    }

    $updateStmt = $pdo->prepare("UPDATE leads SET is_verified = 1 WHERE id = :lead_id");
    $updateStmt->bindParam(':lead_id', $leadId);
    $updateStmt->execute();

    $pdo->commit();
    
    $fetchStmt = $pdo->prepare("SELECT * FROM leads WHERE id = :id");
    $fetchStmt->execute([':id' => $leadId]);
    $lead = $fetchStmt->fetch(PDO::FETCH_ASSOC);

    if ($lead) {
        sendLeadToDiscord($lead);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Lead not found after update',
        ]);
        exit();
    }

    echo json_encode([
        'success' => true,
        'message' => 'OTP verified, lead updated',
    ]);
    exit();
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred: ' . $e->getMessage(),
    ]);
    exit();
}

?>

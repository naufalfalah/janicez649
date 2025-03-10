<?php

require_once 'database.php';
require_once 'helper_round_robin.php';
require_once 'helper_2chat.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method',
    ]);
}

// $checkEmail = check_email($_POST['email'], $_POST['ph_number']);
// if (!$checkEmail['isValid']) {
//     echo json_encode([
//         "success" => false,
//         "isValid" => false,
//         "msg" => $checkEmail['msg'],
//     ]);
//     exit();
// }

$stmt = $pdo->prepare("SELECT COUNT(*) AS total, leads.ph_number as ph_number FROM leads WHERE ph_number = :phone");
$stmt->bindParam(":phone", $_POST['ph_number'], PDO::PARAM_STR);
$stmt->execute();

// Fetch result
$result = $stmt->fetch(PDO::FETCH_ASSOC);
if ($result['total'] != 0) {
    echo json_encode([
        "success" => true,
        "message" => "Data saved successfully!",
        "lead_id" => $_POST['lead_id'],
        "OTP" => $_POST['wp_otp']
    ]);
    exit();
}
if ($result['ph_number'] == $_POST['ph_number']) {
    if (isset($_POST['lead_id']) && $_POST['lead_id'] != '') {
        echo json_encode([
            "success" => true,
            "message" => "Data saved successfully!",
            "lead_id" => $_POST['lead_id'],
            "OTP" => $_POST['wp_otp']
        ]);
        exit();
    }
}

$data = $_POST;

$leadFields = ["form_type", "source_url", "name", "ph_number", "email"];
$leadData = array_intersect_key($data, array_flip($leadFields));

foreach ($leadFields as $field) {
    if (!isset($leadData[$field]) || empty($leadData[$field])) {
        die(json_encode([
            "success" => false,
            "message" => "❌ Missing required field: $field"
        ]));
    }
}

try {
    $stmt = $pdo->prepare("INSERT INTO leads (form_type, source_url, firstname, ph_number, email) 
                          VALUES (:form_type, :source_url, :name, :ph_number, :email)");
    $stmt->execute($leadData);
    $leadId = $pdo->lastInsertId(); // Get inserted lead ID

    $extraFields = array_diff_key($data, array_flip($leadFields));
    unset($extraFields['user_otp'], $extraFields['wp_otp'], $extraFields['lead_id']);

    if (!empty($extraFields)) {
        $stmt = $pdo->prepare("INSERT INTO lead_details (lead_id, lead_form_key, lead_form_value) 
                              VALUES (:lead_id, :lead_form_key, :lead_form_value)");
                    
        foreach ($extraFields as $key => $value) {
            $stmt->execute([
                ':lead_id' => $leadId,
                ':lead_form_key' => $key,
                ':lead_form_value' => is_array($value) ? implode('| ', $value) : $value
            ]);
        }
    }

    // Generate OTP
    $otp = rand(100000, 999999);
    $message = 'Your OTP code is: ' . $otp;
    $otp_stmt = $pdo->prepare("INSERT INTO user_otp (lead_id, OTP, is_expired) VALUES (:lead_id, :otp, :is_expired)");
    $otp_stmt->execute([
        ':lead_id' => $leadId,
        ':otp' => $otp,
        ':is_expired' => 0
    ]);
    sendWpMessage('+971551120500', $message);

    echo json_encode([
        "success" => true,
        "message" => "Data saved successfully!",
        "lead_id" => $leadId,
        "OTP" => $otp
    ]);
    exit();
} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => "Database Insert Failed",
        "errorInfo" => $e->getMessage()
    ]);
    exit();
}

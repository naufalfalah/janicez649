<?php

require_once 'config.php';

loadEnv(__DIR__ . '/.env');

function safe_redirect($url) {
    if (!headers_sent()) {
        header("Location: " . getenv('BASE_URL') . $url);
        exit;
    } else {
        die("Cannot redirect, headers already sent.");
    }
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    safe_redirect("");
    exit;
}

$formType = $_POST['form_type'];
$leadId = $_POST['lead_id'];

switch ($formType) {
    case 'condo':
        safe_redirect("report/condo.php?lead_id=" . urlencode($leadId));
        break;

    case 'hdb':
        safe_redirect("report/hdb.php?lead_id=" . urlencode($leadId));
        break;

    case 'landed':
        safe_redirect("report/landed.php?lead_id=" . urlencode($leadId));
        break;

    default:
        safe_redirect("");
        break;
}

?>
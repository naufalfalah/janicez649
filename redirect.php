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

safe_redirect("report/index.html");

?>
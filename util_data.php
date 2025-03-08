<?php

require_once 'database.php';

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

if ($action === 'getProjects') {
    $stmt = $pdo->query("SELECT id, project, street FROM projects");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
} elseif ($action === 'getTowns') {
    $stmt = $pdo->query("SELECT town, json_data FROM towns ORDER BY town");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
} elseif ($action === 'getBlocks') {
    $stmt = $pdo->query("SELECT town_id, blocks FROM blocks ORDER BY blocks");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
} else {
    echo json_encode(["error" => "Invalid action"]);
}

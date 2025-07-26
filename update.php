<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$id = $_POST['id'] ?? '';
if ($id === '') {
    echo json_encode(['success' => false, 'message' => 'Challenge ID is required']);
    exit;
}

$xmlFile = 'challenges.xml';
if (!file_exists($xmlFile)) {
    echo json_encode(['success' => false, 'message' => 'XML file not found']);
    exit;
}

$xml = simplexml_load_file($xmlFile);
$found = false;

foreach ($xml->challenge as $challenge) {
    if ((string)$challenge['id'] === $id) {
        $challenge->title = $_POST['title'];
        $challenge->category = $_POST['category'];
        $challenge->difficulty = $_POST['difficulty'];
        $challenge->estimatedTime = $_POST['estimatedTime'];
        $challenge->description = $_POST['description'];
        $challenge->requirements = $_POST['requirements'];
        $challenge->tags = $_POST['tags'];
        $found = true;
        break;
    }
}

if ($found) {
    $xml->asXML($xmlFile);
    echo json_encode(['success' => true, 'message' => 'Challenge updated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Challenge not found']);
}

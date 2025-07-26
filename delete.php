<?php
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'] ?? '';

if ($id === '') {
    echo json_encode(['success' => false, 'message' => 'ID not provided']);
    exit;
}

$xmlFile = 'challenges.xml';
if (!file_exists($xmlFile)) {
    echo json_encode(['success' => false, 'message' => 'XML file not found']);
    exit;
}

$xml = simplexml_load_file($xmlFile);
$indexToRemove = -1;

foreach ($xml->challenge as $index => $challenge) {
    if ((string)$challenge['id'] === $id) {
        unset($xml->challenge[$index]);
        $xml->asXML($xmlFile);
        echo json_encode(['success' => true, 'message' => 'Challenge deleted successfully']);
        exit;
    }
}

echo json_encode(['success' => false, 'message' => 'Challenge not found']);

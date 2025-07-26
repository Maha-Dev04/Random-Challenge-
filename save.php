<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

// Collect form fields
$title = $_POST['title'] ?? '';
$category = $_POST['category'] ?? '';
$difficulty = $_POST['difficulty'] ?? '';
$estimatedTime = $_POST['estimatedTime'] ?? '';
$description = $_POST['description'] ?? '';
$requirements = $_POST['requirements'] ?? '';
$tags = $_POST['tags'] ?? '';

// Check required fields
if (!$title || !$category || !$difficulty || !$description) {
    echo json_encode([
        'success' => false,
        'message' => 'Missing required fields'
    ]);
    exit;
}

$file = 'challenges.xml';

// Load or create XML
if (!file_exists($file)) {
    $xml = new SimpleXMLElement('<challenges/>');
} else {
    $xml = simplexml_load_file($file);
    if ($xml === false) {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to load XML'
        ]);
        exit;
    }
}

// Generate new unique ID
$maxId = 0;
foreach ($xml->challenge as $ch) {
    $id = (int) $ch['id'];
    if ($id > $maxId) $maxId = $id;
}
$newId = $maxId + 1;

// Add new challenge
$challenge = $xml->addChild('challenge');
$challenge->addAttribute('id', $newId);
$challenge->addChild('title', htmlspecialchars($title));
$challenge->addChild('category', htmlspecialchars($category));
$challenge->addChild('difficulty', htmlspecialchars($difficulty));
$challenge->addChild('estimatedTime', htmlspecialchars($estimatedTime));
$challenge->addChild('description', htmlspecialchars($description));
$challenge->addChild('requirements', htmlspecialchars($requirements));
$challenge->addChild('tags', htmlspecialchars($tags));
$challenge->addChild('savedAt', date('c'));

// Save XML file and confirm success
if ($xml->asXML($file)) {
    echo json_encode([
        'success' => true,
        'message' => 'Challenge added successfully'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Error saving challenge'
    ]);
}
?>

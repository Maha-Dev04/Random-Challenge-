<?php
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized: Admin access required.']);
    exit;
}

$xmlFile = 'challenges.xml';

// Enable internal error handling for libxml
libxml_use_internal_errors(true);

// Load existing XML or create new
if (file_exists($xmlFile)) {
    $xml = simplexml_load_file($xmlFile);
    if ($xml === false) {
        error_log("Failed to load XML file: " . implode(", ", libxml_get_errors()));
        echo json_encode(['success' => false, 'message' => 'Internal server error: cannot load XML.']);
        exit;
    }
} else {
    $xml = new SimpleXMLElement('<challenges></challenges>');
}

// Get POST data and sanitize
$title = isset($_POST['title']) ? trim($_POST['title']) : '';
$category = isset($_POST['category']) ? trim($_POST['category']) : '';
$difficulty = isset($_POST['difficulty']) ? trim($_POST['difficulty']) : '';
$estimatedTime = isset($_POST['estimatedTime']) ? trim($_POST['estimatedTime']) : '';
$description = isset($_POST['description']) ? trim($_POST['description']) : '';
$requirements = isset($_POST['requirements']) ? trim($_POST['requirements']) : '';
$tags = isset($_POST['tags']) ? trim($_POST['tags']) : '';

// Validate required fields
if (empty($title) || empty($category) || empty($difficulty) || empty($description)) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all required fields.']);
    exit;
}

// Add new challenge
$challenge = $xml->addChild('challenge');
$challenge->addChild('title', htmlspecialchars($title));
$challenge->addChild('category', htmlspecialchars($category));
$challenge->addChild('difficulty', htmlspecialchars($difficulty));
$challenge->addChild('estimatedTime', htmlspecialchars($estimatedTime));
$challenge->addChild('description', htmlspecialchars($description));
$challenge->addChild('requirements', htmlspecialchars($requirements));
$challenge->addChild('tags', htmlspecialchars($tags));
$challenge->addChild('savedAt', date('c'));

// Save XML
if ($xml->asXML($xmlFile)) {
    echo json_encode(['success' => true, 'message' => 'Challenge added successfully.']);
} else {
    error_log("Failed to save XML file: " . $xmlFile);
    echo json_encode(['success' => false, 'message' => 'Failed to save challenge.']);
}
?>

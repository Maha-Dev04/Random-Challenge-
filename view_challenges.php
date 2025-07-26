<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
  echo json_encode([]);
  exit;
}

$host = "localhost";
$user = "root";
$password = "";
$database = "challengehub";

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
  echo json_encode([]);
  exit;
}

$stmt = $conn->prepare("SELECT title, category, difficulty, description, tags FROM challenges WHERE user_id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

$challenges = [];
while ($row = $result->fetch_assoc()) {
  $challenges[] = $row;
}

echo json_encode($challenges);
?>

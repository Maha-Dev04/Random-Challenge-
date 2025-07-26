<?php
header('Content-Type: application/json');

// Utility function to sanitize input
function sanitize($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// Fetch and sanitize input values
$name = sanitize($_POST['name'] ?? '');
$email = sanitize($_POST['email'] ?? '');
$subject = sanitize($_POST['subject'] ?? '');
$message = sanitize($_POST['message'] ?? '');

// Validate required fields
if (empty($name) || empty($email) || empty($subject) || empty($message)) {
    echo json_encode([
        'success' => false,
        'message' => 'All fields are required.'
    ]);
    exit;
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid email address.'
    ]);
    exit;
}

// Email details (replace with your real email)
$to = 'singipillimahalakshmi@gmail.com';
$email_subject = "New Contact Form Submission: $subject";
$email_body = "You have received a new message from ChallengeHub Contact Form.\n\n".
              "Name: $name\n".
              "Email: $email\n".
              "Subject: $subject\n".
              "Message:\n$message";

$headers = "From: no-reply@challengehub.com\r\n";
$headers .= "Reply-To: $email\r\n";

// Send email
$mailSuccess = mail($to, $email_subject, $email_body, $headers);

// Respond with JSON
if ($mailSuccess) {
    echo json_encode([
        'success' => true,
        'message' => 'Thank you! Your message has been sent.'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'There was a problem sending your message. Please try again later.'
    ]);
}
?>

<?php
header('Content-Type: application/json');

// Configuration
$to_email = 'pkaduyaw@gmail.com';
$response = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    // Validate inputs
    if (empty($name) || empty($email) || empty($message)) {
        $response['error'] = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['error'] = 'Invalid email address.';
    } elseif (strlen($message) < 1) {
        $response['error'] = 'Message is too short.';
    } elseif (strlen($message) > 1000) {
        $response['error'] = 'Message is too long.';
    } else {
        // Sanitize inputs
        $name = sanitize_text_field($name);
        $email = sanitize_email_field($email);
        $subject = sanitize_text_field($subject);
        $message = sanitize_textarea_field($message);

        // Send mail
        $headers = 'From: ' . $email . "\r\n" .
                    'Reply-To: ' . $email . "\r\n" .
                    'X-Mailer: PHP/' . phpversion();

        if (mail($to_email, $subject, $message, $headers)) {
            $response['success'] = 'Message sent successfully!';
        } else {
            $response['error'] = 'Failed to send the message.';
        }
    }

    echo json_encode($response);
}

// Sanitization functions
function sanitize_text_field($input) {
    return trim(strip_tags($input));
}

function sanitize_email_field($input) {
    return filter_var($input, FILTER_VALIDATE_EMAIL);
}

function sanitize_textarea_field($input) {
    return trim(strip_tags($input));
}
?>

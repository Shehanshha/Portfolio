<?php
header('Content-Type: application/json');

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
$servername = "localhost";
$username = "root";
$password = "Sanyam@2004";
$dbname = "portfolio";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode([
        'status' => 'error',
        'message' => 'Database connection failed',
        'html' => '<div class="error-message"><h3>Error</h3><p>Database connection failed</p></div>'
    ]));
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $name = trim(htmlspecialchars($_POST['name'] ?? ''));
    $email = trim(htmlspecialchars($_POST['email'] ?? ''));
    $message = trim(htmlspecialchars($_POST['message'] ?? ''));

    // Validate inputs
    $errors = [];
    if (empty($name)) $errors[] = "Name is required";
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    if (empty($message)) $errors[] = "Message is required";

    // Return errors if any
    if (!empty($errors)) {
        $html = '<div class="error-message"><h3>Error:</h3>';
        foreach ($errors as $error) {
            $html .= "<p>- $error</p>";
        }
        $html .= '</div>';
        
        echo json_encode([
            'status' => 'error',
            'message' => 'Validation failed',
            'html' => $html,
            'errors' => $errors
        ]);
        exit;
    }

    // Prepare and execute SQL
    $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
    if (!$stmt) {
        $html = '<div class="error-message"><h3>Error</h3><p>Database prepare failed</p></div>';
        echo json_encode([
            'status' => 'error',
            'message' => 'Database prepare failed',
            'html' => $html,
            'error' => $conn->error
        ]);
        exit;
    }

    $stmt->bind_param("sss", $name, $email, $message);
    
    if ($stmt->execute()) {
        $html = '<div class="success-message">
                    <h3>Thank You!</h3>
                    <p>Your message has been sent successfully. I\'ll get back to you soon.</p>
                 </div>';
        
        echo json_encode([
            'status' => 'success',
            'message' => 'Message sent successfully',
            'html' => $html
        ]);
    } else {
        $html = '<div class="error-message">
                    <h3>Error</h3>
                    <p>Sorry, there was an error saving your message.</p>
                 </div>';
        
        echo json_encode([
            'status' => 'error',
            'message' => 'Database insert failed',
            'html' => $html,
            'error' => $stmt->error
        ]);
    }

    $stmt->close();
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method'
    ]);
}

$conn->close();
?>

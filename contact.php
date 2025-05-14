<?php
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
    die("<div class='error-message'>Connection failed: " . $conn->connect_error . "</div>");
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

    // Display errors if any
    if (!empty($errors)) {
        echo "<div class='error-message'><h3>Error:</h3>";
        foreach ($errors as $error) {
            echo "<p>- $error</p>";
        }
        echo "</div>";
        include 'index.html'; // Include your form page
        exit;
    }

    // Prepare and execute SQL
    $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
    if (!$stmt) {
        die("<div class='error-message'>Prepare failed: " . $conn->error . "</div>");
    }

    $stmt->bind_param("sss", $name, $email, $message);
    
    if ($stmt->execute()) {
        // Success message with styling
        echo "<div class='success-message'>
                <h3>Thank You!</h3>
                <p>Your message has been sent successfully. I'll get back to you soon.</p>
              </div>";
        
        // Include email sending code here if needed
    } else {
        echo "<div class='error-message'>
                <h3>Error</h3>
                <p>Sorry, there was an error saving your message: " . $stmt->error . "</p>
              </div>";
    }

    $stmt->close();
} else {
    // Not a POST request - redirect to form
    header("Location: index.html");
    exit;
}

$conn->close();

// Include your form page again so users can send another message
include 'index.html';
?>

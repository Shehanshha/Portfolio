<?php
// Database connection
$servername = "localhost"; 
$username = "root";  
$password = "Sanyam@2004";  
$dbname = "portfolio";  // Fixed typo from 'porfolio' to 'portfolio'

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Initialize variables and sanitize inputs
    $name = trim(htmlspecialchars($_POST['name'] ?? ''));
    $email = trim(htmlspecialchars($_POST['email'] ?? ''));
    $message = trim(htmlspecialchars($_POST['message'] ?? ''));

    // Input validation
    $errors = [];
    if (empty($name)) {
        $errors[] = "Name is required";
    }
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    if (empty($message)) {
        $errors[] = "Message is required";
    }

    // If validation errors exist, display them
    if (!empty($errors)) {
        echo "<div class='error-message'>";
        echo "<h3>Error:</h3>";
        foreach ($errors as $error) {
            echo "<p>- $error</p>";
        }
        echo "</div>";
        exit;
    }

    // Prepare SQL statement
    $sql = "INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        die("SQL Error: " . $conn->error);
    }

    // Bind parameters and execute
    $stmt->bind_param("sss", $name, $email, $message);
    
    if ($stmt->execute()) {
        // Database operation successful
        
        // Send email notification to yourself
        $to = "sanyamkudale@gmail.com"; 
        $subject = "New Contact Form Message from $name";
        $headers = "From: $email\r\n";  
        $headers .= "Reply-To: $email\r\n";  
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        $email_body = "
        <html>
        <head>
            <title>New Contact Message</title>
        </head>
        <body>
            <h2>New Message from Your Portfolio Contact Form</h2>
            <p><strong>Name:</strong> $name</p>
            <p><strong>Email:</strong> $email</p>
            <p><strong>Message:</strong><br>". nl2br($message) ."</p>
            <br>
            <p>Reply directly to this email to respond.</p>
        </body>
        </html>";

        if (mail($to, $subject, $email_body, $headers)) {
            echo "<div class='success-message'>";
            echo "<h3>Thank You!</h3>";
            echo "<p>Your message has been sent successfully. You'll receive a confirmation email shortly.</p>";
            echo "</div>";
        } else {
            echo "<div class='warning-message'>";
            echo "<h3>Message Saved</h3>";
            echo "<p>Your message has been saved, but there was an issue sending the notification email.</p>";
            echo "</div>";
        }

        // Send confirmation email to the sender
        $confirm_subject = "Thank You for Contacting Me!";
        $confirm_headers = "From: sanyamkudale@gmail.com\r\n";
        $confirm_headers .= "Reply-To: sanyamkudale@gmail.com\r\n";
        $confirm_headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        $confirm_body = "
        <html>
        <head>
            <title>Thank You for Contacting Me</title>
        </head>
        <body>
            <h2>Hello $name,</h2>
            <p>Thank you for reaching out! I have received your message and will get back to you soon.</p>
            <p><strong>Your Message:</strong><br>". nl2br($message) ."</p>
            <br>
            <p>Best Regards,<br>Sanyam Kudale</p>
        </body>
        </html>";

        mail($email, $confirm_subject, $confirm_body, $confirm_headers);
    } else {
        echo "<div class='error-message'>";
        echo "<h3>Database Error</h3>";
        echo "<p>Sorry, there was an error saving your message: " . $stmt->error . "</p>";
        echo "</div>";
    }

    $stmt->close();
} else {
    // Not a POST request
    header("Location: index.html"); // Redirect to your contact form
    exit;
}

$conn->close();
?>

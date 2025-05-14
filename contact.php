<?php
// Database connection
$servername = "localhost"; 
$username = "root";  
$password = "Sanyam@2004";  
$dbname = "porfolio";  

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Database connected successfully!<br>";
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);

    // Input validation
    if (empty($name) || empty($email) || empty($message)) {
        echo "All fields are required!";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format!";
        exit;
    }

    // Insert data into the database
    if (!$stmt = $conn->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)")) {
        die("SQL Error: " . $conn->error);
    }
    
    $stmt->bind_param("sss", $name, $email, $message);
    
    echo "Preparing to insert: Name: $name, Email: $email, Message: $message <br>";

    if ($stmt->execute()) {
        echo "Message stored successfully!";

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
            <p><strong>Message:</strong><br> $message</p>
            <br>
            <p>Reply directly to this email to respond.</p>
        </body>
        </html>";

        mail($to, $subject, $email_body, $headers);

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
            <p><strong>Your Message:</strong> $message</p>
            <br>
            <p>Best Regards,<br>Sanyam Kudale</p>
        </body>
        </html>";

        mail($email, $confirm_subject, $confirm_body, $confirm_headers);
    } else {
        echo "Error inserting data: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

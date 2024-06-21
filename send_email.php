<?php
// Include Composer autoloader
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = ""; // Replace with your MySQL password if set
$dbname = "sample_mail";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize email input
    $email = filter_var($_POST['mail'], FILTER_SANITIZE_EMAIL);

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format");
    }
    
    // Prepare SQL statement to insert email into `emails` table
    $sql = "INSERT INTO emails (email) VALUES ('$email')";

    // Execute SQL statement
    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
        
        // Send email notification using PHPMailer
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com'; // Your SMTP host
            $mail->SMTPAuth   = true;
            $mail->Username   = 'falihaabdulsalam@gmail.com'; // SMTP username
            $mail->Password   = 'jipr oqwg hygf lssr';   // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
            $mail->Port       = 587; // TCP port to connect to

            //Recipients
            $mail->setFrom($email);
            $mail->addAddress('falihaabdulsalam@gmail.com');

            // Content
            $mail->isHTML(false); // Set email format to plain text
            $mail->Subject = 'New Email Submission';
            $mail->Body    = "You have received a new email submission:\n\nEmail: $email";

            $mail->send();
            echo "<br>Email sent successfully to falihaabdulsalam@gmail.com";
        } catch (Exception $e) {
            echo "<br>Failed to send email. Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close connection
$conn->close();
?>

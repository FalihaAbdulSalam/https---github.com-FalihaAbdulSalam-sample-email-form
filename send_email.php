<?php
// Include Composer autoloader
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

// Load environment variables from .env file
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Database connection parameters
$servername = $_ENV['DB_SERVERNAME'];
$username = $_ENV['DB_USERNAME'];
$password = $_ENV['DB_PASSWORD'];
$dbname = $_ENV['DB_NAME'];

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
    $stmt = $conn->prepare("INSERT INTO emails (email) VALUES (?)");
    $stmt->bind_param("s", $email);

    // Execute SQL statement
    if ($stmt->execute()) {
        echo "New record created successfully";

        // Send email notification using PHPMailer
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->isSMTP();
            $mail->Host       = $_ENV['SMTP_HOST']; // Your SMTP host
            $mail->SMTPAuth   = true;
            $mail->Username   = $_ENV['SMTP_USERNAME']; // SMTP username
            $mail->Password   = $_ENV['SMTP_PASSWORD']; // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
            $mail->Port       = $_ENV['SMTP_PORT']; // TCP port to connect to

            //Recipients
            $mail->setFrom('no-reply@yourdomain.com', 'Mailer');
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
        echo "Error: " . $stmt->error;
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>

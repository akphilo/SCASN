<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include Composer's autoload file
require 'vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $donation_type = $_POST['donation_type'];
    $amount = isset($_POST['amount']) ? $_POST['amount'] : ''; // For monetary donation
    $equipment_details = isset($_POST['equipment_details']) ? $_POST['equipment_details'] : ''; // For equipment donation
    $pickup_location = isset($_POST['pickup_location']) ? $_POST['pickup_location'] : ''; // For equipment donation

    // Validate essential fields
    if (!empty($name) && !empty($email) && !empty($donation_type)) {
        // Create a new PHPMailer instance
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Your SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 'myserver194@gmail.com'; // Your email
            $mail->Password = 'sdzi iktt mljj ywsy'; // Your email's app password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Encryption method
            $mail->Port = 587; // TLS port

            // Recipients
            $mail->setFrom('myserver194@gmail.com', 'SCASN'); // Replace with your email
            $mail->addAddress('director.scsaan@gmail.com', 'SCASN'); // Replace with your recipient's email

            // Email subject and body
            $mail->Subject = "New Donation Received from $name";
            $mail->Body = "Donor Information:\n";
            $mail->Body .= "Name: $name\n";
            $mail->Body .= "Email: $email\n";
            $mail->Body .= "Phone: $phone\n";
            $mail->Body .= "Donation Type: $donation_type\n";

            if ($donation_type == 'money') {
                $mail->Body .= "Donation Amount: $" . $amount . "\n";
            } elseif ($donation_type == 'equipment') {
                $mail->Body .= "Equipment Details: $equipment_details\n";
                $mail->Body .= "Pickup Location: $pickup_location\n";
            }

           // $mail->Body = nl2br($body); // Convert newlines to <br> for HTML emails
            // Send the email
           // $mail->Body = $body;

            $mail->send();
            
            // Redirect back to the form with a success message
            header("Location: donate.html?success=1");
            exit();
        } catch (Exception $e) {
            // Redirect back to the form with an error message
            $errorMessage = urlencode("Unable to send the email. Mailer Error: {$mail->ErrorInfo}");
            header("Location: donate.html?error=$errorMessage");
            exit();
        }
    } else {
        // Redirect back to the form with a validation error message
        $errorMessage = urlencode("Please fill in all required fields.");
        header("Location: donate.html?error=$errorMessage");
        exit();
    }
} else {
    // Redirect to the donation form if accessed directly
    header("Location: donate.html");
    exit();
}
?>

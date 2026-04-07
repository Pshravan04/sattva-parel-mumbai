<?php
ob_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name'] ?? '');
    $email = htmlspecialchars($_POST['email'] ?? '');
    $mobile = htmlspecialchars($_POST['mobile'] ?? '');

    // Optional Logging
    file_put_contents("debug-log.txt", "Name: $name | Email: $email | Phone: $mobile\n", FILE_APPEND);

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = '{{SMTP_USER}}';
        $mail->Password = '{{SMTP_PASS}}';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('{{SMTP_USER}}', 'Website Lead');

        $mail->addAddress('');

        $mail->isHTML(true);
        $mail->Subject = 'New Lead Sattva Parel Mumbai';
        $mail->Body = "
            <h2>New Lead Submission</h2>
            <p><strong>Name:</strong> {$name}</p>
            <p><strong>Email:</strong> {$email}</p>
            <p><strong>Phone:</strong> {$mobile}</p>
        ";

        if ($mail->send()) {
            header("Location: thankyou.html");
            exit();
        } else {
            echo "Mailer Error: " . $mail->ErrorInfo;
        }

    } catch (Exception $e) {
        echo "Error Sending Email: {$mail->ErrorInfo}";
    }

} else {
    echo "⚠️ Invalid Request: Must be POST.";
}

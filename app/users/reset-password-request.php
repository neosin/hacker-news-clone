<?php

declare(strict_types=1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../autoload.php';
require __DIR__ . '/../../vendor/autoload.php';

$mail = new PHPMailer(true);

if (isset($_POST['email'])) {
    $email = $_POST['email'];

    $selector = bin2hex(random_bytes(8));
    $token = random_bytes(32);

    $url = "http://localhost:8000/login.php?login=reset_password" . "&selector=" . $selector . "&token=" . bin2hex($token);

    // Reset process expires after 5 minutes.
    $tokenExpires = date("U") + 300;

    $statement = $db->prepare('DELETE FROM reset_passwords WHERE email = :email');
    $statement->bindParam(':email', $email, PDO::PARAM_STR);
    $statement->execute();

    if (!$statement) {
        die(var_dump($db->errorInfo()));
    }

    $hashedToken = password_hash($token, PASSWORD_DEFAULT);

    $statement = $db->prepare("INSERT INTO reset_passwords (email, selector, token, expires) VALUES (:email, :selector, :token, :expires);");
    $statement->bindParam(':email', $email, PDO::PARAM_STR);
    $statement->bindParam(':selector', $selector, PDO::PARAM_STR);
    $statement->bindParam(':token', $hashedToken, PDO::PARAM_STR);
    $statement->bindParam(':expires', $tokenExpires, PDO::PARAM_STR);
    $statement->execute();

    $sendTo = $email;

    $emailSubject = 'Hacker News password reset';
    $emailMessage = 'Click on the link to create a new password: ';
    $emailMessage .= '//
     ';
    $emailMessage .= '<a href="' . $url . ' ">' . $url  . '; </a>';
}
try {
    //Server settings
    $mail->SMTPDebug = 1;                      // Enable verbose debug output
    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'example@gmail.com';                     // SMTP username
    $mail->Password   = 'password';                               // SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

    //Recipients
    $mail->setFrom('example@mail.com', 'hacker news');
    $mail->addAddress($sendTo, 'User');     // Add a recipient

    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Password reset';
    $mail->Body    = $emailMessage;
    $mail->AltBody =  $emailMessage;  // 'This is the body in plain text for non-HTML mail clients'

    $mail->send();

    header('location: /../../login.php?login=check_mail');
} catch (Exception $e) {
    header('/../../login.php');
}

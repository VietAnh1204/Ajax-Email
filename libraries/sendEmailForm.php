<?php
header('Content-Type: application/json');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require dirname(__DIR__) . '/vendor/phpmailer/phpmailer/src/Exception.php';
require dirname(__DIR__) . '/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require dirname(__DIR__) . '/vendor/phpmailer/phpmailer/src/SMTP.php';

$response = [
    'top_err' =>'',
    'top_success' =>'',
    'to_err' =>'',
    'subject_err' =>'',
    'message_err' =>'',
];

try {
    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host       = getenv('PHP_MAILER_HOST') ?: 'smtp.example.com';
    $mail->SMTPAuth   = true;
    $mail->Port       = getenv('PHP_MAILER_PORT') ?: 587;
    $mail->Username   = getenv('PHP_MAILER_USERNAME') ?: '';//Nhập tài khoản vào đây
    $mail->Password   = getenv('PHP_MAILER_PASSWORD') ?: '';//Nhập mật khẩu vào đây
} catch (Exception $e) {
    $response['top_err'] = 'SMTP configuration error: ' . $e->getMessage();
    exit(json_encode($response));
}

$contentType = isset($_SERVER['CONTENT_TYPE']) ? trim($_SERVER['CONTENT_TYPE']) : '';

if ($contentType == "application/json") {
    $content = trim(file_get_contents('php://input'));
    $decoded = json_decode($content, true);
    if (is_array($decoded)) {
        foreach ($decoded as $name => $value) {
            $decoded[$name] = htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
        }

        // Validation
        if (empty($decoded['to'])) {
            $response['to_err'] = 'Error. Please enter a valid email address';
        } else if (!filter_var($decoded['to'], FILTER_VALIDATE_EMAIL)) {
            $response['to_err'] = 'Error. Please enter a valid email address';
        }
        if (empty($decoded['subject'])) {
            $response['subject_err'] = 'Error. Please enter a subject';
        }
        if (empty($decoded['message'])) {
            $response['message_err'] = 'Error. Please enter a message';
        }

        foreach ($response as $type => $message) {
            if (!empty($response[$type])) {
                exit(json_encode($response));
            }
        }

        try {
            $mail->setFrom('');//Nhập tài khoản vào đây
            $mail->Subject = $decoded['subject'];
            $mail->isHTML(true);
            $mail->Body = '<p>' . $decoded['message'] . '</p>';
            $mail->addAddress($decoded['to']);
            $mail->send();
        } catch (Exception $e) {
            $response['top_err'] = 'Email sending failed: ' . $e->getMessage();
            exit(json_encode($response));
        }

        $response['top_success'] = 'Email sent successfully';
        exit(json_encode($response));
    }
}

$response['top_err'] = 'Invalid request format';
exit(json_encode($response));

<?php
// Email configuration
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'your-email@gmail.com');
define('SMTP_PASS', 'your-app-password');
define('FROM_EMAIL', 'your-email@gmail.com');
define('FROM_NAME', 'KPR College - Computer Applications');

// Include PHPMailer
require_once '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendCertificateEmail($recipient_email, $recipient_name, $certificate_url) {
    try {
        $mail = new PHPMailer(true);

        // Server settings
        $mail->SMTPDebug = 0; // Disable debug output
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USER;
        $mail->Password = SMTP_PASS;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = SMTP_PORT;

        // Recipients
        $mail->setFrom(FROM_EMAIL, FROM_NAME);
        $mail->addAddress($recipient_email, $recipient_name);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your Certificate of Participation - KPR College';

        // Email body
        $email_body = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #d4af37;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #001f3f;
            margin: 0;
        }
        .header p {
            color: #666;
            margin: 5px 0 0 0;
        }
        .content {
            color: #333;
            line-height: 1.6;
        }
        .content h2 {
            color: #001f3f;
        }
        .download-btn {
            display: inline-block;
            background-color: #d4af37;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin: 20px 0;
            text-align: center;
        }
        .download-btn:hover {
            background-color: #b8860b;
        }
        .footer {
            text-align: center;
            color: #999;
            font-size: 12px;
            border-top: 1px solid #ddd;
            padding-top: 20px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Congratulations!</h1>
            <p>Your Certificate of Participation is Ready</p>
        </div>

        <div class="content">
            <h2>Dear {$recipient_name},</h2>

            <p>Thank you for participating in the event organized by the <strong>Department of Computer Applications</strong> at <strong>KPR College of Arts, Science and Research</strong>.</p>

            <p>We appreciate your enthusiasm, active participation, and commendable effort. Your certificate of participation has been generated and is ready for download.</p>

            <p style="text-align: center;">
                <a href="{$certificate_url}" class="download-btn" download>📥 Download Certificate</a>
            </p>

            <p>You can also download your certificate by clicking the button above or visiting the link below:</p>
            <p style="word-break: break-all; color: #0066cc; font-size: 12px;">
                {$certificate_url}
            </p>

            <p>If you have any questions about your certificate, please feel free to contact us.</p>

            <p>Best regards,<br>
            <strong>Department of Computer Applications</strong><br>
            KPR College of Arts, Science and Research<br>
            Arasur, Coimbatore
            </p>
        </div>

        <div class="footer">
            <p>&copy; 2024 KPR College. All rights reserved.</p>
            <p>This is an automated email. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
HTML;

        $mail->Body = $email_body;

        // Send email
        if ($mail->send()) {
            return true;
        } else {
            error_log('Email sending failed: ' . $mail->ErrorInfo);
            return false;
        }
    } catch (Exception $e) {
        error_log('Email error: ' . $e->getMessage());
        return false;
    }
}
?>

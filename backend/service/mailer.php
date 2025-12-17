<?php
namespace App;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config/env.php';

use App\Env;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer {
    private PHPMailer $mail;

    public function __construct() {
        $this->mail = new PHPMailer(true);
        $this->setup();
    }

    private function setup(): void {
        $host = Env::get('MAIL_HOST');
        $port = (int) Env::get('MAIL_PORT', '587');
        $user = Env::get('MAIL_USER');
        $pass = Env::get('MAIL_PASS');
        $from = Env::get('MAIL_FROM', $user);
        $fromName = Env::get('MAIL_FROM_NAME', 'App');
        $secure = Env::get('MAIL_SECURE', 'tls');

        if (!$host || !$user || !$pass) {
            throw new \Exception("Konfigurasi email (.env) belum lengkap (MAIL_HOST/USER/PASS).");
        }
        
        $this->mail->SMTPDebug = 0;

        $this->mail->isSMTP();
        $this->mail->Host = $host;
        $this->mail->SMTPAuth = true;
        $this->mail->Username = $user;
        $this->mail->Password = $pass;
        $this->mail->Port = $port;

        if ($secure === 'ssl') $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        else $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

        $this->mail->CharSet = 'UTF-8';
        $this->mail->setFrom($from, $fromName);
    }

    public function send(string $toEmail, string $toName, string $subject, string $htmlBody, string $plainBody = ''): array {
        try {
            $this->mail->clearAllRecipients();
            $this->mail->addAddress($toEmail, $toName);
            $this->mail->Subject = $subject;

            $this->mail->isHTML(true);
            $this->mail->Body = $htmlBody;
            $this->mail->AltBody = $plainBody ?: strip_tags($htmlBody);

            $this->mail->send();
            return ["success" => true, "error" => null];
        } catch (Exception $e) {
            return ["success" => false, "error" => $e->getMessage()];
        }
    }
}

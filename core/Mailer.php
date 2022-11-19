<?php

namespace Core;

use Exception;
use PHPMailer\PHPMailer\PHPMailer;

final class Mailer
{
    private string $to;
    private string $subject;
    private string $body;
    private string $altBody;
    private string $cc;
    private string $bcc;
    private string $attachment;

    private $debug; // MAIL_DEBUG=2 # [0 => OFF, 1 => DEBUG_CLIENT, 2 => DEBUG_SERVER]
    private $host; // MAIL_HOST=smtp.example.com
    private $usr; // MAIL_SMTP_USERNAME=user@example.com
    private $pw; // MAIL_SMTP_PASSWORD=secret
    private $sercure; // MAIL_SMTP_SERCURE=ssl # [ssl, tls]
    private $port; // MAIL_PORT=465
    private $from; // MAIL_FROM=from@example.com
    private $name; // MAIL_FROM=from@example.com

    public function __construct()
    {
        $this->debug = $_ENV["MAIL_DEBUG"];
        $this->host = $_ENV["MAIL_HOST"];
        $this->usr = $_ENV["MAIL_SMTP_USERNAME"];
        $this->pw = $_ENV["MAIL_SMTP_PASSWORD"];
        $this->sercure = $_ENV["MAIL_SMTP_SERCURE"];
        $this->port = $_ENV["MAIL_PORT"];
        $this->from = $_ENV["MAIL_FROM"];
        $this->name = $_ENV["MAIL_ALIAS_NAME"];
    }

    final public function send(): bool
    {
        $mail = new PHPMailer();

        try {
            // Server settings
            $mail->SMTPDebug = $this->debug;
            $mail->isSMTP();
            $mail->Host       = $this->host;
            $mail->SMTPAuth   = true;
            $mail->Username   = $this->usr;
            $mail->Password   = $this->pw;
            $mail->SMTPSecure = $this->sercure;
            $mail->Port       = $this->port;

            // Recipients
            $mail->setFrom($this->from, $this->name);
            $mail->addAddress($this->to);
            if (isset($this->cc)) $mail->addCC($this->cc);
            if (isset($this->bcc)) $mail->addBCC($this->bcc);

            // Attachments
            if (isset($this->attachment)) $mail->addAttachment($this->attachment);

            // Content
            $mail->isHTML(true);
            $mail->Subject = $this->subject;
            $mail->Body    = $this->body;
            if (isset($this->altBody)) $mail->AltBody = $this->altBody;

            return $mail->send();
        } catch (Exception $e) {
            throw $e;
        }
    }

    final public function addReceiver(string $email): Mailer
    {
        $this->to = $email;

        return $this;
    }

    final public function addSubject(string $subject): Mailer
    {
        $this->subject = $subject;

        return $this;
    }

    final public function addBody(string $body): Mailer
    {
        $this->body = $body;

        return $this;
    }

    final public function addView(string $template, array $data = [], array $mergeData = []): Mailer
    {
        $view = new View();
        $this->body = $view->getContent($template, $data, $mergeData);

        return $this;
    }

    final public function addAltBody(string $altBody): Mailer
    {
        $this->altBody = $altBody;

        return $this;
    }

    final public function addCc(string $cc): Mailer
    {
        $this->cc = $cc;

        return $this;
    }

    final public function addBcc(string $bcc): Mailer
    {
        $this->bcc = $bcc;

        return $this;
    }

    final public function addAttachment(string $path): Mailer
    {
        $this->attachment = $path;

        return $this;
    }
}

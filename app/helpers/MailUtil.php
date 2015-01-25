<?php

/**
 * Created by PhpStorm.
 * User: sknz
 * Date: 1/25/15
 * Time: 8:06 PM
 */
class MailUtil
{
    private function __construct()
    {

    }

    public static function send($to, $subject, $message)
    {
        $transporter = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
            ->setUsername()
            ->setPassword();

        $mailer = Swift_Mailer::newInstance($transporter);
    }

    private function __clone()
    {

    }
}
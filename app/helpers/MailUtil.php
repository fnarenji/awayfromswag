<?php
namespace app\helpers;

use SwagFramework\Config\ConfigFileParser;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;

/**
 * Created by PhpStorm.
 * User: sknz
 * Date: 1/25/15
 * Time: 8:06 PM
 */

namespace app\helpers;

class MailUtil
{
    private function __construct()
    {

    }

    public static function send($to, $subject, $message)
    {
        $config = new ConfigFileParser("app/config/mail.json");
        $transporter = Swift_SmtpTransport::newInstance($config->getEntry('host'), (int)$config->getEntry('port'), $config->getEntry('security'))
            ->setUsername($config->getEntry('username'))
            ->setPassword($config->getEntry('password'));

        $mailer = Swift_Mailer::newInstance($transporter);
        $message = Swift_Message::newInstance($subject, $message)
            ->addTo($to)
            ->setSender($config->getEntry('sender'));
        $mailer->send($message);
    }

    private function __clone()
    {

    }
}
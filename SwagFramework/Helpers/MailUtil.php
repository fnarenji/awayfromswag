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
    private static $config;
    private function __construct()
    {

    }

    public static function send($to, $subject, $message)
    {
        if (is_null(self::$config))
            self::$config = new ConfigFileParser("app/config/mail.json");

        $transporter = Swift_SmtpTransport::newInstance()
            ->setHost(self::$config->getEntry('host'))
            ->setPort((int)self::$config->getEntry('port'))
            ->setEncryption(self::$config->getEntry('security'))
            ->setUsername(self::$config->getEntry('username'))
            ->setPassword(self::$config->getEntry('password'));

        $mailer = Swift_Mailer::newInstance($transporter);
        $message = Swift_Message::newInstance($subject, $message)
            ->addTo($to)
            ->setSender(self::$config->getEntry('sender'));
        $mailer->send($message);
    }

    private function __clone()
    {

    }
}
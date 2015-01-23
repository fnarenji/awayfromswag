<?php
/**
 * Created by PhpStorm.
 * User: loick
 * Date: 16/01/15
 * Time: 11:48
 */

namespace SwagFramework\Config;


class ConversationConfig
{

    private $path;

    function __construct($path)
    {
        $this->path = $path;
    }

    public static function parseFromFile($fileName = 'app/config/conversation.json')
    {
        $config = new ConfigFileParser($fileName);
        return new self(
            $config->getEntry('path')
        );
    }

    public function getPath()
    {
        return $this->path;
    }


}
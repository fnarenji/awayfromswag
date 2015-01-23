<?php
/**
 * Created by PhpStorm.
 * User: loick
 * Date: 30/12/14
 * Time: 20:30
 */

namespace SwagFramework\Helpers;


use SwagFramework\Exceptions\InputNotSetException;
use SwagFramework\Exceptions\PopupIncorrectTypeException;

class Popup
{
    /**
     * session key
     */
    const SESSION_KEY = 'popup';

    private $type = array(
        'success' => 'success',
        'error' => 'error',
        'warning' => 'warning'
    );

    /**
     * @var array popup
     */
    private $popup = [];

    /**
     * default constructor
     */
    function __construct()
    {
        try {
            $popup = Input::session(self::SESSION_KEY);
        } catch (InputNotSetException $e) {
        }

        if (!empty($popup)) {
            $this->popup = $popup;
        }
    }

    /**
     * set popup
     * @param $title string title of popup
     * @param $message string message of popup
     * @param string $type type of popup
     * @throws PopupIncorrectTypeException
     */
    public function set($title, $message, $type = 'warning')
    {
        if (!array_key_exists($type, $this->type)) {
            throw new PopupIncorrectTypeException($type, $title);
        }

        $new = [
            'title' => $title,
            'message' => $message,
            'type' => $type
        ];

        array_push($this->popup, $new);

        $_SESSION[self::SESSION_KEY] = $this->popup;
    }

    /**
     * get all popup
     * @return array
     */
    public function getAll()
    {
        return $this->popup;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: loick
 * Date: 30/12/14
 * Time: 20:30
 */

namespace SwagFramework\Helpers;


use SwagFramework\Exceptions\PopupIncorrectTypeException;
use SwagFramework\Tools\Session;

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
    private $popup = array();

    /**
     * @var Session Session helper
     */
    private $session;

    /**
     * default constructor
     */
    function __construct()
    {
        $this->session = new Session();

        $popup = $this->session->get(self::SESSION_KEY);
        if (!empty($popup)) {
            $this->popup = $popup;
        }
    }

    /**
     * set popup
     * @param $title title of popup
     * @param $message message of popup
     * @param string $type type of popup
     * @throws PopupIncorrectTypeException
     */
    public function set($title, $message, $type = 'warning')
    {
        if (!array_key_exists($type, $this->type)) {
            throw new PopupIncorrectTypeException($type, $title);
        }

        $new = array(
            'title' => $title,
            'message' => $message,
            'type' => $type
        );

        array_push($this->popup, $new);
        $this->session->set(self::SESSION_KEY, $this->popup);
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
<?php
/**
 * Created by PhpStorm.
 * User: loick
 * Date: 17/12/14
 * Time: 09:18
 */

namespace SwagFramework;


use SwagFramework\Exceptions\HelperAlreadyExistsException;
use SwagFramework\Exceptions\HelperNotFoundException;
use system\Helpers\Form;

class Helper
{
    /**
     * @var array helpers available
     */
    private $helpers = array();

    /**
     * default constructor
     * @throws HelperAlreadyExistsException
     */
    function __construct()
    {
        $this->add('form', new Form());
    }

    /**
     * register a new helper
     * @param $name key of helper
     * @param $helper helper
     * @throws HelperAlreadyExistsException
     * @throws HelperNotFoundException
     */
    private function add($name, $helper) {
        if(array_key_exists($name, $this->helpers))
            throw new HelperAlreadyExistsException($name);
        $this->helpers[$name] = new $helper;
    }

    /**
     * get helper
     * @param $name name of helper
     * @return $helper the helper
     * @throws HelperNotFoundException
     */
    public function get($name) {
        $name = strtolower($name);
        if(!array_key_exists($name, $this->helpers))
            throw new HelperNotFoundException($name);
        return $this->helpers[$name];
    }

}
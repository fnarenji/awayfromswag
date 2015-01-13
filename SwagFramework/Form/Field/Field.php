<?php
/**
 * Created by PhpStorm.
 * User: loick
 * Date: 13/01/15
 * Time: 15:39
 */

namespace SwagFramework\Form\Field;


abstract class Field
{
    private $name;
    private $attributes = array();

    function __construct($name, $attributes = array())
    {
        $this->name = $name;
        $this->attributes = $attributes;
    }

    public function addAttribute($name, $value)
    {
        $name = strtolower($name);
        $value = htmlentities($value);
        $this->attributes[$name] = $value;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    public function getAttributesHTML()
    {
        $html = '';
        foreach($this->getAttributes() as $key => $value)
            $html .= ' ' . $key . '="' . addslashes($value) . '"';

        return $html;
    }

    public function getName()
    {
        return $this->name;
    }

    public abstract function getHTML();

}
<?php
/**
 * Created by PhpStorm.
 * User: loick
 * Date: 13/01/15
 * Time: 16:55
 */

namespace SwagFramework\Form\Field;


class Label extends Field
{
    private $value;

    function __construct($for, $value)
    {
        $this->addAttribute('for', $for);
        $this->value = $value;
    }

    public function getHTML()
    {
        return '<label' . $this->getAttributesHTML() . '>' . ucfirst($this->value) . '</label>';
    }
}
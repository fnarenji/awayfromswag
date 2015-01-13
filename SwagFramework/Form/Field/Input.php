<?php
/**
 * Created by PhpStorm.
 * User: loick
 * Date: 13/01/15
 * Time: 15:39
 */

namespace SwagFramework\Form\Field;


class Input extends Field
{
    public function getHTML()
    {
        return '<input' . $this->getAttributesHTML() . ' />';
    }
}
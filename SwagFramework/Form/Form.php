<?php
/**
 * Created by PhpStorm.
 * User: loick
 * Date: 13/01/15
 * Time: 15:34
 */

namespace SwagFramework\Form;


use SwagFramework\Form\Field\LabelField;
use SwagFramework\Helpers\Input;

class Form
{
    private $method;
    private $action;
    private $fields = array();
    private $input;

    function __construct()
    {
        $this->input = new Input();
        $this->method = 'POST';
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function setMethod($method)
    {
        $this->method = $method;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function setAction($action)
    {
        $this->action = $action;
    }

    public function addField(\SwagFramework\Form\Field\Field $field)
    {
        $this->fields[$field->getName()] = $field;
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function getField($name)
    {
        return (isset($this->fields[$name])) ? $this->fields[$name] : null;
    }

    public function getForm()
    {
        $form = array();

        foreach ($this->fields as $field) {
            $form[$field->getName()] = $this->input->post($field->getName());
        }

        return $form;
    }

    public function getFormHTML($labels = array())
    {
        $form = '<form '
            . 'method="' . $this->getMethod() . '"'
            . ' '
            . 'action="' . $this->getAction() . '"'
            . '>';

        foreach ($this->getFields() as $field) {
            if (!empty($labels) && array_key_exists($field->getName(), $labels)) {
                $label = new LabelField($field->getName(), $labels[$field->getName()]);
                $form .= CR . TAB . $label->getHTML();
                $form .= CR . TAB . $field->getHTML();
            } elseif($field->getName() == 'submit' || $field->getAttribute('type') == 'hidden')
                $form .= CR . TAB . $field->getHTML();
        }

        $form .= CR . '</form>';

        return $form;
    }
}
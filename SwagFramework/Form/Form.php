<?php
/**
 * Created by PhpStorm.
 * User: loick
 * Date: 13/01/15
 * Time: 15:34
 */

namespace SwagFramework\Form;


use SwagFramework\Exceptions\AttributeNotExistsException;
use SwagFramework\Form\Field\Field;
use SwagFramework\Form\Field\LabelField;

class Form
{
    private $method;
    private $action;
    private $id;
    private $class;
    /**
     * @var Field[]
     */
    private $fields = [];

    function __construct($action, $method = 'POST')
    {
        $this->method = $method;
        $this->action = $action;
    }

    public function addField(Field $field)
    {
        $this->fields[$field->getName()] = $field;
    }

    public function getField($name)
    {
        return (isset($this->fields[$name])) ? $this->fields[$name] : null;
    }

    public function getForm()
    {
        $form = [];

        foreach ($this->fields as $field) {
            $form[$field->getName()] = $this->input->post($field->getName());
        }

        return $form;
    }

    public function getFormHTML($labels = [])
    {
        $form = '<form '
            . 'method="' . $this->getMethod() . '"'
            . ' '
            . 'action="' . $this->getAction() . '"'
            . ' '
            . 'id="' . $this->getId() . '"'
            . ' '
            . 'class="' . $this->getClass() . '"'
            . '>';

        foreach ($this->getFields() as $field) {
            try{
                if (!empty($labels) && array_key_exists($field->getName(), $labels)) {
                    $label = new LabelField($field->getName(), $labels[$field->getName()]);
                    $form .= CR . TAB . $label->getHTML();
                    $form .= CR . TAB . $field->getHTML();
                } elseif($field->getName() == 'submit' || $field->getAttribute('type') == 'hidden')
                    $form .= CR . TAB . $field->getHTML();
            } catch(AttributeNotExistsException $e){
                // fix getAttribute('type') on TextArea
            }
        }

        $form .= CR . '</form>';

        return $form;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param mixed $class
     */
    public function setClass($class)
    {
        $this->class = $class;
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

    /**
     * @return Field[]
     */
    public function getFields()
    {
        return $this->fields;
    }
}
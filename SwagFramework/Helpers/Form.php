<?php
/**
 * Created by PhpStorm.
 * User: loick
 * Date: 28/12/14
 * Time: 20:31
 */

namespace SwagFramework\Helpers;


use SwagFramework\Config\DatabaseConfig;
use SwagFramework\Database\DatabaseProvider;
use SwagFramework\Exceptions\TableNotFoundDatabaseException;
use SwagFramework\Form\Field\InputField;
use SwagFramework\Form\Field\TextAreaField;

class Form
{
    private function getType($type)
    {
        $tmp = explode('(', $type);
        if (!empty($tmp)) {
            $type = $tmp[0];
        }
        return $type;
    }

    /**
     * convert type mysql to input type
     * if primary key -> hidden
     * default text
     * @param $att
     * @return string
     */
    private function convertAttributeType($att)
    {
        if ($att['Key'] == 'PRI') {
            return 'hidden';
        }
        return 'text';
    }

    private function getInput($value)
    {
        $field = new InputField($value['Field']);
        $field->addAttribute('type', $this->convertAttributeType($value));

        return $field;
    }

    private function getTextArea($value)
    {
        $field = new TextAreaField($value['Field']);

        return $field;
    }

    /**
     * generate form for table
     * @param $table string
     * @param $action string
     * @param string $method method form (default = POST)
     * @return \SwagFramework\Form\Form
     * @throws TableNotFoundDatabaseException
     */
    public function generate($table, $action, $method = 'POST')
    {
        $sql = 'SHOW FIELDS '
            . 'FROM ' . $table;

        DatabaseProvider::connect(DatabaseConfig::parseFromFile());
        $res = DatabaseProvider::connection()->execute($sql, null);

        if (empty($res)) {
            throw new TableNotFoundDatabaseException($table);
        }

        $form = new \SwagFramework\Form\Form();

        foreach ($res as $value) {
            if($this->getType($value['Type']) == 'text')
                $field = $this->getTextArea($value);
            else
                $field = $this->getInput($value);
            $form->addField($field);
        }

        $submit = new InputField('submit');
        $submit->addAttribute('type', 'submit');
        $submit->addAttribute('value', 'Envoyer');
        $form->addField($submit);

        return $form;
    }
}

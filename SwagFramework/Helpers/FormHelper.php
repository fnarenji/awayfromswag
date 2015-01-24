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
use SwagFramework\Form\Form;

class FormHelper
{
    /**
     * generate form for table
     * @param $table string table
     * @param $action string action
     * @param string $method method form (default = POST)
     * @return \SwagFramework\Form\Form
     * @throws TableNotFoundDatabaseException
     */
    public static function generate($table, $action, $method = 'POST')
    {
        $sql = 'SHOW FIELDS '
            . 'FROM ' . $table;

        DatabaseProvider::connect(DatabaseConfig::parseFromFile());
        $res = DatabaseProvider::connection()->query($sql, []);

        if (empty($res)) {
            throw new TableNotFoundDatabaseException($table);
        }

        $form = new Form($action, $method);

        foreach ($res as $value) {
            if (self::getType($value['Type']) == 'text') {
                $field = self::getTextArea($value);
            } else {
                $field = self::getInput($value);
            }
            $form->addField($field);
        }

        $submit = new InputField('submit');
        $submit->addAttribute('type', 'submit');
        $submit->addAttribute('value', 'Envoyer');
        $form->addField($submit);

        return $form;
    }

    private static function getType($type)
    {
        $tmp = explode('(', $type);
        if (!empty($tmp)) {
            $type = $tmp[0];
        }
        return $type;
    }

    private static function getTextArea($value)
    {
        $field = new TextAreaField($value['Field']);

        return $field;
    }

    private static function getInput($value)
    {
        $field = new InputField($value['Field']);
        $field->addAttribute('type', self::convertAttributeType($value));

        return $field;
    }

    /**
     * convert type mysql to input type
     * if primary key -> hidden
     * default text
     * @param $att
     * @return string
     */
    private static function convertAttributeType($att)
    {
        if ($att['Key'] == 'PRI') {
            return 'hidden';
        }
        return 'text';
    }
}

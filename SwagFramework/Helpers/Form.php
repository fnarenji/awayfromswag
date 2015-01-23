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
use SwagFramework\Form\Field\Input;

class Form
{
    /**
     * generate form for table
     * @param $table string table
     * @param $action string action
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
            $field = new Input($value['Field']);
            $field->addAttribute('type', $this->convertAttributeType($value));
            $form->addField($field);
        }

        return $form;
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
}
<?php
/**
 * Created by PhpStorm.
 * User: loick
 * Date: 28/12/14
 * Time: 20:31
 */

namespace system\Helpers;


class Form {
    /**
     * @var database
     */
    private $db;

    /**
     * generate input
     * @param $type
     * @param $name
     * @param string $value
     * @param string $class
     * @return string
     */
    private function input($type, $name, $value = '', $class = '') {
        $input = '<input type="' . $type . '" name="' . $name . '" value="' . $value . '" class="' . $class . '"';

        return $input;
    }

    /**
     * generate label
     * @param $name name of label
     * @param $for input name
     * @return string
     */
    private function label($name, $for) {
        $name = ucfirst($name) . ' :';
        return '<label for="' . $for . '">' . $name. '</label>';
    }

    /**
     * generate input by key
     * key available :
     * id
     * @param $table
     * @param string $type
     * @return string
     */
    private function key($table, $type = 'text') {
        if($type == 'hidden') {
            $res = $this->input('hidden', $table['Field']);
        }
        else {
            $res = $this->label($table['Field'], $table['Field']) . '\n';
            $res .= $this->input('text', $table['Field']);
        }

        return $res;
    }

    /**
     * default constructor
     */
    function __construct()
    {
        $this->db = new Database();
    }

    /**
     * generate form for table
     * @param $table table
     * @param $action action
     * @param string $method method form (default = POST)
     * @return string form
     */
    public function generate($table, $action, $method = 'POST') {
        $sql = 'SHOW FIELDS '
            . 'FROM ?';

        $res = $db->execute($sql, array($table));

        $form = '<form action="' . $action . '" method="' . $method . '">';

        foreach($res as $value) {
            $form .= $this->key($value);
        }

        $form .= $this->input('submit', 'submit', 'Envoyer');
        $form .= '</form>';

        return $form;
    }
}
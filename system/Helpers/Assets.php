<?php
/**
 * Created by PhpStorm.
 * User: loick
 * Date: 29/12/14
 * Time: 16:02
 */

namespace SwagFramework\Helpers;


use SwagFramework\Exceptions\FileNotFoundException;

class Assets {
    /**
     * get file web
     * @param $file
     * @return string
     * @throws FileNotFoundException
     */
    private function file($file) {
        $file = ROOT . 'public' . DS . $file;
        if(!file_exists($file))
            throw new FileNotFoundException($file);
        return WEBROOT . 'public' . DS . $file;
    }

    /**
     * generate css link
     * @param $name name of css file without extension .css
     * @return string css link
     * @throws FileNotFoundException
     */
    public function css($name) {
        $file = $this->file($name . '.css');
        return '<link rel="stylesheet" href="' . $file . '">';
    }

    /**
     * generate js link
     * @param $name name of js file without extension .js
     * @return string js link
     * @throws FileNotFoundException
     */
    public function js($name) {
        $file = $this->file($name . '.js');
        return '<script type="text/javascript" src="' . $file . '"></script>';
    }

    /**
     * generate img link
     * @param $src
     * @param string $alt
     * @return string img link
     * @throws FileNotFoundException
     */
    public function img($src, $alt = '') {
        $file = $this->file($src);
        return '<img src="' . $file . '" alt="' . $alt . '" />';
    }
}
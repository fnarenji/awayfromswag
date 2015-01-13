<?php
/**
 * Created by PhpStorm.
 * User: loick
 * Date: 29/12/14
 * Time: 16:02
 */

namespace SwagFramework\Helpers;


use SwagFramework\Exceptions\FileNotFoundException;

class Assets
{
    /**
     * generate css link
     * @param $name name of css file without extension .css
     * @return string css link
     * @throws FileNotFoundException
     */
    public function css($name)
    {
        $file = $this->file('css' . DS . $name . '.css');
        echo '<link rel="stylesheet" href="' . $file . '">';
    }

    /**
     * get file web
     * @param $file
     * @return string
     * @throws FileNotFoundException
     */
    private function file($file)
    {
        $test = ROOT . 'public' . DS . $file;
        if (!file_exists($test)) {
            throw new FileNotFoundException($test);
        }
        return WEBROOT . 'public' . DS . $file;
    }

    /**
     * generate js link
     * @param $name name of js file without extension .js
     * @return string js link
     * @throws FileNotFoundException
     */
    public function js($name)
    {
        $file = $this->file('js' . DS . $name . '.js');
        echo '<script type="text/javascript" src="' . $file . '"></script>';
    }

    /**
     * generate img link
     * @param $src src public/img/src
     * @param string $alt
     * @return string img link
     * @throws FileNotFoundException
     */
    public function img($src, $alt = '')
    {
        $file = $this->file('img' . DS . $src);
        echo '<img src="' . $file . '" alt="' . $alt . '" />';
    }
}
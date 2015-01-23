<?php
/**
 * Created by PhpStorm.
 * User: thomasmunoz
 * Date: 30/12/14
 * Time: 00:04
 */

namespace SwagFramework\XML;

use SplFileObject;
use SwagFramework\Exceptions\XMLArrayBadFormatException;
use SwagFramework\Exceptions\XMLNotWritableException;

/**
 * This class creates a XML file (or rewrites it)
 * and write everything you want on it with the tags you choose.
 * Class XML
 * @package SwagFramework\XML
 */
class XML
{
    /**
     * This is the name of the file we want to create.
     * @var string
     */
    protected $name;
    /**
     * This is the file that we want to edit
     * @var SplFileObject
     */
    protected $file;
    /**
     * This is the name of the very first tag in your file
     * (the doctype tag in HTMl for example)
     * @var string
     */
    private $header;

    public function __construct($name, $header)
    {
        $this->name = $name;
        $this->header = $header;

        // Throw a runtime exception if not writable
        $this->file = new \SplFileObject($name, 'w');

        $this->begin($this->header);
    }

    private function begin($header)
    {
        $tag = '<?' . $header . '?>';
        $tag .= "\n";

        $this->file->fwrite($tag);
    }

    public function write($content = array(), $level = 1)
    {

        // faster than is_array()
        if (isset($content['content']) && (array)$content['content'] == $content['content']) {
            $tag = '<' . $content['title'];
            $tag .= isset($content['option']) ? ' ' . $content['option'] : '';
            $tag .= '>';
            $tag .= "\n";
            $this->file->fwrite($tag);

            if (count($content['content']) > 2) {
                foreach ($content['content'] as $item) {
                    $this->addTab($level);
                    $this->write($item, $level + 1);
                }
            } else {
                $this->addTab($level);
                $this->write($content['content'], $level + 1);
            }

            $this->addTab($level - 1);
            $tag = '</' . $content['title'] . '>';
            $tag .= "\n";
            $this->file->fwrite($tag);
        } else {
            if (!isset($content['content'])) {
                $this->writeOrphanTag($content);
            } else {
                $this->writeTag($content);
            }

        }
    }

    private function addTab($number)
    {
        for ($i = 0; $i < $number; ++$i) {
            $this->file->fwrite("\t");
        }
    }

    private function writeOrphanTag($content)
    {
        $tag = '<' . $content['title'];
        $tag .= isset($content['option']) ? ' ' . $content['option'] : '';
        $tag .= '/>';
        $tag .= "\n";
        $this->file->fwrite($tag);
    }

    private function writeTag($content = array())
    {
        $size = count($content);

        if ($size < 1 || $size > 2) {
            throw new XMLNotWritableException($content);
        } else {
            if (!isset($content['title']) || !isset($content['content'])) {
                throw new XMLArrayBadFormatException();
            }
        }

        $tag = '<' . $content['title'];
        $tag .= isset($content['option']) ? ' ' . $content['option'] : '';
        $tag .= '>';
        $tag .= $content['content'];
        $tag .= '</' . $content['title'] . '>';
        $tag .= "\n";

        $this->file->fwrite($tag);
    }

    public function getFileName()
    {
        return $this->name;
    }

    protected function getFile()
    {
        return $this->file;
    }
}
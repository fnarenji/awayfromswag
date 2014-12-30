<?php
/**
 * Created by PhpStorm.
 * User: thomasmunoz
 * Date: 30/12/14
 * Time: 01:20
 */

namespace tests;

use \SwagFramework\XML\XML;

class XMLTest extends \PHPUnit_Framework_TestCase
{
    private function createSimpleXMLFile()
    {
        $header = 'xml version="1.0" encoding="UTF-8"';
        $name = 'XMLTest.xml';
        $file = new XML($name, $header);

        $content = array(
                            'title' => 'note',
                            'content' => array(
                                array(
                                    'title' => 'to',
                                    'content' =>'Tove'
                                ),
                                array(
                                    'title' => 'from',
                                    'content' => 'Jani'
                                ),
                                array(
                                    'title' => 'heading',
                                    'content' => 'Reminder'
                                ),
                                array(
                                    'title' => 'body',
                                    'content' => 'Don\'t forget me this weekend!'
                                )
                            )
                        );
        $file->write($content);
        return $name;
    }

    private function readSimpleXML()
    {
        $filename = $this->createSimpleXMLFile();
        $file = new \SplFileObject($filename, "r");
        $contentToTest = $file->fread($file->getSize());

        $filename = 'SimpleXML.xml';
        $file = new \SplFileObject($filename, "r");
        $content = $file->fread($file->getSize());

        return array($contentToTest, $content);
    }
    public function testXML()
    {
        $simpleXML = $this->readSimpleXML();

        $this->assertEquals(0, 0);
        $this->assertEquals($simpleXML[0], $simpleXML[1]);

    }
}
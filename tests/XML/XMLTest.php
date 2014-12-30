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
        // Example taken on http://www.w3schools.com/xml/
        $header = 'xml version="1.0" encoding="UTF-8"';
        $name = 'SimpleXML.xml';
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

    private function createComplexXMLFile()
    {
        // Example taken on http://www.w3schools.com/xml/
        $header = 'xml version="1.0" encoding="UTF-8" ';
        $name = 'ComplexXML.xml';
        $file = new XML($name, $header);

        $content = array(
                        'title' => 'rss',
                        'option' => 'version="2.0"',
                        'content' => array(
                            'title' => 'channel',
                            'content' => array(
                                        array(
                                            'title' => 'title',
                                            'content' => 'W3Schools Home Page'
                                            ),
                                        array(
                                            'title' => 'link',
                                            'content' => 'http://www.w3schools.com'
                                             ),
                                        array(
                                            'title' => 'description',
                                            'content' => 'Free web building tutorials'
                                            ),
                                        array(
                                            'title' => 'item',
                                            'content' => array(
                                                            array(
                                                                'title' => 'title',
                                                                'content' => 'RSS Tutorial'
                                                            ),
                                                            array(
                                                                'title' => 'link',
                                                                'content' => 'http://www.w3schools.com/rss'
                                                            ),
                                                            array(
                                                                'title' => 'description',
                                                                'content' => 'New RSS tutorial on W3Schools'
                                                            )
                                                        )
                                        ),
                                        array(
                                            'title' => 'item',
                                            'content' => array(
                                                array(
                                                    'title' => 'title',
                                                    'content' => 'XML Tutorial'
                                                ),
                                                array(
                                                    'title' => 'link',
                                                    'content' => 'http://www.w3schools.com/xml'
                                                ),
                                                array(
                                                    'title' => 'description',
                                                    'content' => 'New XML tutorial on W3Schools'
                                                )
                                            )
                                    )
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
    private function readComplexXML()
    {
        $filename = $this->createComplexXMLFile();
        $file = new \SplFileObject($filename, "r");
        $contentToTest = $file->fread($file->getSize());

        $filename = 'ComplexXML.xml';
        $file = new \SplFileObject($filename, "r");
        $content = $file->fread($file->getSize());

        return array($contentToTest, $content);
    }
    public function testSimpleXML()
    {
        $simpleXML = $this->readSimpleXML();
        $this->assertEquals($simpleXML[0], $simpleXML[1]);
    }
    public function testComplexXML()
    {
        $complexXML = $this->readComplexXML();
        $this->assertEquals($complexXML[0], $complexXML[1]);
    }
}
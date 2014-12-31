<?php
/**
 * Created by PhpStorm.
 * User: thomasmunoz
 * Date: 30/12/14
 * Time: 23:58
 */

namespace tests\RSS;
use \SwagFramework\XML\RSS;

class RSSTest extends \PHPUnit_Framework_TestCase
{
    private function createSimpleRSS()
    {
        $name = 'SimpleRSSTest.rss';
        $url = 'http://unicorn.ovh';
        $title = 'Articles about Unicorn - Unicorn are real!';
        $img = 'http://upload.wikimedia.org/wikipedia/commons/8/8f/Historiae_animalium_1551_De_Monocerote.jpg';
        $author = 'AFS';

        $feed = new RSS($name, $url, $title, $img, $author);

        $title = 'Proof that unicorn exists';
        $link = 'https://www.youtube.com/watch?v=HHQIXCs4d98';
        $summary = 'This is a legit proof that unicorn are real, they exist I have a proof now !';
        $date = new \DateTime();
        $dateU = $date->format('Y-m-d\TH:i:s\Z');

        $feed->addEntry($title, $link, $summary, $dateU);
        $feed->create();

        return $name;
    }

    private function createComplexRSS()
    {

        /* HEADER */
        $name = 'ComplexRSSTest.rss';
        $url = 'http://unicorn.ovh';
        $title = 'Articles about Unicorn - Unicorn are real!';
        $img = 'http://upload.wikimedia.org/wikipedia/commons/8/8f/Historiae_animalium_1551_De_Monocerote.jpg';
        $author = 'AFS';

        $feed = new RSS($name, $url, $title, $img, $author);

        /* FIRST ENTRY */
        $title = 'Proof that unicorn exists';
        $link = 'https://www.youtube.com/watch?v=HHQIXCs4d98';
        $summary = 'This is a legit proof that unicorn are real, they exist I have a proof now !';
        $date = new \DateTime();
        $dateU = $date->format('Y-m-d\TH:i:s\Z');

        $feed->addEntry($title, $link, $summary, $dateU);

        /* SECOND ENTRY */
        $title = 'A new Unicorn has been seen!';
        $link = 'http://www.iflscience.com/plants-and-animals/rare-unicorn-deer-found-slovenia';
        $summary = 'In Slovenia an unicorn has been discovered!';
        $date = new \DateTime();
        $dateU = $date->format('Y-m-d\TH:i:s\Z');

        $feed->addEntry($title, $link, $summary, $dateU);

        /* THIRD ENTRY */

        $title = 'Listen this new song about unicorn!';
        $link = 'https://www.youtube.com/watch?v=aOYRj9jCGtE';
        $summary = 'This is a new french song about unicorns, you\'ll love it !';
        $date = new \DateTime();
        $dateU = $date->format('Y-m-d\TH:i:s\Z');

        $feed->addEntry($title, $link, $summary, $dateU);

        $feed->create();

        return $name;
    }
    private function readSimpleRSS()
    {
        $filename = $this->createSimpleRSS();
        $file = new \SplFileObject($filename, "r");
        $contentToTest = $file->fread($file->getSize());

        $filename = 'SimpleRSS.rss';
        $file = new \SplFileObject($filename, "r");
        $content = $file->fread($file->getSize());

        return array($contentToTest, $content);
    }

    private function readComplexRSS()
    {
        $filename = $this->createComplexRSS();
        $file = new \SplFileObject($filename, "r");
        $contentToTest = $file->fread($file->getSize());

        $filename = 'ComplexRSS.rss';
        $file = new \SplFileObject($filename, "r");
        $content = $file->fread($file->getSize());

        return array($contentToTest, $content);
    }
    public function testSimpleRSS()
    {
        /**
         * You have to check manually if the test passed (simply by clicking
         * on "Click to see difference" on PhpStorm) because the RSS insert the
         * date on the file so it can't be equals to the original file :)
         */
        $simpleRSS = $this->readSimpleRSS();

        $this->assertEquals($simpleRSS[0], $simpleRSS[1]);
    }

    public function testComplexRSS()
    {
        /**
         * You have to check manually if the test passed (simply by clicking
         * on "Click to see difference" on PhpStorm) because the RSS insert the
         * date on the file so it can't be equals to the original file :)
         */

        $complexRSS = $this->readComplexRSS();

        $this->assertEquals($complexRSS[0], $complexRSS[1]);
    }
}

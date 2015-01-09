<?php
/**
 * Created by PhpStorm.
 * User: thomasmunoz
 * Date: 30/12/14
 * Time: 22:33
 */

namespace SwagFramework\XML;

/**
 * This class is designed to create a simple Atom feed
 * source : http://atomenabled.org/developers/syndication/ (official Atom developper platform)
 * Class RSS
 * @package SwagFramework\XML
 */
class RSS extends XML
{
    /**
     * The url of the website for the rss feed
     * @var string
     */
    private $url;

    /**
     * The title of the rss feed
     * @var string
     */
    private $title;

    /**
     * The URL of the feed image
     * @var string
     */
    private $img;

    /**
     * Author of the RSS feed (name of the website for example)
     * @var string
     */
    private $author;
    /**
     * The content of the RSS feed
     * @var array
     */
    private $document = array();

    /**
     * Array of all the entries in the RSS feed (articles for example)
     * @var array
     */
    private $entries = array();

    /**
     * Date of creation of the feed
     * @var string
     */
    private $date;

    public function __construct($name, $url, $title, $img, $author)
    {
        $this->url = $url;
        $this->title = $title;
        $this->img = $img;
        $this->author = $author;

        $date = new \DateTime();
        $this->date = $date->format('Y-m-d\TH:i:s\Z');

        $this->initEntries();
        parent::__construct($name, 'xml version="1.0" encoding="utf-8"');
    }

    public function initEntries()
    {
        $this->entries = array(
            array(
                'title' => 'title',
                'content' => $this->title
            ),
            array(
                'title' => 'atom:link',
                'option' => 'href="' . $this->url . '" rel="self" type="application/rss+xml"'
            ),
            array(
                'title' => 'updated',
                'content' => $this->date
            ),
            array(
                'title' => 'author',
                'content' => array(
                    'title' => 'name',
                    'content' => $this->author
                )
            )
        );
    }

    public function create()
    {
        $this->document = array(
            'title' => 'feed',
            'option' => 'xmlns="http://www.w3.org/2005/Atom" xml:lang="fr"',
            'content' => $this->entries
        );
        $this->generate();
    }

    private function generate()
    {
        $this->write($this->document);
    }

    public function addEntry($title, $link, $summary, $date)
    {
        $this->entries [] = array(
            'title' => 'entry',
            'content' => array(
                array(
                    'title' => 'title',
                    'content' => $title
                ),
                array(
                    'title' => 'link',
                    'option' => 'href="' . $link . '"'
                ),
                array(
                    'title' => 'updated',
                    'content' => $date
                ),
                array(
                    'title' => 'summary',
                    'content' => $summary
                ),
            )
        );


    }

}
<?php
/**
 * Created by PhpStorm.
 * User: sknz
 * Date: 13/01/15
 * Time: 15:52
 */

namespace SwagFramework\Config;

use SwagFramework\Exceptions\FileNotFoundException;
use SwagFramework\Exceptions\MissingConfigEntryException;

class ConfigFileParser
{
    /**
     * @var array
     * @brief
     */
    private $jsonData;

    /**
     * @var string
     * @brief Name of the config file
     */
    private $fileName;

    /**
     * @param $fileName string name of the config file
     * @throws FileNotFoundException
     */
    public function __construct($fileName)
    {
        if (!file_exists($fileName)) {
            throw new FileNotFoundException($fileName);
        }

        $fileContent = file_get_contents($fileName);
        $this->jsonData = json_decode($fileContent, true);
        if ($this->jsonData == null) {
            throw new FileNotFoundException($this->fileName);
        }
    }

    public function getEntry($entryName)
    {
        if (!array_key_exists($entryName, $this->jsonData)) {
            throw new MissingConfigEntryException($this->fileName, $entryName);
        }

        return $this->jsonData[$entryName];
    }
}
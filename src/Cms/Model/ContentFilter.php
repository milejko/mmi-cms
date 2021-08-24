<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Model;

/**
 * Klasa filtrowania contentu CMS
 */
class ContentFilter
{

    /**
     * Content HTML
     * @var string
     */
    private $_content;

    /**
     * Konstruktor
     * @param string $content
     */
    public function __construct($content)
    {
        //przypisanie contentu
        $this->_content = $content;
    }

    /**
     * Zwraca przefiltrowany content
     * @return string
     */
    public function getFilteredContent()
    {
        //zastępowanie ścieżek "świeżymi renderami"
        preg_replace_callback('/\/data\/[a-f0-9]\/[a-f0-9]\/[a-f0-9]\/[a-f0-9]\/(scalecrop|scalex|scaley|default)\/([0-9x]{0,10})\/([a-f0-9]{32}\.[a-zA-Z0-9]{1,4})/', [&$this, '_refreshThumbs'], $this->_content);
        return $this->_content;
    }

    /**
     * Odświeża thumby
     * @param array $matches
     * @return string
     */
    private function _refreshThumbs(array $matches)
    {
        //zwraca ścieżkę (skaluje w razie potrzeby)
        return (new FileSystemModel($matches[3]))->getPublicPath($matches[1], $matches[2]);
    }

}

<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Config;

/**
 * Klasa konfiguracji obsługi kategorii Cms
 */
class CategoryConfig
{

    /**
     * Nazwa klasy do weryfikacji możliwości buforowania danej kategorii
     * @var string
     */
    public $bufferingAllowedClass;

    /**
     * Pozwala usuwać kategorie z wersjami archiwalnymi
     * @var bool
     */
    public $allowDeleteWithVersions = true;

}

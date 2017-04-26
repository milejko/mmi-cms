<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Form\Element;

/**
 * Element utrudniający wysłanie formularza robotom, poprzez dodanie wartości przepisywanej JS'em
 */
class Antirobot extends \Mmi\Form\Element\Hidden
{

    /**
     * Ignorowanie tego pola, pole obowiązkowe, automatyczna walidacja
     */
    public function __construct($name)
    {
        parent::__construct($name);
        $this->setIgnore()
            ->setRequired()
            ->addValidator(new \Cms\Validator\Antirobot);
    }

    /**
     * Buduje pole
     * @return string
     */
    public function fetchField()
    {
        $this->setValue(\Cms\Validator\Antirobot::generateCrc());
        return parent::fetchField();
    }

}

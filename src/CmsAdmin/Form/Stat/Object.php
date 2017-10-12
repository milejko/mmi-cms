<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Form\Stat;

use Cms\Form\Element;

/**
 * Obiekt wyboru statystyki do podglądu
 */
class Object extends \Cms\Form\Form
{

    /**
     * Konfiguracja formularza
     */
    public function init()
    {
        //obiekt
        $this->addElement((new Element\Select('object'))
                ->setLabel('statystyka')
                ->setValue($this->getOption('object'))
                ->setMultioptions([null => '---'] + (new \Cms\Orm\CmsStatLabelQuery)->orderAsc('label')->findPairs('object', 'label')));

        //rok
        $this->addElement((new Element\Select('year'))
                ->setLabel('rok')
                ->setValue($this->getOption('year'))
                ->setMultioptions([date('Y') - 1 => date('Y') - 1, date('Y') => date('Y')]));

        //miesiąc
        $this->addElement((new Element\Select('month'))
                ->setLabel('miesiąc')
                ->setValue($this->getOption('month'))
                ->setMultioptions($this->_getMonthMultioptions()));
    }

    /**
     * Zwraca miesiące rzymskie
     * @return array
     */
    private function _getMonthMultioptions()
    {
        //budowa tabeli
        return [1 => 'I',
            2 => 'II',
            3 => 'III',
            4 => 'IV',
            5 => 'V',
            6 => 'VI',
            7 => 'VII',
            8 => 'VIII',
            9 => 'IX',
            10 => 'X',
            11 => 'XI',
            12 => 'XII',
        ];
    }

}

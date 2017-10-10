<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Form;

use Cms\Form\Element;

/**
 * Formularz harmonogramu
 */
class Cron extends \Mmi\Form\Form
{

    public function init()
    {

        //nazwa zadania
        $this->addElement((new Element\Text('name'))
            ->setLabel('nazwa zadania')
            ->setRequired()
            ->addValidator(new \Mmi\Validator\StringLength([0, 50])));

        //opis
        $this->addElement((new Element\Textarea('description'))
            ->setRequired()
            ->addValidator(new \Mmi\Validator\NotEmpty([]))
            ->setLabel('Opis'));

        //minuta
        $this->addElement((new Element\Text('minute'))
            ->setLabel('Minuta')
            ->setDescription('minuta (0 - 59) lub np ( */5 wykonaj co 5 minut), (10,20 w dziesiątej i dwudziestej minucie godziny) , ( * w każdej minucie)')
            ->setRequired()
            ->addValidator(new \Mmi\Validator\NotEmpty([])));

        //godzina
        $this->addElement((new Element\Text('hour'))
            ->setLabel('Godzina')
            ->setDescription('godzina (0 - 23)')
            ->setRequired()
            ->addValidator(new \Mmi\Validator\NotEmpty([])));

        //dzień miesiąca
        $this->addElement((new Element\Text('dayOfMonth'))
            ->setLabel('Dzień miesiąca')
            ->setDescription('dzień miesiąca (1 - 31)')
            ->setRequired()
            ->addValidator(new \Mmi\Validator\NotEmpty([])));

        //miesiąc
        $this->addElement((new Element\Text('month'))
            ->setLabel('Miesiąc')
            ->setDescription('miesiąc (1 - 12)')
            ->setRequired()
            ->addValidator(new \Mmi\Validator\NotEmpty([])));

        //dzień tygodnia
        $this->addElement((new Element\Text('dayOfWeek'))
            ->setLabel('Dzień tygodnia')
            ->setDescription('dzień tygodnia (1 - 7) (Poniedziałek=1, Wtorek=2,..., Niedziela=7)')
            ->setRequired()
            ->addValidator(new \Mmi\Validator\NotEmpty([])));

        //obiekt
        $value = $this->getRecord() ? ('module=' . $this->getRecord()->module .
            '&controller=' . $this->getRecord()->controller .
            '&action=' . $this->getRecord()->action) : null;

        $options = [null => '---'];
        foreach (\CmsAdmin\Model\Reflection::getOptionsWildcard(3) as $paramString => $label) {
            $mvcParams = [];
            parse_str($paramString, $mvcParams);
            if ($mvcParams['controller'] == 'cron') {
                $options[$paramString] = $label;
            }
        }

        //system object
        $this->addElement((new Element\Select('mvcParams'))
            ->setLabel('Obiekt CMS')
            ->setDescription('Istniejące obiekty CMS')
            ->setRequired()
            ->addValidator(new \Mmi\Validator\NotEmpty([]))
            ->setMultioptions($options)
            ->setOption('id', 'objectId')
            ->setValue($value));

        //aktywny
        $this->addElement((new Element\Checkbox('active'))
            ->setLabel('Aktywny')
            ->setChecked()
            ->setRequired()
            ->addValidator(new \Mmi\Validator\NotEmpty([])));

        //zapis
        $this->addElement((new Element\Submit('submit'))
            ->setLabel('zapisz zadanie'));
    }

    /**
     * Parsowanie parametrów przed zapisem
     * @return boolean
     */
    public function beforeSave()
    {
        $mvcParams = [];
        //parsowanie mvcParams
        parse_str($this->getElement('mvcParams')->getValue(), $mvcParams);
        //zapis do obiektu
        $this->getRecord()->module = isset($mvcParams['module']) ? $mvcParams['module'] : null;
        $this->getRecord()->controller = isset($mvcParams['controller']) ? $mvcParams['controller'] : null;
        $this->getRecord()->action = isset($mvcParams['action']) ? $mvcParams['action'] : null;
    }

}

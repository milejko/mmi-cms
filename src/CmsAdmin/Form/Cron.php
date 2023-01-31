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
use CmsAdmin\Model\Reflection;

/**
 * Formularz harmonogramu
 */
class Cron extends \Cms\Form\Form
{
    public function init()
    {
        //nazwa zadania
        $this->addElement((new Element\Text('name'))
            ->setLabel('form.cron.name.label')
            ->setRequired()
            ->addValidator(new \Mmi\Validator\StringLength([0, 50])));

        //opis
        $this->addElement((new Element\Textarea('description'))
            ->setRequired()
            ->addValidator(new \Mmi\Validator\NotEmpty())
            ->setLabel('form.cron.description.label'));

        //minuta
        $this->addElement((new Element\Text('minute'))
            ->setLabel('form.cron.minute.label')
            ->setDescription('form.cron.minute.description')
            ->setRequired()
            ->addValidator(new \Mmi\Validator\NotEmpty()));

        //godzina
        $this->addElement((new Element\Text('hour'))
            ->setLabel('form.cron.hour.label')
            ->setDescription('form.cron.hour.description')
            ->setRequired()
            ->addValidator(new \Mmi\Validator\NotEmpty()));

        //dzień miesiąca
        $this->addElement((new Element\Text('dayOfMonth'))
            ->setLabel('form.cron.dayOfMonth.label')
            ->setDescription('form.cron.dayOfMonth.description')
            ->setRequired()
            ->addValidator(new \Mmi\Validator\NotEmpty()));

        //miesiąc
        $this->addElement((new Element\Text('month'))
            ->setLabel('form.cron.month.label')
            ->setDescription('form.cron.month.description')
            ->setRequired()
            ->addValidator(new \Mmi\Validator\NotEmpty()));

        //dzień tygodnia
        $this->addElement((new Element\Text('dayOfWeek'))
            ->setLabel('form.cron.dayOfWeek.label')
            ->setDescription('form.cron.dayOfWeek.description')
            ->setRequired()
            ->addValidator(new \Mmi\Validator\NotEmpty()));

        //obiekt
        $value = $this->getRecord() ? ('module=' . $this->getRecord()->module .
            '&controller=' . $this->getRecord()->controller .
            '&action=' . $this->getRecord()->action) : null;

        $options = [null => '---'];
        foreach ((new Reflection())->getOptionsWildcard(3) as $paramString => $label) {
            $mvcParams = [];
            parse_str($paramString, $mvcParams);
            if ($mvcParams['controller'] == 'cron') {
                $options[$paramString] = $label;
            }
        }

        //system object
        $this->addElement((new Element\Select('mvcParams'))
            ->setLabel('form.cron.mvcParams.label')
            ->setDescription('form.cron.mvcParams.description')
            ->setRequired()
            ->addValidator(new \Mmi\Validator\NotEmpty())
            ->setMultioptions($options)
            ->setOption('id', 'objectId')
            ->setValue($value));

        //trwa wykonywanie
        $this->addElement((new Element\Checkbox('lock'))
            ->setLabel('form.cron.lock.label')
            ->setDescription('form.cron.lock.description')
            ->setRequired()
            ->addValidator(new \Mmi\Validator\NotEmpty()));

        //aktywny
        $this->addElement((new Element\Checkbox('active'))
            ->setLabel('form.cron.active.label')
            ->setChecked()
            ->setRequired()
            ->addValidator(new \Mmi\Validator\NotEmpty()));

        //zapis
        $this->addElement((new Element\Submit('submit'))
            ->setLabel('form.cron.submit.label'));
    }

    /**
     * Parsowanie parametrów przed zapisem
     */
    public function beforeSave(): bool
    {
        $mvcParams = [];
        //parsowanie mvcParams
        parse_str($this->getElement('mvcParams')->getValue(), $mvcParams);
        //zapis do obiektu
        $this->getRecord()->module = isset($mvcParams['module']) ? $mvcParams['module'] : null;
        $this->getRecord()->controller = isset($mvcParams['controller']) ? $mvcParams['controller'] : null;
        $this->getRecord()->action = isset($mvcParams['action']) ? $mvcParams['action'] : null;
        return true;
    }
}

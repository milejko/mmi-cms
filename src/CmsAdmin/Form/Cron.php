<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Form;

class Cron extends \Mmi\Form\Form {

	public function init() {
		$this->addElementText('name')
			->setLabel('Nazwa zadania')
			->setRequired()
			->addValidatorStringLength(2, 50);

		$this->addElementTextarea('description')
			->setLabel('Opis')
			->setRequired();

		$this->addElementText('minute')
			->setLabel('Minuta')
			->setDescription('minuta (0 - 59) lub np ( */5 wykonaj co 5 minut), (10,20 w dziesiątej i dwudziestej minucie godziny) , ( * w każdej minucie)')
			->setRequired();

		$this->addElementText('hour')
			->setLabel('Godzina')
			->setDescription('godzina (0 - 23)')
			->setRequired();

		$this->addElementText('dayOfMonth')
			->setLabel('Dzień miesiąca')
			->setDescription('dzień miesiąca (1 - 31)')
			->setRequired();

		$this->addElementText('month')
			->setLabel('Miesiąc')
			->setDescription('miesiąc (1 - 12)')
			->setRequired();

		$this->addElementText('dayOfWeek')
			->setLabel('Dzień tygodnia')
			->setDescription('dzień tygodnia (1 - 7) (Poniedziałek=1, Wtorek=2,..., Niedziela=7)')
			->setRequired();

		$value = null;
		if ($this->_record) {
			$value = $this->_record->module . '_' . $this->_record->controller . '_' . $this->_record->action;
		}

		$options = [null => '---'];
		foreach (\CmsAdmin\Model\Reflection::getActions() as $action) {
			if ($action['controller'] == 'cron') {
				$options[$action['path']] = $action['module'] . ': ' . $action['controller'] . ' - ' . $action['action'];
			}
		}

		//system object
		$this->addElementSelect('object')
			->setLabel('Obiekt CMS')
			->setDescription('Istniejące obiekty CMS')
			->setRequired()
			->addValidatorNotEmpty()
			->setMultioptions($options)
			
			->setOption('id', 'objectId')
			->setValue($value);

		$this->addElementCheckbox('active')
			->setLabel('Aktywny')
			->setChecked()
			->setRequired();

		$this->addElementSubmit('btn_save')->setLabel('zapisz zadanie');
	}

}

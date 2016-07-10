<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Form;

/**
 * Formularz grup atrybutów
 */
class AttributeGroup extends \Mmi\Form\Form {

	public function init() {

		//nazwa
		$this->addElementText('name')
			->setLabel('nazwa')
			->setRequired()
			->addFilterStringTrim()
			->addValidatorStringLength(2, 128);

		//opis
		$this->addElementTextarea('description')
			->setLabel('opis')
			->addFilterStringTrim();

		//atrybuty (lista
		$this->addElementMultiCheckbox('attributes')
			->setMultioptions((new \Cms\Orm\CmsAttributeQuery)->findPairs('id', 'name'))
			->setLabel('atrybuty');
		
		//zapis
		$this->addElementSubmit('submit')
			->setLabel('zapisz');
	}

}

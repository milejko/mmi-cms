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
 * Klasa formularza ACL
 */
class Acl extends \Mmi\Form\Form {

	public function init() {

		$this->_record->cmsRoleId = \Mmi\App\FrontController::getInstance()->getRequest()->roleId;

		//parametry MVC
		$this->addElementSelect('mvcParams')
			->setMultioptions(array_merge([null => '---'], \CmsAdmin\Model\Reflection::getOptionsWildcard()));

		//dozwolone/zabronione
		$this->addElementSelect('access')
			->setMultioptions([
				'allow' => 'dozwolone',
				'deny' => 'zabronione'
			]);

		//zapis
		$this->addElementSubmit('submit')
			->setLabel('dodaj regułę');
	}
	
	/**
	 * Parsowanie parametrów przed zapisem
	 * @return boolean
	 */
	public function beforeSave() {
		$mvcParams = [];
		//parsowanie mvcParams
		parse_str($this->getElement('mvcParams')->getValue(), $mvcParams);
		//zapis do obiektu
		$this->getRecord()->module = isset($mvcParams['module']) ? strtolower($mvcParams['module']) : null;
		$this->getRecord()->controller = isset($mvcParams['controller']) ? strtolower($mvcParams['controller']) : null;
		$this->getRecord()->action = isset($mvcParams['action']) ? strtolower($mvcParams['action']) : null;
		return parent::beforeSave();
	}

}

<?php

/**
 * Mmi Framework (https://bitbucket.org/mariuszmilejko/mmicms/)
 * 
 * @link       https://bitbucket.org/mariuszmilejko/mmicms/
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Form;

class Acl extends \Mmi\Form {

	public function init() {

		$this->_record->cmsRoleId = \Mmi\Controller\Front::getInstance()->getRequest()->roleId;

		$this->addElementSelect('object')
			->setMultiOptions(array_merge(['' => '---'], \CmsAdmin\Model\Reflection::getOptionsWildcard()));

		$this->addElementSelect('access')
			->setMultiOptions([
				'allow' => 'dozwolone',
				'deny' => 'zabronione'
			]);

		$this->addElementSubmit('submit')
			->setLabel('dodaj regułę');
	}

}

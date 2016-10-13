<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Form;

class GalleryWidgetForm extends \Cms\Form\Form {

	public function init() {

		$this->addElementPlupload('files')
			->setObject('cmsgallery');

		$this->addElementSubmit('submit')
			->setLabel('zapisz');
	}

}

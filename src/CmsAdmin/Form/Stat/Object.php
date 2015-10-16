<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Form\Stat;

class Object extends \Mmi\Form\Form {

	public function init() {

		$this->addElementSelect('object')
			->setLabel('statystyka')
			->setValue($this->getOption('object'))
			->setMultioptions([null => '---'] + (new \Cms\Orm\CmsStatLabelQuery)->orderAsc('label')->findPairs('object', 'label'));

		$this->addElementSelect('year')
			->setLabel('rok')
			->setValue($this->getOption('year'))
			->setMultioptions([date('Y') - 1 => date('Y') - 1, date('Y') => date('Y')]);

		$view = \Mmi\App\FrontController::getInstance()->getView();

		$this->addElementSelect('month')
			->setLabel('miesiąc')
			->setValue($this->getOption('month'))
			->setMultioptions([1 => $view->getTranslate()->_('styczeń'),
				2 => $view->getTranslate()->_('luty'),
				3 => $view->getTranslate()->_('marzec'),
				4 => $view->getTranslate()->_('kwiecień'),
				5 => $view->getTranslate()->_('maj'),
				6 => $view->getTranslate()->_('czerwiec'),
				7 => $view->getTranslate()->_('lipiec'),
				8 => $view->getTranslate()->_('sierpień'),
				9 => $view->getTranslate()->_('wrzesień'),
				10 => $view->getTranslate()->_('październik'),
				11 => $view->getTranslate()->_('listopad'),
				12 => $view->getTranslate()->_('grudzień'),
			]);
	}

}

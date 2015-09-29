<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin;

/**
 * Kontroler nawigacji
 */
class NavigationController extends Mvc\Controller {

	/**
	 * Lista pozycji
	 */
	public function indexAction() {
		$config = new \Mmi\Navigation\Config();
		\Cms\Model\Navigation::decorateConfiguration($config);
		$this->view->navigation = $config->findById($this->id, true);
	}

	/**
	 * Edycja elementu menu
	 */
	public function editAction() {
		$navRecord = new \Cms\Orm\Navigation\Record($this->id);
		switch ($this->type) {
			case 'link':
				$form = new \CmsAdmin\Form\Page\Link($navRecord);
				break;
			case 'folder':
				$form = new \CmsAdmin\Form\Page\Folder($navRecord);
				break;
			case 'simple':
				$form = new \CmsAdmin\Form\Page\Article($navRecord);
				break;
			default:
				$form = new \CmsAdmin\Form\Page\Cms($navRecord);
				break;
		}
		if ($form->isSaved()) {
			$this->getResponse()->redirect('cmsAdmin', 'navigation', 'index', ['id' => $navRecord->parentId]);
		}
		$this->view->pageForm = $form;
	}

	/**
	 * Usuwanie elementu
	 */
	public function deleteAction() {
		/* @var $record \Cms\Orm\Navigation\Record */
		$record = \Cms\Orm\Navigation\Query::factory()->findPk($this->id);
		if ($record !== null && $record->delete()) {
			$this->getMessenger()->addMessage('Poprawnie usunięto element nawigacyjny', true);
		}
		$this->getResponse()->redirect('cmsAdmin', 'navigation', 'index', ['id' => $record->parentId]);
	}

	/**
	 * Sortowanie ajax elementów
	 * @return string
	 */
	public function sortAction() {
		$this->getResponse()->setTypePlain();
		if (!$this->getPost()->__get('navigation-item')) {
			return $this->view->getTranslate()->_('Przenoszenie nie powiodło się');
		}
		\Cms\Model\Navigation::sortBySerial($this->getPost()->__get('navigation-item'));
		return '';
	}

}

<?php

/**
 * Mmi Framework (https://bitbucket.org/mariuszmilejko/mmicms/)
 * 
 * @link       https://bitbucket.org/mariuszmilejko/mmicms/
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Controller;

/**
 * Kontroler administracji stronami CMS
 */
class Page extends Action {

	public function indexAction() {
		$this->view->grid = new \Cms\Plugin\PageGrid();
	}

	public function editAction() {
		$form = new \CmsAdmin\Form\Page($pageRecord = new \Cms\Orm\Page\Record($this->id));
		if ($form->isSaved()) {
			$this->getResponse()->redirect('cmsAdmin', 'page', 'compose', ['id' => $pageRecord->id]);
		}
		$this->view->pageForm = $form;
	}

	public function composeAction() {
		if (!$this->id || null === ($page = \Cms\Orm\Page\Query::factory()
			->whereId()->equals($this->id)
			->findFirst())) {
			$this->getResponse()->redirect('cmsAdmin', 'page');
		}
		//lista aktywnych widgetow
		$this->view->widgets = \Cms\Orm\Page\Widget\Query::active()->find();

		//skrypty js
		$this->view->headScript()->prependFile($this->view->baseUrl . '/resource/cms/js/jquery/jquery.js');
		$this->view->headScript()->appendFile($this->view->baseUrl . '/resource/cms/js/jquery/ui.js');
		$this->view->headScript()->appendFile($this->view->baseUrl . '/resource/cms/js/page.js');

		//css'y
		$this->view->headLink()->appendStyleSheet($this->view->baseUrl . '/default/cms/css/page.css');
		$this->view->headLink()->appendStyleSheet($this->view->baseUrl . '/default/cms/css/fonts/fontawesome/css/font-awesome.css');
		$this->view->headStyle()->appendStyleFile('default/cms/css/page.css');

		$withWidgets = preg_replace('/(\{widget\(([a-zA-Z1-9\'\,\s\(\=\>]+\))\)\})/', '<div class="composer-widget" data-widget="$2">$2</div>$1', $page->text);

		//ustawianie contentu
		$this->view->setPlaceholder('content', $this->view->render('cms-admin', 'page', 'toolkit') .
			'<div class="cms-page-composer">' . $this->view->renderDirectly($withWidgets) . '</div>');

		//render layoutu
		return $this->view->renderLayout('cms', 'page');
	}

	public function updateAction() {
		if (!$this->getPost()->id || !$this->getPost()->data) {
			return json_encode(['success' => 0]);
		}
		$page = \Cms\Orm\Page\Query::factory()
			->where('id')->equals($this->getPost()->id)
			->findFirst();
		if ($page === null) {
			return json_encode(['success' => 0]);
		}
		$page->text = htmlspecialchars_decode($this->getPost()->data);
		$page->save();
		return json_encode(['success' => 1]);
	}

	public function loadAction() {
		$this->getResponse()->setDebug(false);
		if (!$this->getPost()->id) {
			return json_encode(['success' => 0]);
		}
		$page = \Cms\Orm\Page\Query::factory()
			->whereId()->equals($this->getPost()->id)
			->findFirst();
		if ($page === null) {
			return json_encode(['sucess' => 0]);
		}
		//parsowanie widgetow do postaci zjadalnej przez composer
		$parsed = preg_replace('/\{widget\(([a-zA-Z1-9\'\,\s\(\=\>]+\))\)\}/', '<div class="widget" data-widget="$1">Widget</div>', $page->text);
		return $parsed;
	}

	public function deleteAction() {
		if (null !== ($record = \Cms\Orm\Page\Query::factory()->findPk($this->id)) && $record->delete()) {
			$this->getHelperMessenger()->addMessage('Strona usunięta poprawnie');
		}
		$this->getResponse()->redirect('cmsAdmin', 'page');
	}

}

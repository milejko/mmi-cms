<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2017 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin;

/**
 * Kontroler kontaktów
 */
class CategoryAclController extends Mvc\Controller {

	/**
	 * Akcja ustawiania uprawnień na kategoriach
	 */
	public function indexAction() {
		$this->view->roles = (new \Cms\Orm\CmsRoleQuery)->find();
		//jeśli niewybrana rola - przekierowanie na pierwszą istniejącą
		if (!$this->roleId && count($this->view->roles)) {
			$this->getResponse()->redirect('cmsAdmin', 'categoryAcl', 'index', ['roleId' => $this->view->roles[0]->id]);
		}
		//formularz edycji uprawnień
		$form = new Form\CategoryAclForm(null, ['roleId' => $this->roleId]);
		//po zapisie
		if ($form->isSaved()) {
			//przekierowanie na zapisaną stronę
			$this->getResponse()->redirect('cmsAdmin', 'categoryAcl', 'index', ['roleId' => $this->roleId]);
		}
		$this->view->categoryAclForm = $form;
	}

}

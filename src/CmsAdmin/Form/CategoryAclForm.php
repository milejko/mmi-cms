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
 * Formularz edycji ACL dla roli
 */
class CategoryAclForm extends \Cms\Form\Form {

	public function init() {



		//drzewo kategorii (dozwolone)
		$this->addElementTree('allow')
			->setLabel('dozwolone kategorie')
			->setMultiple()
			->setValue(implode(';', (new \Cms\Orm\CmsCategoryAclQuery)
					->whereCmsRoleId()->equals($this->getOption('roleId'))
					->andFieldAccess()->equals('allow')
					->findPairs('id', 'cms_category_id')))
			->setStructure(['children' => (new \Cms\Model\CategoryModel)->getCategoryTree()]);

		//drzewo kategorii (zabronione)
		$this->addElementTree('deny')
			->setLabel('zabronione kategorie')
			->setMultiple()
			->setValue(implode(';', (new \Cms\Orm\CmsCategoryAclQuery)
					->whereCmsRoleId()->equals($this->getOption('roleId'))
					->andFieldAccess()->equals('deny')
					->findPairs('id', 'cms_category_id')))
			->setStructure(['children' => (new \Cms\Model\CategoryModel)->getCategoryTree()]);

		$this->addElementSubmit()
			->setLabel('zapisz');
	}

	/**
	 * Zapis uprawnień
	 * @return boolean
	 */
	public function beforeSave() {
		//czyszczenie uprawnień dla roli
		(new \Cms\Orm\CmsCategoryAclQuery)
			->whereCmsRoleId()->equals($this->getOption('roleId'))
			->find()
			->delete();
		//zapis uprawnień "dozwól"
		foreach (explode(';', $this->getElement('allow')->getValue()) as $categoryId) {
			$aclRecord = new \Cms\Orm\CmsCategoryAclRecord;
			$aclRecord->access = 'allow';
			$aclRecord->cmsCategoryId = $categoryId;
			$aclRecord->cmsRoleId = $this->getOption('roleId');
			$aclRecord->save();
		}
		//zapis uprawnień "zabroń"
		foreach (explode(';', $this->getElement('deny')->getValue()) as $categoryId) {
			$aclRecord = new \Cms\Orm\CmsCategoryAclRecord;
			$aclRecord->access = 'deny';
			$aclRecord->cmsCategoryId = $categoryId;
			$aclRecord->cmsRoleId = $this->getOption('roleId');
			$aclRecord->save();
		}
		return true;
	}

}

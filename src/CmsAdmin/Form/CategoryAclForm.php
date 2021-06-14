<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Form;

use Cms\Form\Element;
use Cms\Orm\CmsCategoryQuery;
use CmsAdmin\Model\CategoryAclModel;
use Mmi\App\App;
use Mmi\Cache\CacheInterface;

/**
 * Formularz edycji ACL dla roli
 */
class CategoryAclForm extends \Cms\Form\Form
{

    public function init()
    {
        $tree = (new \Cms\Model\CategoryModel((new CmsCategoryQuery())->whereTemplate()->like($this->getOption('scope') . '%')))->getCategoryTree();
        //drzewo kategorii (dozwolone)
        $this->addElement((new Element\Tree('allow'))
            ->setLabel('form.categoryAcl.allow.label')
            ->setMultiple()
            ->setValue(implode(';', (new \Cms\Orm\CmsCategoryAclQuery)
                ->whereCmsRoleId()->equals($this->getOption('roleId'))
                ->andFieldAccess()->equals('allow')
                ->findPairs('id', 'cms_category_id')))
            ->setStructure(['children' => $tree]));

        //drzewo kategorii (zabronione)
        $this->addElement((new Element\Tree('deny'))
            ->setLabel('form.categoryAcl.deny.label')
            ->setMultiple()
            ->setValue(implode(';', (new \Cms\Orm\CmsCategoryAclQuery)
                ->whereCmsRoleId()->equals($this->getOption('roleId'))
                ->andFieldAccess()->equals('deny')
                ->findPairs('id', 'cms_category_id')))
            ->setStructure(['children' => $tree]));

        $this->addElement((new Element\Submit('submit'))
            ->setLabel('form.categoryAcl.submit.label'));
    }

    /**
     * Zapis uprawnień
     * @return boolean
     */
    public function beforeSave()
    {
        //czyszczenie uprawnień dla roli
        (new \Cms\Orm\CmsCategoryAclQuery)
            ->whereCmsRoleId()->equals($this->getOption('roleId'))
            ->delete();
        //zapis uprawnień "dozwól"
        foreach (explode(';', $this->getElement('allow')->getValue()) as $categoryId) {
            //brak kategorii
            if (!$categoryId) {
                continue;
            }
            $aclRecord = new \Cms\Orm\CmsCategoryAclRecord;
            $aclRecord->access = 'allow';
            $aclRecord->cmsCategoryId = $categoryId;
            $aclRecord->cmsRoleId = $this->getOption('roleId');
            $aclRecord->save();
        }
        //zapis uprawnień "zabroń"
        foreach (explode(';', $this->getElement('deny')->getValue()) as $categoryId) {
            //brak kategorii
            if (!$categoryId) {
                continue;
            }
            $aclRecord = new \Cms\Orm\CmsCategoryAclRecord;
            $aclRecord->access = 'deny';
            $aclRecord->cmsCategoryId = $categoryId;
            $aclRecord->cmsRoleId = $this->getOption('roleId');
            $aclRecord->save();
        }
        //usunięcie cache
        App::$di->get(CacheInterface::class)->remove(CategoryAclModel::CACHE_KEY);
        return true;
    }
}

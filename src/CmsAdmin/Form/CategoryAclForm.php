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
use Cms\Model\SkinsetModel;
use Cms\Orm\CmsCategoryQuery;
use CmsAdmin\Model\CategoryAclModel;
use Mmi\App\App;
use Mmi\Cache\CacheInterface;

/**
 * Formularz edycji ACL dla roli
 */
class CategoryAclForm extends \Cms\Form\Form
{
    public const SCOPE_CONFIG_OPTION_NAME = 'scope';
    private SkinsetModel $skinsetModel;

    public function init()
    {
        $this->skinsetModel = $this->getOption(SkinsetModel::class);

        $tree = (new \Cms\Model\CategoryModel((new CmsCategoryQuery())
            ->whereTemplate()->like($this->getOption(self::SCOPE_CONFIG_OPTION_NAME) . '%')))
            ->getCategoryTree();

        //drzewo kategorii (dozwolone)
        $this->addElement((new Element\Tree('allow'))
            ->setLabel('form.categoryAcl.allow.label')
            ->setMultiple()
            ->setValue(implode(';', (new \Cms\Orm\CmsCategoryAclQuery())
                ->whereRole()->equals($this->getOption('role'))
                ->andFieldAccess()->equals('allow')
                ->findPairs('id', 'cms_category_id')))
            ->setStructure(['children' => $this->getFilteredTree($tree)]));

        //drzewo kategorii (zabronione)
        $this->addElement((new Element\Tree('deny'))
            ->setLabel('form.categoryAcl.deny.label')
            ->setMultiple()
            ->setValue(implode(';', (new \Cms\Orm\CmsCategoryAclQuery())
                ->whereRole()->equals($this->getOption('role'))
                ->andFieldAccess()->equals('deny')
                ->findPairs('id', 'cms_category_id')))
            ->setStructure(['children' => $this->getFilteredTree($tree)]));

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
        (new \Cms\Orm\CmsCategoryAclQuery())
            ->join('cms_category')->on('cms_category_id')
            ->where('template', 'cms_category')->like($this->getOption(self::SCOPE_CONFIG_OPTION_NAME) . '%')
            ->whereCmsRoleId()->equals($this->getOption('roleId'))
            ->delete();
        //zapis uprawnień "dozwól"
        foreach (explode(';', $this->getElement('allow')->getValue()) as $categoryId) {
            //brak kategorii
            if (!$categoryId) {
                continue;
            }
            $aclRecord = new \Cms\Orm\CmsCategoryAclRecord();
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
            $aclRecord = new \Cms\Orm\CmsCategoryAclRecord();
            $aclRecord->access = 'deny';
            $aclRecord->cmsCategoryId = $categoryId;
            $aclRecord->cmsRoleId = $this->getOption('roleId');
            $aclRecord->save();
        }
        //usunięcie cache
        App::$di->get(CacheInterface::class)->remove(CategoryAclModel::CACHE_KEY);
        return true;
    }

    private function getFilteredTree(array $tree)
    {
        $filteredTree = [];
        foreach ($tree as $category) {
            //checking if template is compatible
            if (strpos($category['template'], '/') && null === $template = $this->skinsetModel->getTemplateConfigByKey($category['template'])) {
                continue;
            }
            //allow by default
            $category['allow'] = true;
            //recursive build
            $category['children'] = $this->getFilteredTree($category['children']);
            $filteredTree[] = $category;
        }
        return $filteredTree;
    }
}

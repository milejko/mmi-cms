<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Form;

use Cms\Form\Element\Submit;
use Cms\Form\Element\Tree;
use Cms\Form\Form;
use Cms\Model\CategoryModel;
use Cms\Orm\CmsCategoryQuery;
use Cms\Orm\CmsCategoryRecord;
use Mmi\Filter\EmptyToNull;

/**
 * Formularz edycji szegółów kategorii
 * @method \Cms\Orm\CmsCategoryRecord getRecord()
 */
class CategoryMoveForm extends Form
{

    public function init()
    {
        $tree = (new CategoryModel(new CmsCategoryQuery()))->getCategoryTree();
        //drzewo kategorii (dozwolone)
        $this->addElement((new Tree('parentId'))
            ->setLabel('form.categoryAcl.allow.label')
            ->addFilter(new EmptyToNull())
            ->setMultiple(false)
            ->setStructure(['children' => [['id'=> '0', 'name' => '', 'children' => $this->getFilteredTree($tree, $this->getRecord())]]]));

        $this->addElement((new Submit('submit'))->setLabel('sumit'));
    }

    private function getFilteredTree(array $tree, CmsCategoryRecord $categoryRecord = null): array
    {
        $filteredTree = [];
        foreach ($tree as $category) {
            if ($category['path'] == $categoryRecord->path) {
                $category['allow'] = false;    
            }
            if ($category['template']) {
                $category['allow'] = false;
            }
            
            $category['children'] = $this->getFilteredTree($category['children'], $categoryRecord);
            $filteredTree[] = $category;
        }
        return $filteredTree;
    }

}
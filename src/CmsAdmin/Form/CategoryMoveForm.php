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
use CmsAdmin\Model\CategoryAcl;
use Mmi\Filter\EmptyToNull;
use Mmi\Security\AuthInterface;

/**
 * Formularz edycji szegółów kategorii
 * @method \Cms\Orm\CmsCategoryRecord getRecord()
 */
class CategoryMoveForm extends Form
{
    public const SCOPE_CONFIG_OPTION_NAME = 'scope';

    private CategoryAcl $acl;
    private AuthInterface $auth;

    public function init()
    {
        //injections
        $this->acl = (new \CmsAdmin\Model\CategoryAclModel)->getAcl();
        $this->auth = $this->getOption(AuthInterface::class);

        $tree = (new CategoryModel((new CmsCategoryQuery())
            ->whereTemplate()->like(self::SCOPE_CONFIG_OPTION_NAME . '%')
            ))->getCategoryTree();
        //drzewo kategorii (dozwolone)
        $this->addElement((new Tree('parentId'))
            ->setLabel('form.categoryMove.parentId.label')
            ->addFilter(new EmptyToNull())
            ->setMultiple(false)
            ->setStructure(['children' => [['id'=> '0', 'name' => '', 'children' => $this->getFilteredTree($tree, $this->getRecord())]]]));

        $this->addElement((new Submit('submit'))->setLabel('form.categoryMove.save.label'));
    }

    private function getFilteredTree(array $tree, CmsCategoryRecord $categoryRecord = null): array
    {
        $filteredTree = [];
        foreach ($tree as $category) {
            if ($category['id'] == $categoryRecord->id) {
                $category['allow'] = false;
            }
            if (substr($category['path'], 0, strlen($categoryRecord->path) + 1) == $categoryRecord->path . '/') {
                $category['allow'] = false;    
            }
            if ($category['template']) {
                $category['allow'] = false;
            }
            if (!$this->acl->isAllowed($this->auth->getRoles(), $category['id'])) {
                $category['allow'] = false;
            }
            $category['children'] = $this->getFilteredTree($category['children'], $categoryRecord);
            $filteredTree[] = $category;
        }
        return $filteredTree;
    }

}
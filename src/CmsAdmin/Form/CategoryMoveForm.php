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
use Cms\Model\SkinsetModel;
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
    private SkinsetModel $skinsetModel;

    private const PARENT_ID_KEY = 'parentId';

    public function init()
    {
        //injections
        $this->acl = (new \CmsAdmin\Model\CategoryAclModel())->getAcl();
        $this->auth = $this->getOption(AuthInterface::class);
        $this->skinsetModel = $this->getOption(SkinsetModel::class);

        $tree = (new CategoryModel((new CmsCategoryQuery())
            ->whereTemplate()->like($this->getOption(self::SCOPE_CONFIG_OPTION_NAME) . '%')))->getCategoryTree();
        //drzewo kategorii (dozwolone)
        $this->addElement((new Tree(self::PARENT_ID_KEY))
            ->setLabel('form.categoryMove.parentId.label')
            ->addFilter(new EmptyToNull())
            ->setMultiple(false)
            ->setStructure(['children' => [['id'=> '0', 'name' => '', 'allow' => $this->skinsetModel->getTemplateConfigByKey($this->getRecord()->template)->getAllowedOnRoot(), 'children' => $this->getFilteredTree($tree, $this->getRecord())]]]));

        $this->addElement((new Submit('submit'))->setLabel('form.categoryMove.save.label'));
    }

    private function getFilteredTree(array $tree, CmsCategoryRecord $categoryRecord = null): array
    {
        $filteredTree = [];
        $categoryTemplateConfig = $this->skinsetModel->getTemplateConfigByKey($categoryRecord->template);
        foreach ($tree as $category) {
            //checking if template is compatible
            if (strpos($category['template'], '/') && null === $template = $this->skinsetModel->getTemplateConfigByKey($category['template'])) {
                continue;
            }
            //checking if category isn't self
            if ($category['id'] == $categoryRecord->id) {
                continue;
            }
            //allow by default
            $category['allow'] = true;
            //target category template doesn't allow required category
            if (!in_array($categoryTemplateConfig->getKey(), $template->getCompatibleChildrenKeys())) {
                $category['allow'] = false;
            }
            //acl check
            if (!$this->acl->isAllowed($this->auth->getRoles(), $category['id'])) {
                $category['allow'] = false;
            }
            //recursive build
            $category['children'] = $this->getFilteredTree($category['children'], $categoryRecord);
            $filteredTree[] = $category;
        }
        return $filteredTree;
    }

    public function validator()
    {
        //try to move up to root and root is not allowed
        if (!$this->getElement(self::PARENT_ID_KEY)->getValue() && !$this->skinsetModel->getTemplateConfigByKey($this->getRecord()->template)->getAllowedOnRoot()) {
            $this->getElement(self::PARENT_ID_KEY)->addError('form.categoryMove.parentId.error');
            return false;
        }
        return true;
    }
}

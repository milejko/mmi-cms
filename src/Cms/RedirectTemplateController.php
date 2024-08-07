<?php

/**
 * Multiportals CMS instance (content repository)
 *
 * @copyright Copyright (c) 2021 Nowa Era (http://nowaera.pl) All rights reserved.
 * @license   Proprietary and confidential
 */

declare(strict_types=1);

namespace Cms;

use Cms\Api\LinkData;
use Cms\Form\Element;
use Cms\Model\CategoryModel;
use Cms\Orm\CmsCategoryQuery;
use CmsAdmin\Form\CategoryForm;
use Mmi\Filter;
use Mmi\Validator;

class RedirectTemplateController extends AbstractTemplateController
{
    private const REDIRECT_URI = 'redirectUri';
    private const REDIRECT_CATEGORY_ID = 'redirectCategoryId';
    private const REDIRECT_TYPE = 'redirectType';
    private const REDIRECT_TYPE_OPTIONS = [
        LinkData::REL_EXTERNAL => 'form.category.redirectType.external.label',
        LinkData::REL_INTERNAL => 'form.category.redirectType.internal.label',
    ];

    public function decorateEditForm(CategoryForm $categoryForm): void
    {
        parent::decorateEditForm($categoryForm);

        $redirectType = LinkData::REL_EXTERNAL;
        $redirectCategoryId = null;
        $redirectUri = $categoryForm->getRecord()->redirectUri;

        if ($redirectUri && preg_match('/^' . str_replace('/', '\/', LinkData::INTERNAL_REDIRECT_PREFIX) . '(\d+)/', $redirectUri, $matches)) {
            $redirectType = LinkData::REL_INTERNAL;
            $redirectCategoryId = $matches[1];
            $redirectUri = LinkData::INTERNAL_REDIRECT_PREFIX . $redirectCategoryId;
        }

        $tree = (new CategoryModel(
            (new CmsCategoryQuery())
                ->whereTemplate()->like($this->cmsCategoryRecord->getScope() . '/%')
        ))->getCategoryTree();

        $categoryForm->addElement(
            (new Element\Radio(self::REDIRECT_TYPE))
                ->setLabel('form.category.redirectType.label')
                ->setMultioptions(self::REDIRECT_TYPE_OPTIONS)
                ->setValue($redirectType)
        );

        $categoryForm->removeElement(self::REDIRECT_URI)->addElement(
            (new Element\Text(self::REDIRECT_URI))
                ->setLabel('form.category.redirect.label')
                ->setRequired()
                ->addValidator(new Validator\Url())
                ->addFilter(new Filter\StringTrim())
                ->setValue($redirectUri)
        );

        $categoryForm->addElement(
            (new Element\Tree(self::REDIRECT_CATEGORY_ID))
                ->setLabel('form.category.redirectCategoryId.label')
                ->setMultiple(false)
                ->setStructure(['children' => $tree])
                ->setValue($redirectCategoryId)
        );
    }

    public function beforeSaveEditForm(CategoryForm $categoryForm): void
    {
        parent::beforeSaveEditForm($categoryForm);

        if (LinkData::REL_INTERNAL !== $categoryForm->getElement(self::REDIRECT_TYPE)->getValue()) {
            $categoryForm->getElement(self::REDIRECT_CATEGORY_ID)
                ->setValue(null);
            return;
        }

        $categoryForm->getElement(self::REDIRECT_URI)
            ->removeValidator(Validator\Url::class)
            ->setValue(LinkData::INTERNAL_REDIRECT_PREFIX . $categoryForm->getElement(self::REDIRECT_CATEGORY_ID)->getValue());
    }
}

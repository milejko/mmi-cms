<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2023 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Form;

use Cms\Form\Element;
use Cms\Form\Form;
use Mmi\Filter\StringTrim;
use Mmi\Validator\NotEmpty;

class CategorySearch extends Form
{
    public const FIELD_QUERY_NAME = 'query';
    public const FIELD_FILTER_NAME = 'filter';

    public const FIELD_FILTER_OPTION_ALL = 'all';
    public const FIELD_FILTER_OPTION_NAME = 'name';
    public const FIELD_FILTER_OPTION_URI = 'uri';
    public const FIELD_FILTER_OPTION_BREADCRUMBS = 'breadcrumbs';

    public const FIELD_FILTER_OPTIONS = [
        self::FIELD_FILTER_OPTION_ALL => 'form.categorySearch.filter.option.all',
        self::FIELD_FILTER_OPTION_NAME => 'form.categorySearch.filter.option.name',
        self::FIELD_FILTER_OPTION_URI => 'form.categorySearch.filter.option.uri',
        self::FIELD_FILTER_OPTION_BREADCRUMBS => 'form.categorySearch.filter.option.breadcrumbs',
    ];

    public function init()
    {
        $this->addElement(
            (new Element\Text(self::FIELD_QUERY_NAME))
                ->setLabel('form.categorySearch.query.label')
                ->setPlaceholder('form.categorySearch.query.placeholder')
                ->setRequired(true)
                ->addValidator(new NotEmpty())
                ->addFilter(new StringTrim())
        );

        $this->addElement(
            (new Element\Select(self::FIELD_FILTER_NAME))
                ->setLabel('form.categorySearch.filter.label')
                ->setRequired(true)
                ->addValidator(new NotEmpty())
                ->setMultioptions(self::FIELD_FILTER_OPTIONS)
                ->setValue(self::FIELD_FILTER_OPTION_ALL)
        );

        $this->addElement(
            (new Element\Submit(''))
                ->setLabel('form.categorySearch.submit.label')
        );
    }
}

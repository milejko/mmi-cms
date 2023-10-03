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
use Cms\Form\Form;
use Cms\Validator\TagUnique;
use Mmi\Filter\StringTrim;
use Mmi\Validator\NotEmpty;
use Mmi\Validator\StringLength;
use Psr\Container\ContainerInterface;

/**
 * Formularz tagów
 */
class Tag extends Form
{
    private const TAG_STRING_LENGTH = [
        'min' => 1,
        'max' => 35,
    ];

    public function init()
    {
        /** @var ContainerInterface $container */
        $container = $this->getOption(ContainerInterface::class);

        $cmsLanguageList = explode(',', $container->get('cms.language.list'));

        //język
        $this->addElement(
            (new Element\Select('lang'))
                ->setLabel('form.tag.lang.label')
                ->setRequired()
                ->addValidator(new NotEmpty())
                ->setMultioptions(array_combine($cmsLanguageList, $cmsLanguageList))
        );

        //tag
        $this->addElement(
            (new Element\Text('tag'))
                ->setLabel('form.tag.tag.label')
                ->setRequired()
                ->addFilter(new StringTrim())
                ->addValidator(new StringLength(self::TAG_STRING_LENGTH))
                ->addValidator(new TagUnique([$this]))
        );

        //zapis
        $this->addElement(
            (new Element\Submit('submit'))
                ->setLabel('form.tag.submit.label')
        );
    }
}

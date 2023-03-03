<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2023 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Tests\Unit\Cms\Form\Element;

use PHPUnit\Framework\TestCase;
use Mmi\Form\Element\MultiCheckbox;
use Tests\Mock\Cms\Form\SampleForm;

class MultiCheckboxTest extends TestCase
{
    public function test(): void
    {
        $sampleForm = new SampleForm;
        $element = (new MultiCheckbox('test'))
            ->setLabel('test label')
            ->setMultioptions(['test' => 'test']);
        $sampleForm->addElement($element);
        self::assertEquals('<ul id="tests-mock-cms-form-sampleform-test-list"><li id="tests-mock-cms-form-sampleform-test-test-item"><input type="checkbox" name="tests-mock-cms-form-sampleform[test][]" data-requiredAsterisk="*" data-labelPostfix="" class="field" data-label="test" id="tests-mock-cms-form-sampleform-test-test" value="test"  /><label id="tests-mock-cms-form-sampleform-test-test-label" for="tests-mock-cms-form-sampleform-test-test">test</label>' . "\n" .
        '</li></ul>', $element->fetchField());
    }
}

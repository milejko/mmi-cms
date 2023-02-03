<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2023 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Tests\Mock\Cms;

use Cms\AbstractTemplateController;
use Cms\Api\TransportInterface;

class SampleTplInvalidControllerClassMock extends AbstractTemplateController
{
    public function getTransportObject(): TransportInterface
    {
        throw new \Exception('some bug');
        return parent::getTransportObject();
    }
}

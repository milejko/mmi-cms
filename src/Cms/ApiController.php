<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms;

use Cms\App\CmsSkinsetConfig;
use Cms\Model\TemplateModel;
use Mmi\Http\Request;
use Mmi\Mvc\ActionHelper;

/**
 * Kontroler kategorii
 */
class ApiController extends \Mmi\Mvc\Controller
{


    /**
     * @Inject
     * @var CmsSkinsetConfig
     */
    private $cmsSkinsetConfig;

    /**
     * @Inject
     * @var ActionHelper
     */
    private $actionHelper;

    /**
     * Akcja dispatchera kategorii
     */
    public function getCategoryAction(Request $request)
    {
        //pobranie kategorii
        $category = (new Orm\CmsCategoryQuery)->getCategoryByUri($request->uri);
        $this->getResponse()->setTypeJson();
        return json_encode((new TemplateModel($category, $this->cmsSkinsetConfig))->getTransportObject($request));
    }

}

<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms;

/**
 * Kontroler łączący różne instancje CMS
 */
class ConnectorController extends \Mmi\Mvc\Controller
{

    public function init()
    {
        $this->getResponse()->setTypeJson();
    }

    public function importAction()
    {
        
    }

    public function exportContentAction()
    {
        //@TODO uprawnienia
        $exporter = new Model\StructureExporter;
        return json_encode(
            [
                'attributeType' => (new \Cms\Orm\CmsAttributeTypeQuery)->find()->toArray(),
                'attribute' => (new \Cms\Orm\CmsAttributeQuery)->find()->toArray(),
                'attributeRelation' => (new \Cms\Orm\CmsAttributeRelationQuery)->find()->toArray(),
                'attributeValue' => (new \Cms\Orm\CmsAttributeValueQuery)->find()->toArray(),
                'attributeValueRelation' => (new \Cms\Orm\CmsAttributeValueRelationQuery)->find()->toArray(),
                'categoryType' => (new \Cms\Orm\CmsCategoryTypeQuery)->find()->toArray(),
                'category' => (new \Cms\Orm\CmsCategoryQuery)->find()->toArray(),
                'categoryAcl' => (new \Cms\Orm\CmsCategoryAclQuery)->find()->toArray(),
                'categoryRelation' => (new \Cms\Orm\CmsCategoryRelationQuery)->find()->toArray(),
                'categoryWidgets' => (new \Cms\Orm\CmsCategoryWidgetQuery)->find()->toArray(),
                'categoryWidgetCategory' => (new \Cms\Orm\CmsCategoryWidgetCategoryQuery)->find()->toArray(),
            ]
        );
    }

}

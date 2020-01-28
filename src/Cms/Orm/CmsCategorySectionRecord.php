<?php

namespace Cms\Orm;

/**
 * Rekord sekcji
 */
class CmsCategorySectionRecord extends \Mmi\Orm\Record
{

    public $id;
    public $categoryTypeId;
    public $key;
    public $name;
    public $order;
    public $required;

    /**
     * Zwraca listę kompatybilnych widgetów
     * @return CmsCategoryWidgetRecord[]
     */
    public function getCompatibleWidgets()
    {
        return (new CmsCategoryWidgetQuery())
            ->join('cms_category_widget_section')->on('id', 'cms_category_widget_id')
            ->where('cms_category_section_id', 'cms_category_widget_section')->equals($this->id)
            ->find();
    }
}

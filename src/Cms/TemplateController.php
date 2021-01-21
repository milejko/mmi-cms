<?php

namespace Cms;

use Cms\App\CmsSkinsetConfig;
use Cms\Orm\CmsCategoryRecord;
use Cms\Model\TemplateJson;
use Cms\Model\WidgetModel;
use CmsAdmin\Form\CategoryForm;
use Mmi\Mvc\Controller;
use Mmi\Http\Request;
use Mmi\App\App;

/**
 * Abstrakcyjna klasa kontrolera widgetów
 */
abstract class TemplateController extends Controller
{
    /**
     * CMS category record
     * @var CmsCategoryRecord
     */
    private $cmsCategoryRecord;

    /**
     * CMS skinset config
     * @var CmsSkinsetConfig
     */
    private $cmsSkinsetConfig;

    /**
     * Sets the CMS category record
     */
    public function setCategoryRecord(CmsCategoryRecord $cmsCategoryRecord): self
    {
        $this->cmsCategoryRecord = $cmsCategoryRecord;
        return $this;
    }

    public function setSkinsetConfig(CmsSkinsetConfig $cmsSkinsetConfig): self
    {
        $this->cmsSkinsetConfig = $cmsSkinsetConfig;
        return $this;
    }

    /**
     * Gets the CMS category record
     */
    public final function getCategoryRecord(): CmsCategoryRecord
    {
        return $this->cmsCategoryRecord;
    }

    /**
     * Gets the CMS category record
     */
    public final function getSkinsetConfig(): CmsSkinsetConfig
    {
        return $this->cmsSkinsetConfig;
    }

    /**
     * Wyświetlenie szablonu po stronie klienta
     */
    public function displayAction(Request $request)
    {}

    /**
     * Akcja wywoływana przy usuwaniu szablonu
     */
    public function deleteAction(): void
    {}

    /**
     * Zwraca obiekt JSON (na potrzeby API)
     */
    public function getJson(Request $request): TemplateJson
    {
        $templateJson = new TemplateJson;
        $templateJson->widgets = $this->getWidgetJsons($request);
        $templateJson->attributes = $this->cmsCategoryRecord;
        return $templateJson;
    }

    /**
     * Dekoracja formularza edycji
     * @param CategoryForm $categoryForm
     */
    public function decorateEditForm(CategoryForm $categoryForm): void
    {}

    /**
     * Metoda przed zapisem formularza
     * @param CategoryForm $categoryForm
     */
    public function beforeSaveEditForm(CategoryForm $categoryForm): void
    {}

    /**
     * Metoda po zapisie formularza
     * @param CategoryForm $categoryForm
     */
    public function afterSaveEditForm(CategoryForm $categoryForm): void
    {}

    /**
     * Pobiera JSONy widgetów
     */
    protected function getWidgetJsons(Request $request): array
    {
        $widgets = [];
        //getting section skinsets
        foreach ($this->cmsCategoryRecord->getWidgetModel()->getWidgetRelations() as $widgetRelationRecord) {
            $widgets[substr($widgetRelationRecord->widget, 0, strrpos($widgetRelationRecord->widget, '/'))][] = (new WidgetModel($widgetRelationRecord, $this->getSkinsetConfig()))->getJson($request);
        }
        return $widgets;
    }
    
}
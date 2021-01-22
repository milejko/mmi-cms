<?php

namespace Cms;

use Cms\App\CmsSkinsetConfig;
use Cms\Orm\CmsCategoryRecord;
use Cms\Model\WidgetModel;
use Cms\Transport\TemplateTransport;
use CmsAdmin\Form\CategoryForm;
use Mmi\Mvc\Controller;
use Mmi\Http\Request;

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
     * Zwraca obiekt transportowy (na potrzeby API)
     */
    public function getTransportObject(Request $request): TemplateTransport
    {
        $to             = new TemplateTransport;
        $to->sections   = $this->getSections($request);
        $to->id         = $this->cmsCategoryRecord->id;
        $to->template   = $this->cmsCategoryRecord->template;
        $to->uri        = $this->cmsCategoryRecord->uri;
        $to->customUri  = $this->cmsCategoryRecord->customUri;
        $to->dateAdd    = $this->cmsCategoryRecord->dateAdd;
        $to->dateModify = $this->cmsCategoryRecord->dateModify;
        $to->attributes = json_decode($this->cmsCategoryRecord->configJson, true);
        return $to;
    }

    /**
     * Dekoracja formularza edycji
     */
    public function decorateEditForm(CategoryForm $categoryForm): void
    {}

    /**
     * Metoda przed zapisem formularza
     */
    public function beforeSaveEditForm(CategoryForm $categoryForm): void
    {}

    /**
     * Metoda po zapisie formularza
     */
    public function afterSaveEditForm(CategoryForm $categoryForm): void
    {}

    /**
     * Pobiera obiekty transportowe widgetów (podzielone na sekcje)
     */
    protected function getSections(Request $request): array
    {
        $widgets = [];
        //getting section skinsets
        foreach ($this->cmsCategoryRecord->getWidgetModel()->getWidgetRelations() as $widgetRelationRecord) {
            //calculating section name by full section path
            $fullSectionPath = substr($widgetRelationRecord->widget, 0, strrpos($widgetRelationRecord->widget, '/'));
            $sectionName = substr($fullSectionPath, strrpos($fullSectionPath, '/') + 1);
            //adding widgets to section
            $widgets[$sectionName][] = (new WidgetModel($widgetRelationRecord, $this->getSkinsetConfig()))->getTransportObject($request);
        }
        return $widgets;
    }
    
}
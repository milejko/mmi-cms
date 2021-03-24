<?php

namespace Cms;

use Cms\Api\BreadcrumbData;
use Cms\Api\LinkData;
use Cms\Api\TemplateDataTransport;
use Cms\Api\TransportInterface;
use Cms\App\CmsSkinsetConfig;
use Cms\Orm\CmsCategoryRecord;
use Cms\Model\WidgetModel;
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
     */
    private CmsCategoryRecord $cmsCategoryRecord;

    /**
     * CMS skinset config
     */
    private CmsSkinsetConfig $cmsSkinsetConfig;

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
    {
    }

    /**
     * Akcja wywoływana przy usuwaniu szablonu
     */
    public function deleteAction(): void
    {
    }

    /**
     * Zwraca obiekt transportowy (na potrzeby API)
     */
    public function getTransportObject(Request $request): TransportInterface
    {
        $attributes = json_decode($this->cmsCategoryRecord->configJson, true);
        $to = new TemplateDataTransport;
        $to->id = $this->cmsCategoryRecord->id;
        $to->template = $this->cmsCategoryRecord->template;
        $to->dateAdd = $this->cmsCategoryRecord->dateAdd;
        $to->dateModify = $this->cmsCategoryRecord->dateModify;
        $to->attributes = is_array($attributes) ? $attributes : [];
        $to->sections = $this->getSections($request);
        $to->breadcrumbs = $this->getBreadcrumbs();
        $to->siblings = $this->getSiblings();
        $to->_links = [(new LinkData)->setHref(rtrim(ApiController::API_PREFIX, '/'))->setRel(LinkData::REL_MENU)];
        return $to;
    }

    /**
     * Dekoracja formularza edycji
     */
    public function decorateEditForm(CategoryForm $categoryForm): void
    {
    }

    /**
     * Metoda przed zapisem formularza
     */
    public function beforeSaveEditForm(CategoryForm $categoryForm): void
    {
    }

    /**
     * Metoda po zapisie formularza
     */
    public function afterSaveEditForm(CategoryForm $categoryForm): void
    {
    }

    /**
     * Pobiera obiekty transportowe widgetów (podzielone na sekcje)
     */
    protected function getSections(Request $request): array
    {
        $widgets = [];
        //getting section skinsets
        foreach ($this->cmsCategoryRecord->getWidgetModel()->getWidgetRelations() as $widgetRelationRecord) {
            //inactive widget
            if (!$widgetRelationRecord->active) {
                continue;
            }
            //adding widgets to section
            $widgets[substr($fullSectionPath = substr($widgetRelationRecord->widget, 0, strrpos($widgetRelationRecord->widget, '/')), strrpos($fullSectionPath, '/') + 1)][] = (new WidgetModel($widgetRelationRecord, $this->getSkinsetConfig()))->getDataObject($request);
        }
        return $widgets;
    }

    /**
     * Pobiera breadcrumby
     */
    public function getBreadcrumbs(): array
    {
        $breadcrumbs = [];
        $record = $this->cmsCategoryRecord;
        $order = count(explode('/', $this->cmsCategoryRecord->path));
        while (null !== $record) {
            $breadcrumbs[] = (new BreadcrumbData)
                ->setTitle($record->name)
                ->setOrder($order--)
                ->setLinks($record->template ? [
                    (new LinkData)
                        ->setHref(ApiController::API_PREFIX . ($record->customUri ?: $record->uri))
                        ->setRel($this->cmsCategoryRecord === $record ? LinkData::REL_SELF : LinkData::REL_BACK)
                ] : []);
            $record = $record->getParentRecord();
        }
        return array_reverse($breadcrumbs);
    }

    /**
     * Pobiera rodzeństwo
     */
    public function getSiblings(): array
    {
        $siblings = [];
        $records = $this->cmsCategoryRecord->getParentRecord()->getChildrenRecords();
        foreach ($records as $record) {
            if ($record->id === $this->cmsCategoryRecord->id) {
                continue;
            }
            $siblings[] = (new BreadcrumbData)
                ->setTitle($record->name)
                ->setLinks($record->template ? [
                    (new LinkData)
                        ->setHref(ApiController::API_PREFIX . ($record->customUri ?: $record->uri))
                        ->setRel(LinkData::REL_SIBLING)
                ] : []);
        }

        return $siblings;
    }
}

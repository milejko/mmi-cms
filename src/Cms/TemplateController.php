<?php

namespace Cms;

use Cms\Api\BreadcrumbData;
use Cms\Api\LinkData;
use Cms\Api\TemplateDataTransport;
use Cms\Api\TransportInterface;
use Cms\App\CmsSkinsetConfig;
use Cms\Model\WidgetModel;
use Cms\Orm\CmsCategoryRecord;
use CmsAdmin\Form\CategoryForm;
use Mmi\Http\Request;
use Mmi\Mvc\Controller;

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
        $to->name = $this->cmsCategoryRecord->name;
        $to->dateAdd = $this->cmsCategoryRecord->dateAdd;
        $to->dateModify = $this->cmsCategoryRecord->dateModify;
        $to->title = $this->cmsCategoryRecord->title;
        $to->description = $this->cmsCategoryRecord->description;
        $to->opensNewWindow = $this->cmsCategoryRecord->blank ? true : false;
        $to->attributes = is_array($attributes) ? $attributes : [];
        $to->sections = $this->getSections($request);
        $to->breadcrumbs = $this->getBreadcrumbs();
        $to->siblings = $this->getSiblings();
        $scope = substr($this->cmsCategoryRecord->template, 0, strpos($this->cmsCategoryRecord->template, '/'));
        $to->_links = [(new LinkData)->setHref(ApiController::API_PREFIX . $scope)->setRel(LinkData::REL_MENU)];
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
            $scope = substr($record->template, 0, strpos($record->template, '/'));
            $breadcrumbs[] = (new BreadcrumbData)
                ->setTitle($record->name ? : '')
                ->setOrder($order--)
                ->setLinks($scope ? [
                    (new LinkData)
                        ->setHref(ApiController::API_PREFIX . $scope . '/' . ($record->customUri ?: $record->uri))
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
        foreach ($this->cmsCategoryRecord->getSiblingsRecords() as $record) {
            if (!$record->active || $record->id === $this->cmsCategoryRecord->id) {
                continue;
            }
            $scope = substr($record->template, 0, strpos($record->template, '/'));
            $siblings[] = (new BreadcrumbData)
                ->setTitle($record->name ? : '')
                ->setLinks($scope ? [
                    (new LinkData)
                        ->setHref(ApiController::API_PREFIX . $scope . '/' . ($record->customUri ?: $record->uri))
                        ->setRel(LinkData::REL_SIBLING)
                ] : []);
        }
        return $siblings;
    }
}

<?php

namespace Cms;

use Cms\Api\BreadcrumbData;
use Cms\Api\LinkData;
use Cms\Api\TemplateDataTransport;
use Cms\Api\TransportInterface;
use Cms\App\CmsSkinsetConfig;
use Cms\Exception\CategoryWidgetException;
use Cms\Model\SkinsetModel;
use Cms\Model\WidgetModel;
use Cms\Orm\CmsCategoryRecord;
use Cms\Orm\CmsFileQuery;
use CmsAdmin\Form\CategoryForm;
use Mmi\Http\Request;
use Mmi\Mvc\Controller;

/**
 * Abstrakcyjna klasa kontrolera widgetów
 */
abstract class AbstractTemplateController extends Controller
{
    /**
     * CMS category record
     */
    protected CmsCategoryRecord $cmsCategoryRecord;

    /**
     * CMS skinset config
     */
    protected CmsSkinsetConfig $cmsSkinsetConfig;

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
        $to = new TemplateDataTransport;
        $to->id = $this->cmsCategoryRecord->id;
        $to->template = $this->cmsCategoryRecord->template;
        $to->name = $this->cmsCategoryRecord->name;
        $to->dateAdd = $this->cmsCategoryRecord->dateAdd;
        $to->dateModify = $this->cmsCategoryRecord->dateModify;
        $to->title = $this->cmsCategoryRecord->title ?: $this->cmsCategoryRecord->name;
        if (null !== $ogImageRecord = CmsFileQuery::imagesByObject(CmsCategoryRecord::OG_IMAGE_OBJECT, $this->cmsCategoryRecord->id)->findFirst()) {
            $to->ogImageUrl = $ogImageRecord->getUrl('scalecrop', '1200x630');
        }
        $to->description = (string) $this->cmsCategoryRecord->description;
        $to->opensNewWindow = $this->cmsCategoryRecord->blank ? true : false;
        $to->children = $this->getChildren();
        //attributes
        $attributes = json_decode((string) $this->cmsCategoryRecord->configJson, true);
        $to->attributes = is_array($attributes) ? $attributes : [];
        $to->sections = $this->getSections($request);
        $to->breadcrumbs = $this->getBreadcrumbs();
        $to->siblings = $this->getSiblings();
        $scope = substr($this->cmsCategoryRecord->template, 0, strpos($this->cmsCategoryRecord->template, '/')) ?: $this->cmsCategoryRecord->template;
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
            try {
                //adding widgets to section
                $widgets[substr($fullSectionPath = substr($widgetRelationRecord->widget, 0, strrpos($widgetRelationRecord->widget, '/')), strrpos($fullSectionPath, '/') + 1)][] = (new WidgetModel($widgetRelationRecord, $this->getSkinsetConfig()))->getDataObject($request);
            } catch (CategoryWidgetException $e) {
                //ignoring failed widgets
            }
        }
        return $widgets;
    }

    /**
     * Pobiera breadcrumby
     */
    protected function getBreadcrumbs(): array
    {
        $breadcrumbs = [];
        $record = $this->cmsCategoryRecord;
        $order = count(explode('/', $this->cmsCategoryRecord->path));
        while (null !== $record) {
            //adding breadcrumb with modified order field
            $breadcrumbs[] = $this->getBreadcrumbDataByRecord($record, LinkData::REL_BACK)
                ->setOrder($order--);
            $record = $record->getParentRecord();
        }
        return array_reverse($breadcrumbs);
    }

    /**
     * Pobiera dzieci
     */
    protected function getChildren(): array
    {
        $children = [];
        foreach ($this->cmsCategoryRecord->getChildrenRecords() as $childRecord) {
            $children[] = $this->getBreadcrumbDataByRecord($childRecord);
        }
        return $children;
    }

    /**
     * Pobiera rodzeństwo
     */
    protected function getSiblings(): array
    {
        $siblings = [];
        foreach ($this->cmsCategoryRecord->getSiblingsRecords() as $record) {
            //not active or self
            if (!$record->active || $record->id == $this->cmsCategoryRecord->id || $record->id == $this->cmsCategoryRecord->cmsCategoryOriginalId) {
                continue;
            }
            //template not compatible
            if (null === (new SkinsetModel($this->cmsSkinsetConfig))->getTemplateConfigByKey($record->template)) {
                continue;
            }
            $siblings[] = $this->getBreadcrumbDataByRecord($record, LinkData::REL_SIBLING);
        }
        return $siblings;
    }

    protected function getBreadcrumbDataByRecord(CmsCategoryRecord $cmsCategoryRecord, string $defaultRel = LinkData::REL_NEXT): BreadcrumbData
    {
        $scope = substr($cmsCategoryRecord->template, 0, strpos($cmsCategoryRecord->template, '/')) ?: $cmsCategoryRecord->template;
        return (new BreadcrumbData)
            ->setId($cmsCategoryRecord->id)
            ->setName($cmsCategoryRecord->name ?: '')
            ->setTemplate($cmsCategoryRecord->template)
            ->setBlank($cmsCategoryRecord->blank)
            ->setOrder($cmsCategoryRecord->order)
            ->setLinks([
                (new LinkData)
                    ->setHref(ApiController::API_PREFIX . $scope . '/' . ($cmsCategoryRecord->customUri ?: $cmsCategoryRecord->uri))
                    ->setRel($this->cmsCategoryRecord === $cmsCategoryRecord ? LinkData::REL_SELF : $defaultRel)
            ]);
    }
}

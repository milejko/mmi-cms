<?php

namespace Cms;

use Cms\Api\BreadcrumbData;
use Cms\Api\LinkData;
use Cms\Api\RedirectTransport;
use Cms\Api\TemplateDataTransport;
use Cms\Api\TransportInterface;
use Cms\App\CmsRouterConfig;
use Cms\App\CmsSkinsetConfig;
use Cms\Exception\CategoryWidgetException;
use Cms\Model\SkinsetModel;
use Cms\Model\TemplateModel;
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
    final public function getCategoryRecord(): CmsCategoryRecord
    {
        return $this->cmsCategoryRecord;
    }

    /**
     * Gets the CMS category record
     */
    final public function getSkinsetConfig(): CmsSkinsetConfig
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
    public function getTransportObject(): TransportInterface
    {
        $to = new TemplateDataTransport();
        $to->id = (int) $this->cmsCategoryRecord->id;
        $to->order = $this->cmsCategoryRecord->getAbsoluteOrder();
        $to->template = $this->cmsCategoryRecord->template;
        $to->path = $this->cmsCategoryRecord->getUri();
        $to->name = (string) $this->cmsCategoryRecord->name;
        $to->dateAdd = $this->cmsCategoryRecord->dateAdd;
        $to->dateModify = $this->cmsCategoryRecord->dateModify;
        $to->title = (string) ($this->cmsCategoryRecord->title ?: $this->cmsCategoryRecord->name);
        if (null !== $ogImageRecord = CmsFileQuery::imagesByObject(CmsCategoryRecord::OG_IMAGE_OBJECT, $this->cmsCategoryRecord->id)->findFirst()) {
            $to->ogImageUrl = $ogImageRecord->getThumbUrl('scalecrop', '1200x630');
        }
        $to->description = (string) $this->cmsCategoryRecord->description;
        $to->opensNewWindow = (bool) $this->cmsCategoryRecord->blank;
        $to->visible = (bool) $this->cmsCategoryRecord->visible;
        $to->children = $this->getChildren();
        $to->attributes = $this->getAttributes();
        $to->sections = $this->getSections();
        $to->breadcrumbs = $this->getBreadcrumbs();
        $to->siblings = $this->getSiblings();
        $to->_links = [
            (new LinkData())
                ->setHref(sprintf(CmsRouterConfig::API_METHOD_CONTENTS, $this->cmsCategoryRecord->getScope()))
                ->setRel(LinkData::REL_CONTENTS),
            (new LinkData())
                ->setHref(sprintf(CmsRouterConfig::API_METHOD_CONTENT, $this->cmsCategoryRecord->getScope(), $this->cmsCategoryRecord->getUri()))
                ->setRel(LinkData::REL_SELF),
        ];
        return $to;
    }

    /**
     * Zwraca atrybuty (z ewentualnym formatowaniem)
     */
    public function getAttributes(): array
    {
        $attributes = json_decode((string) $this->cmsCategoryRecord->configJson, true);
        return is_array($attributes) ? $attributes : [];
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
    protected function getSections(): array
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
                $widgets[substr($fullSectionPath = substr($widgetRelationRecord->widget, 0, strrpos($widgetRelationRecord->widget, '/')), strrpos($fullSectionPath, '/') + 1)][] = (new WidgetModel($widgetRelationRecord, $this->getSkinsetConfig()))->getDataObject();
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
        $record = $this->cmsCategoryRecord->getParentRecord();
        $skinsetModel = new SkinsetModel($this->cmsSkinsetConfig);
        while (null !== $record) {
            //template not compatible
            if (null === $skinsetModel->getTemplateConfigByKey($record->template)) {
                continue;
            }
            //adding breadcrumb with modified order field
            $breadcrumbs[] = $this->getBreadcrumbDataByRecord($record)
                ->setOrder($record->getAbsoluteOrder());
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
        $skinsetModel = new SkinsetModel($this->cmsSkinsetConfig);
        foreach ($this->cmsCategoryRecord->getChildrenRecords() as $record) {
            //inavtive record
            if (!$record->active) {
                continue;
            }
            //template not compatible
            if (null === $skinsetModel->getTemplateConfigByKey($record->template)) {
                continue;
            }
            $children[] = $this->getBreadcrumbDataByRecord($record);
        }
        return $children;
    }

    /**
     * Pobiera rodzeństwo
     */
    protected function getSiblings(): array
    {
        $beforeMeSiblings = [];
        $afterMeSiblings = [];
        $beforeMe = true;
        $skinsetModel = new SkinsetModel($this->cmsSkinsetConfig);
        foreach ($this->cmsCategoryRecord->getSiblingsRecords() as $record) {
            //ifself
            if ($record->id == $this->cmsCategoryRecord->id || $record->id == $this->cmsCategoryRecord->cmsCategoryOriginalId) {
                $beforeMe = false;
                continue;
            }
            //template not compatible
            if (null === $skinsetModel->getTemplateConfigByKey($record->template)) {
                continue;
            }
            if ($beforeMe) {
                $beforeMeSiblings[] = $this->getBreadcrumbDataByRecord($record);
                continue;
            }
            $afterMeSiblings[] = $this->getBreadcrumbDataByRecord($record);
        }
        //after me comes first
        return array_merge($afterMeSiblings, $beforeMeSiblings);
    }

    protected function getBreadcrumbDataByRecord(CmsCategoryRecord $cmsCategoryRecord): BreadcrumbData
    {
        if ($cmsCategoryRecord->redirectUri) {
            $links = (new RedirectTransport($cmsCategoryRecord->redirectUri))->_links;
        } else {
            $links = [
                (new LinkData())
                    ->setHref(sprintf(CmsRouterConfig::API_METHOD_CONTENT, $cmsCategoryRecord->getScope(), $cmsCategoryRecord->getUri()))
                    ->setRel(LinkData::REL_CONTENT)
            ];
        }
        //CAREFUL! You are inside an instance of Controller stored in DI container, template model can change your own fields like (cmsCategoryRecord and cmsSkinsetConfig)
        //backuping original categoryRecord (from controller), as it can be replaced by TemplateModel, cause Controllers come from DI container
        $originalCategoryRecord = $this->cmsCategoryRecord;
        $attributes = (new TemplateModel($cmsCategoryRecord, $this->cmsSkinsetConfig))->getAttributes();
        //restoring original category
        $this->cmsCategoryRecord = $originalCategoryRecord;
        return (new BreadcrumbData())
            ->setId($cmsCategoryRecord->id)
            ->setName($cmsCategoryRecord->name ?: '')
            ->setPath($cmsCategoryRecord->getUri())
            ->setTemplate($cmsCategoryRecord->template)
            ->setBlank((bool) $cmsCategoryRecord->blank)
            ->setVisible((bool) $cmsCategoryRecord->visible)
            ->setAttributes($attributes)
            ->setOrder($cmsCategoryRecord->getAbsoluteOrder())
            ->setLinks($links);
    }
}

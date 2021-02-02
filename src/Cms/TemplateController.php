<?php

namespace Cms;

use Cms\Api\LinkData;
use Cms\Api\TemplateDataTransport;
use Cms\Api\TransportInterface;
use Cms\App\CmsSkinsetConfig;
use Cms\Orm\CmsCategoryRecord;
use Cms\Model\WidgetModel;
use Cms\Orm\CmsCategoryQuery;
use CmsAdmin\Form\CategoryForm;
use Mmi\Mvc\Controller;
use Mmi\Http\Request;

/**
 * Abstrakcyjna klasa kontrolera widgetów
 */
abstract class TemplateController extends Controller
{

    const API_PREFIX = '/api/category/';

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
        $to              = new TemplateDataTransport;
        $to->id          = $this->cmsCategoryRecord->id;
        $to->template    = $this->cmsCategoryRecord->template;
        $to->dateAdd     = $this->cmsCategoryRecord->dateAdd;
        $to->dateModify  = $this->cmsCategoryRecord->dateModify;
        $to->attributes  = json_decode($this->cmsCategoryRecord->configJson, true);
        $to->breadcrumbs = $this->getBreadcrumbs();
        $to->sections    = $this->getSections($request);
        $to->menus       = $this->getMenus($request);
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
            //calculating section name by full section path
            $fullSectionPath = substr($widgetRelationRecord->widget, 0, strrpos($widgetRelationRecord->widget, '/'));
            $sectionName = substr($fullSectionPath, strrpos($fullSectionPath, '/') + 1);
            //adding widgets to section
            $widgets[$sectionName][] = (new WidgetModel($widgetRelationRecord, $this->getSkinsetConfig()))->getDataObject($request);
        }
        return $widgets;
    }

    protected function getBreadcrumbs(): array
    {
        $breadcrumbs = [];
        $category = $this->cmsCategoryRecord;
        $order = 0;
        while (null !== ($category = $category->getParentRecord())) {
            $breadcrumb = [
                'title'     => $category->name,
                'order'     => $order,
                '_links'    => [
                    (new LinkData)
                        ->setHref(self::API_PREFIX . ($category->customUri ?: $category->uri))
                        ->setRel(LinkData::REL_BACK)
                ],
            ];
            $order++;
            $breadcrumbs[] = $breadcrumb;
        }
        $breadcrumbs[] = (new LinkData)
            ->setHref(self::API_PREFIX . ($this->getCategoryRecord()->customUri ?: $this->getCategoryRecord()->uri))
            ->setRel(LinkData::REL_SELF);
        return $breadcrumbs;
    }

    protected function getMenus(Request $request): array
    {
        $menu = [];
        foreach ((new CmsCategoryQuery())
            ->whereStatus()->equals(10)
            ->whereActive()->equals(1)
            ->orderAscParentId()
            ->orderAscOrder()
            ->findFields(['id', 'template', 'name', 'uri', 'customUri', 'path', 'order']) as $item) {
            $links = [];
            if ($item['template']) {
                $links[] = (new LinkData)
                    ->setHref(self::API_PREFIX . ($item['customUri'] ?: $item['uri']))
                    ->setRel(LinkData::REL_NEXT);
            }
            $fullPath = trim($item['path'] . '/' . $item['id'], '/');
            $activatedFullPath = trim($this->cmsCategoryRecord->path . '/' . $this->cmsCategoryRecord->id, '/');
            $this->injectToMenu($menu, $fullPath, [
                'id'        => $item['id'],
                'name'      => $item['name'],
                'order'     => $item['order'],
                'fp' => $fullPath . ' vs ' . $this->cmsCategoryRecord->path,
                'active'    => 0 === strpos($activatedFullPath . '/', $fullPath . '/'),
                '_links'    => $links,
                'children'  => [],
            ]);
        }
        return $menu['children'];
    }

    protected function injectToMenu(&$menu, $path, $value)
    {
        $ids = explode("/", $path);
        $current = &$menu;
        foreach ($ids as $id) {
            $current = &$current['children']['item-' . $id];
        }
        $current = is_array($current) ? array_merge($value, $current) : $value;
    }
}

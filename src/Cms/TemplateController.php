<?php

namespace Cms;

use Cms\Api\BreadcrumbData;
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
        $to->sections    = $this->getSections($request);
        $to->breadcrumbs = $this->getBreadcrumbs();
        $to->menus       = $this->getMenus();
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
            //adding widgets to section
            $widgets[substr($fullSectionPath = substr($widgetRelationRecord->widget, 0, strrpos($widgetRelationRecord->widget, '/')), strrpos($fullSectionPath, '/') + 1)][] = (new WidgetModel($widgetRelationRecord, $this->getSkinsetConfig()))->getDataObject($request);
        }
        return $widgets;
    }

    /**
     * Pobiera breadcrumby
     */
    protected function getBreadcrumbs(): array
    {
        $breadcrumbs = [];
        $category = $this->cmsCategoryRecord;
        $order = count(explode('/', $this->cmsCategoryRecord->path));
        while (null !== $category) {
            $breadcrumbs[] = (new BreadcrumbData)
                ->setTitle($category->name)
                ->setOrder($order--)
                ->setLinks($category->template ? [
                    (new LinkData)
                        ->setHref(ApiController::API_PREFIX . ($category->customUri ?: $category->uri))
                        ->setRel($this->cmsCategoryRecord === $category ? LinkData::REL_SELF : LinkData::REL_BACK)
                ] : []);
            $category = $category->getParentRecord();
        }
        return array_reverse($breadcrumbs);
    }

    protected function getMenus(): array
    {
        $menu = [];
        foreach ((new CmsCategoryQuery())
            ->whereStatus()->equals(10)
            ->whereActive()->equals(1)
            ->orderAscParentId()
            ->orderAscOrder()
            ->findFields(['id', 'template', 'name', 'uri', 'customUri', 'path', 'order']) as $item) {
            $fullPath = trim($item['path'] . '/' . $item['id'], '/');
            $activatedFullPath = trim($this->cmsCategoryRecord->path . '/' . $this->cmsCategoryRecord->id, '/');
            $this->injectIntoMenu($menu, $fullPath, [
                'id'        => $item['id'],
                'name'      => $item['name'],
                'order'     => $item['order'],
                'active'    => 0 === strpos($activatedFullPath . '/', $fullPath . '/'),
                '_links'    => $item['template'] ? [
                    (new LinkData)
                        ->setHref(ApiController::API_PREFIX . ($item['customUri'] ?: $item['uri']))
                        ->setRel(LinkData::REL_NEXT)
                ] : [],
                'children'  => [],
            ]);
        }
        return $menu['children'];
    }

    protected function injectIntoMenu(&$menu, $path, $value)
    {
        $ids = explode('/', $path);
        $current = &$menu;
        foreach ($ids as $id) {
            $current = &$current['children'][$id];
        }
        $current = is_array($current) ? array_merge($value, $current) : $value;
    }
}

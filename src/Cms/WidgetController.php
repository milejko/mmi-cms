<?php

namespace Cms;

use Cms\Orm\CmsCategoryWidgetCategoryRecord;
use Cms\Orm\CmsFileQuery;
use Cms\Transport\WidgetTransport;
use Mmi\Mvc\Controller;
use Mmi\Http\Request;

/**
 * Abstrakcyjna klasa kontrolera widgetów
 */
abstract class WidgetController extends Controller
{

    /**
     * Widget relation record
     * @var CmsCategoryWidgetCategoryRecord
     */
    private $widgetRecord;

    /**
     * Sets the CMS category record
     */
    public function setWidgetRecord(CmsCategoryWidgetCategoryRecord $widgetRecord): self
    {
        $this->widgetRecord = $widgetRecord;
        return $this;
    }

    /**
     * Zwraca rekord relacji widgeta
     */
    public final function getWidgetRecord(): CmsCategoryWidgetCategoryRecord
    {
        return $this->widgetRecord;
    }

    /**
     * Ustawia stan zapisany
     * @return void
     */
    public final function redirectToCategory(): void
    {
        //przekierowanie na stronę edycji
        $this->getResponse()->redirect('cmsAdmin', 'category', 'edit', [
            'id' => $this->categoryId,
            'originalId' => $this->originalId,
            'uploaderId' => $this->categoryId,
        ]);
    }

    /**
     * Wyświetlenie edytora widgeta (po stronie admina)
     */
    abstract public function editAction();

    /**
     * Wyświetlenie podglądu widgeta (po stronie admina)
     */
    abstract public function previewAction();

    /**
     * Po usunięciu widgeta
     */
    public function deleteAction(): void
    {}

    /**
     * Wyświetlenie po stronie klienta (HTML)
     */
    public function displayAction(Request $request)
    {}
    
    /**
     * Render obiektu JSON (na potrzeby API)
     */
    public function getTransportObject(Request $request): WidgetTransport
    {
        $to         = new WidgetTransport();
        $to->id     = $this->widgetRecord->uuid;
        $to->widget = substr($this->widgetRecord->widget, strrpos($this->widgetRecord->widget, '/') + 1);
        $to->config = json_decode($this->widgetRecord->configJson, true);
        $to->order  = $this->widgetRecord->order;
        $to->attachments  = $this->getAttachments($request);
        return $to;
    }

    /**
     * Pobiera załączniki
     */
    protected function getAttachments(Request $request): array
    {
        $files = [];
        foreach ((new CmsFileQuery)
            ->whereObject()->like(CmsCategoryWidgetCategoryRecord::FILE_OBJECT . '%')
            ->andFieldObjectId()->equals($this->widgetRecord->id)
            ->orderAscOrder()
            ->orderAscId()
            ->find() as $file) {
            $sectionName = substr($file->object, strlen(CmsCategoryWidgetCategoryRecord::FILE_OBJECT));
            $files[$sectionName][] = $file;
        }
        return $files;
    }

}
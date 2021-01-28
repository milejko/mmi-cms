<?php

namespace Cms;

use Cms\Api\AttachmentData;
use Cms\Api\DataInterface;
use Cms\Api\WidgetData;
use Cms\Orm\CmsCategoryWidgetCategoryRecord;
use Cms\Orm\CmsFileQuery;
use Mmi\Mvc\Controller;
use Mmi\Http\Request;

/**
 * Abstrakcyjna klasa kontrolera widgetów
 */
abstract class WidgetController extends Controller
{

    /**
     * Attachment thumb width & 
     */
    const ATTACHMENT_THUMB_METHOD   = 'scalecrop';
    const ATTACHMENT_THUMB_SCALE    = '640x480';
    const ATTACHMENT_THUMB_SCALE2X  = '1280x960';
    const ATTACHMENT_THUMB_SCALE4X  = '2560x1920';

    /**
     * Widget relation record
     */
    private CmsCategoryWidgetCategoryRecord $widgetRecord;

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
    {
    }

    /**
     * Wyświetlenie po stronie klienta (HTML)
     */
    public function displayAction(Request $request)
    {
    }

    /**
     * Pobiera obiekt transportowy (na potrzeby API)
     */
    public function getDataObject(Request $request): DataInterface
    {
        $to             = new WidgetData();
        $to->id         = $this->widgetRecord->uuid;
        $to->widget     = substr($this->widgetRecord->widget, strrpos($this->widgetRecord->widget, '/') + 1);
        $to->attributes = json_decode($this->widgetRecord->configJson, true);
        $to->order      = $this->widgetRecord->order;
        $to->files      = $this->getCmsFiles($request);
        return $to;
    }

    /**
     * Pobiera załączniki
     */
    protected function getCmsFiles(Request $request): array
    {
        $attachments = [];
        foreach ((new CmsFileQuery)
            ->whereObject()->like(CmsCategoryWidgetCategoryRecord::FILE_OBJECT . '%')
            ->andFieldObjectId()->equals($this->widgetRecord->id)
            ->orderAscOrder()
            ->orderAscId()
            ->find() as $file) {
            $sectionName = substr($file->object, strlen(CmsCategoryWidgetCategoryRecord::FILE_OBJECT));
            $to                 = new AttachmentData;
            $to->attributes     = $file->data->toArray();
            $to->originalUrl    = $file->getUrl();
            $to->thumbUrl       = $file->getUrl(static::ATTACHMENT_THUMB_METHOD, static::ATTACHMENT_THUMB_SCALE);
            $to->thumb2xUrl     = $file->getUrl(static::ATTACHMENT_THUMB_METHOD, static::ATTACHMENT_THUMB_SCALE2X);
            $to->thumb4xUrl     = $file->getUrl(static::ATTACHMENT_THUMB_METHOD, static::ATTACHMENT_THUMB_SCALE4X);
            $to->size           = $file->size;
            $to->name           = $file->original;
            $to->mimeType       = $file->mimeType;
            $to->order          = $file->order ?: 0;
            $attachments[$sectionName ? : 'default'][] = $to;
        }
        return $attachments;
    }
}

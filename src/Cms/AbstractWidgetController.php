<?php

namespace Cms;

use Cms\Api\AttachmentData;
use Cms\Api\DataInterface;
use Cms\Api\WidgetData;
use Cms\Orm\CmsCategoryWidgetCategoryRecord;
use Cms\Orm\CmsFileQuery;
use Cms\Orm\CmsFileRecord;
use Mmi\Mvc\Controller;
use Mmi\Http\Request;

/**
 * Abstrakcyjna klasa kontrolera widgetów
 */
abstract class AbstractWidgetController extends Controller
{

    /**
     * Attachment thumb width &
     */
    const ATTACHMENT_THUMB_METHOD   = 'scale';
    const ATTACHMENT_THUMB_SCALE    = '800';
    const ATTACHMENT_THUMB_SCALE2X  = '1600';

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
            ->whereActive()->equals(true)
            ->whereObject()->like(CmsCategoryWidgetCategoryRecord::FILE_OBJECT . '%')
            ->andFieldObjectId()->equals($this->widgetRecord->id)
            ->orderAscOrder()
            ->orderAscId()
            ->find() as $file) {
            $attachments[substr($file->object, strlen(CmsCategoryWidgetCategoryRecord::FILE_OBJECT)) ?: 'default'][] = $this->getAttachmentData($file);
        }
        return $attachments;
    }

    /**
     * Pobiera dane załącznika na podstawie pliku
     */
    protected function getAttachmentData(CmsFileRecord $fileRecord): AttachmentData
    {
        $to                             = new AttachmentData();
        $to->attributes                 = $fileRecord->data->toArray();
        $to->name                       = $fileRecord->original;
        $to->fileName                   = $fileRecord->name;
        $to->size                       = $fileRecord->size;
        $to->mimeType                   = $fileRecord->mimeType;
        $to->order                      = $fileRecord->order ?: 0;
        $to->attributes['downloadUrl']  = $fileRecord->getUrl('download');
        if ('image' == $fileRecord->class) {
            $to->attributes['thumbUrl']     = $fileRecord->getUrl(static::ATTACHMENT_THUMB_METHOD, static::ATTACHMENT_THUMB_SCALE);
            $to->attributes['thumb2xUrl']   = $fileRecord->getUrl(static::ATTACHMENT_THUMB_METHOD, static::ATTACHMENT_THUMB_SCALE2X);
        }
        return $to;
    }

}

<?php

namespace Cms;

use Cms\Api\AttachmentData;
use Cms\Api\DataInterface;
use Cms\Api\WidgetData;
use Cms\App\CmsWidgetConfig;
use Cms\Orm\CmsCategoryWidgetCategoryRecord;
use Cms\Orm\CmsFileQuery;
use Cms\Orm\CmsFileRecord;
use Mmi\Http\Request;
use Mmi\Mvc\Controller;

/**
 * Abstrakcyjna klasa kontrolera widgetów
 * Property z Requesta:
 * @property int $categoryId
 * @property int $originalId
 * @property int $uploaderId
 */
abstract class AbstractWidgetController extends Controller
{
    /**
     * Attachment thumb width &
     */
    public const ATTACHMENT_THUMB_METHOD   = 'scale';
    public const ATTACHMENT_THUMB_MOBILE   = '450';
    public const ATTACHMENT_THUMB_TABLET   = '900';
    public const ATTACHMENT_THUMB_DESKTOP  = '1920';

    /**
     * Widget relation record
     */
    private CmsCategoryWidgetCategoryRecord $widgetRecord;

    /**
     * Widget config
     */
    private CmsWidgetConfig $widgetConfig;

    /**
     * Sets the CMS category record
     */
    public function setWidgetRecord(CmsCategoryWidgetCategoryRecord $cmsCategoryWidgetRecord): self
    {
        $this->widgetRecord = $cmsCategoryWidgetRecord;
        return $this;
    }

    /**
     * Sets the CMS widget config
     */
    public function setWidgetConfig(CmsWidgetConfig $cmsWidgetConfig): self
    {
        $this->widgetConfig = $cmsWidgetConfig;
        return $this;
    }

    /**
     * Zwraca rekord relacji widgeta
     */
    final public function getWidgetRecord(): CmsCategoryWidgetCategoryRecord
    {
        return $this->widgetRecord;
    }

    final public function getWidgetConfig(): CmsWidgetConfig
    {
        return $this->widgetConfig;
    }

    /**
     * Ustawia stan zapisany
     * @return void
     */
    final public function redirectToCategory(): void
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
     * Pobiera content pod wyszukiwarkę
     */
    abstract public function getContentToSearch(): string;

    /**
     * Pobiera obiekt transportowy (na potrzeby API)
     */
    public function getDataObject(): DataInterface
    {
        $to             = new WidgetData();
        $to->id         = $this->widgetRecord->uuid;
        $to->widget     = substr($this->widgetRecord->widget, strrpos($this->widgetRecord->widget, '/') + 1);
        $to->attributes = json_decode($this->widgetRecord->configJson, true);
        $to->order      = $this->widgetRecord->order;
        $to->files      = $this->getCmsFiles();
        return $to;
    }

    /**
     * Pobiera załączniki
     */
    protected function getCmsFiles(): array
    {
        $attachments = [];
        foreach ((new CmsFileQuery())
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
        $to->attributes['downloadUrl']  = $fileRecord->getDownloadUrl();
        $to->attributes['openUrl']      = $fileRecord->getDownloadUrl();
        if ('image' === $fileRecord->class) {
            $to->attributes['thumbMobileUrl'] = $fileRecord->getThumbUrl(static::ATTACHMENT_THUMB_METHOD, static::ATTACHMENT_THUMB_MOBILE);
            $to->attributes['thumbTabletUrl'] = $fileRecord->getThumbUrl(static::ATTACHMENT_THUMB_METHOD, static::ATTACHMENT_THUMB_TABLET);
            $to->attributes['thumbDesktop']   = $fileRecord->getThumbUrl(static::ATTACHMENT_THUMB_METHOD, static::ATTACHMENT_THUMB_DESKTOP);
            $to->attributes['thumbUrl']       = $fileRecord->getThumbUrl(static::ATTACHMENT_THUMB_METHOD, static::ATTACHMENT_THUMB_TABLET);
            $to->attributes['thumb2xUrl']     = $fileRecord->getThumbUrl(static::ATTACHMENT_THUMB_METHOD, static::ATTACHMENT_THUMB_DESKTOP);
        }
        return $to;
    }
}

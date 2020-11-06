<?php

namespace Cms;

use Cms\Orm\CmsCategoryWidgetCategoryRecord;
use Mmi\Http\Request;
use Mmi\Http\Response;
use Mmi\Mvc\Controller;
use Mmi\Mvc\Messenger;
use Mmi\Mvc\View;

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
     * @return CmsCategoryWidgetCategoryRecord
     */
    public final function getWidgetRecord()
    {
        return $this->widgetRecord;
    }

    /**
     * Ustawia stan zapisany
     * @return void
     */
    public final function redirectToCategory()
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
     * @return string
     */
    abstract public function editAction();

    /**
     * Wyświetlenie podglądu widgeta (po stronie admina)
     * @return string
     */
    abstract public function previewAction();

    /**
     * Wyświetlenie po stronie klienta (HTML)
     * @return string
     */
    public function displayAction()
    {}

    /**
     * Po usunięciu widgeta
     * @return void
     */
    public function deleteAction()
    {}
    
    /**
     * Render obiektu JSON (na potrzeby API)
     * @return string
     */
    public function renderJsonAction()
    {}

}
<?php

namespace Cms;

use Cms\Orm\CmsCategoryWidgetCategoryRecord;
use Mmi\Http\Request;
use Mmi\Mvc\Controller;
use Mmi\Mvc\View;

/**
 * Abstrakcyjna klasa kontrolera widgetów
 */
abstract class WidgetController extends Controller
{

    /**
     * Rekord relacji
     * @var CmsCategoryWidgetCategoryRecord
     */
    private $_widgetRecord;

    /**
     * Konstruktor
     */
    public function __construct(Request $request, View $view, CmsCategoryWidgetCategoryRecord $widgetRecord)
    {
        //parent
        parent::__construct($request, $view);
        //przypisanie rekordu
        $this->_widgetRecord = $widgetRecord;
    }

    /**
     * Zwraca rekord relacji widgeta
     * @return CmsCategoryWidgetCategoryRecord
     */
    public final function getWidgetRecord()
    {
        return $this->_widgetRecord;
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
    abstract public function displayAction();

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
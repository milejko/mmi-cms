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
    private $_relationRecord;

    /**
     * Zwraca rekord relacji
     * @return CmsCategoryWidgetCategoryRecord
     */
    public function getRelationRecord()
    {
        return $this->_relationRecord;
    }

    /**
     * Ustawia stan zapisany
     * @return void
     */
    public function redirectToCategory()
    {
        //przekierowanie na stronę edycji
        $this->getResponse()->redirect('cmsAdmin', 'category', 'edit', [
            'id' => $this->categoryId,
            'originalId' => $this->originalId,
            'uploaderId' => $this->originalUploaderId,
        ]);
    }

    /**
     * Konstruktor
     */
    public function __construct(Request $request, View $view, CmsCategoryWidgetCategoryRecord $relationRecord)
    {
        //parent
        parent::__construct($request, $view);
        //przypisanie rekordu
        $this->_relationRecord = $relationRecord;
    }

    //edycja widgeta
    abstract public function editAction();

    //podgląd w admin panelu
    abstract public function previewAction();

    //wyświetlenie po stronie klienta
    abstract public function displayAction();
    
}
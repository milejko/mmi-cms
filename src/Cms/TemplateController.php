<?php

namespace Cms;

use Cms\Orm\CmsCategoryRecord;
use Cms\Orm\CmsCategoryWidgetCategoryRecord;
use Mmi\Http\Request;
use Mmi\Mvc\Controller;
use Mmi\Mvc\View;

/**
 * Abstrakcyjna klasa kontrolera widgetów
 */
abstract class TemplateController extends Controller
{

    /**
     * Rekord kategorii
     * @var CmsCategoryRecord
     */
    private $_categoryRecord;

    /**
     * Konstruktor
     */
    public function __construct(Request $request, View $view, CmsCategoryRecord $categoryRecord)
    {
        //parent
        parent::__construct($request, $view);
        //przypisanie rekordu
        $this->_categoryRecord = $categoryRecord;
    }

    /**
     * Zwraca rekord relacji
     * @return CmsCategoryRecord
     */
    public function getCategoryRecord()
    {
        return $this->_categoryRecord;
    }

    //wyświetlenie po stronie klienta
    abstract public function displayAction();
    
}
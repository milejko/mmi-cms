<?php

namespace Cms;

use Cms\Orm\CmsCategoryRecord;
use CmsAdmin\Form\Category;
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
     * Zwraca rekord kategorii
     * @return CmsCategoryRecord
     */
    public final function getCategoryRecord()
    {
        return $this->_categoryRecord;
    }

    /**
     * Wyświetlenie szablonu po stronie klienta
     * @return string
     */
    abstract public function displayAction();

    /**
     * Render obiektu JSON (na potrzeby API)
     * @return string
     */
    abstract public function renderJsonAction();

    /**
     * Akcja wywoływana przy usuwaniu szablonu
     * @return void
     */
    abstract public function deleteAction();
    
}
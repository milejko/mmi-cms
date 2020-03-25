<?php

namespace Cms;

use Cms\Orm\CmsCategoryRecord;
use CmsAdmin\Form\CategoryForm;
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
    public function displayAction()
    {}

    /**
     * Akcja wywoływana przy usuwaniu szablonu
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

    /**
     * Dekoracja formularza edycji
     * @param CategoryForm $categoryForm
     * @return void
     */
    public function decorateEditForm(CategoryForm $categoryForm)
    {}

    /**
     * Metoda przed zapisem formularza
     * @param CategoryForm $categoryForm
     * @return void
     */
    public function beforeSaveEditForm(CategoryForm $categoryForm)
    {}

    /**
     * Metoda po zapisie formularza
     * @param CategoryForm $categoryForm
     * @return void
     */
    public function afterSaveEditForm(CategoryForm $categoryForm)
    {}
    
}
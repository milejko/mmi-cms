<?php

namespace Cms;

use Cms\Orm\CmsCategoryRecord;
use CmsAdmin\Form\CategoryForm;
use Mmi\Mvc\Controller;

/**
 * Abstrakcyjna klasa kontrolera widgetów
 */
abstract class TemplateController extends Controller
{
    /**
     * CMS category record
     * @var CmsCategoryRecord
     */
    private $cmsCategoryRecord;

    /**
     * Sets the CMS category record
     */
    public function setCategoryRecord(CmsCategoryRecord $cmsCategoryRecord): self
    {
        $this->cmsCategoryRecord = $cmsCategoryRecord;
        return $this;
    }

    /**
     * Gets the CMS category record
     */
    public final function getCategoryRecord(): CmsCategoryRecord
    {
        return $this->cmsCategoryRecord;
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
<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin;

/**
 * Kontroler atrybutów
 */
class AttributeController extends Mvc\Controller
{

    /**
     * Lista tagów
     */
    public function indexAction()
    {
        $this->view->grid = new \CmsAdmin\Plugin\AttributeGrid();
    }

    /**
     * Edycja atrybutu
     */
    public function editAction()
    {
        $form = new \CmsAdmin\Form\Attribute(new \Cms\Orm\CmsAttributeRecord($this->id));
        //form zapisany
        if ($form->isSaved()) {
            $this->getMessenger()->addMessage('messenger.attribute.saved', true);
            $this->getResponse()->redirect('cmsAdmin', 'attribute', 'index');
        }
        //pobranie typu atrybutu
        $attributeType = new \Cms\Orm\CmsAttributeTypeRecord($form->getRecord()->cmsAttributeTypeId);
        //ograniczona lista
        if ($attributeType->restricted) {
            //grid wartości atrybutu
            $this->view->valueGrid = new Plugin\AttributeValueGrid(['id' => $form->getRecord()->id]);
            $valueRecord = new \Cms\Orm\CmsAttributeValueRecord($this->valueId);
            $valueRecord->cmsAttributeId = $form->getRecord()->id;
            //form wartości atrybutu
            $valueForm = new Form\AttributeValue($valueRecord);
            //zapis wartości atrybutu
            if ($valueForm->isSaved()) {
                $this->getMessenger()->addMessage('messenger.attribute.attributeValue.saved', true);
                $this->getResponse()->redirect('cmsAdmin', 'attribute', 'edit', ['id' => $form->getRecord()->id]);
            }
            //form wartości do widoku
            $this->view->valueForm = $valueForm;
        }
        //form atrybutu do widoku
        $this->view->attributeForm = $form;
    }

    /**
     * Usuwanie atrybutu
     */
    public function deleteAction()
    {
        $attribute = (new \Cms\Orm\CmsAttributeQuery)->findPk($this->id);
        if ($attribute && $attribute->delete()) {
            $this->getMessenger()->addMessage('messenger.attribute.deleted', true);
        }
        $this->getResponse()->redirect('cmsAdmin', 'attribute', 'index');
    }

    /**
     * Usuwanie relacji szablon atrybut
     */
    public function deleteAttributeRelationAction()
    {
        //wyszukiwanie rekordu relacji
        $record = (new \Cms\Orm\CmsAttributeRelationQuery)
            ->whereObjectId()->equals($this->id)
            ->findPk($this->relationId);
        //jeśli znaleziono rekord
        if ($record && $record->delete()) {
            //wyszukiwanie stron w zmienionym szablonie
            foreach ((new \Cms\Orm\CmsCategoryQuery)->whereCmsCategoryTypeId()
                ->equals($this->id)
                ->findPairs('id', 'id') as $categoryId) {
                //usuwanie wartości usuniętych atrybutów
                (new \Cms\Model\AttributeValueRelationModel('category', $categoryId))
                    ->deleteAttributeValueRelationsByAttributeId($record->cmsAttributeId);
            }
            $this->getMessenger()->addMessage('messenger.attribute.attributeRelation.deleted', true);
        }
    }

}

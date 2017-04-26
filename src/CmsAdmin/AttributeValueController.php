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
 * Kontroler wartości atrybutów
 */
class AttributeValueController extends Mvc\Controller
{

    /**
     * Usuwanie wartości atrybutu
     */
    public function deleteAction()
    {
        $value = (new \Cms\Orm\CmsAttributeValueQuery)->findPk($this->id);
        //usuwanie wartości
        if ($value && $value->delete()) {
            $this->getMessenger()->addMessage('Wartości usunięta', true);
        }
        //przekierowanie na atrybut
        $this->getResponse()->redirect('cmsAdmin', 'attribute', 'edit', ['id' => $value->cmsAttributeId]);
    }

}

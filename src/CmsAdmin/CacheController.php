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
 * Kontroler do obsługi cache Cms
 */
class CacheController extends Mvc\Controller
{

    /**
     * Czyści cały cache
     */
    public function indexAction()
    {
        if (\App\Registry::$cache) {
            \App\Registry::$cache->flush();
            $this->getMessenger()->addMessage('messenger.cache.cleared', true);
        }
        if ($this->getRequest()->getReferer()) {
            $this->getResponse()->redirectToUrl(urldecode($this->getRequest()->getReferer()));
        }
        $this->getResponse()->redirect('cmsAdmin');
    }

}

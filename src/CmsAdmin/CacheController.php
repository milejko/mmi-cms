<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin;

use Mmi\Cache\Cache;
use Mmi\Http\Request;
use Mmi\Mvc\Controller;

/**
 * Kontroler do obsługi cache Cms
 */
class CacheController extends Controller
{

    /**
     * @Inject
     * @var Cache
     */
    private $cache;

    /**
     * Czyści cały cache
     */
    public function indexAction(Request $request)
    {
        //czyszczenie cache
        $this->cache->flush();
        //messenger
        $this->getMessenger()->addMessage('messenger.cache.cleared', true);
        //przekierowanie na referer
        if ($request->getReferer()) {
            $this->getResponse()->redirectToUrl(urldecode($request->getReferer()));
        }
        $this->getResponse()->redirect('cmsAdmin');
    }

}

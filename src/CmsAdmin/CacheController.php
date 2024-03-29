<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin;

use Mmi\Cache\CacheInterface;
use Mmi\Cache\SystemCacheInterface;
use Mmi\Http\Request;
use Mmi\Mvc\Controller;

/**
 * Kontroler do obsługi cache Cms
 */
class CacheController extends Controller
{
    /**
     * @Inject
     */
    private CacheInterface $cache;

    /**
     * @Inject
     */
    private SystemCacheInterface $systemCache;

    /**
     * Czyści cały cache
     */
    public function indexAction(Request $request)
    {
        //czyszczenie cache
        $this->cache->flush();
        $this->systemCache->flush();
        //messenger
        $this->getMessenger()->addMessage('messenger.cache.cleared', true);
        //przekierowanie na referer
        if ($request->getReferer()) {
            $this->getResponse()->redirectToUrl(urldecode($request->getReferer()));
        }
        $this->getResponse()->redirect('cmsAdmin');
    }
}

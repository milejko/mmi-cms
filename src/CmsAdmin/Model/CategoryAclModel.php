<?php

namespace CmsAdmin\Model;

use Mmi\App\App;
use Mmi\Cache\CacheInterface;

/**
 * Model uprawnień edycji kategorii
 */
class CategoryAclModel
{
    public const CACHE_KEY = 'mmi-cms-category-acl';

    /**
     * Obiekt ACL
     */
    private CategoryAcl $acl;

    /**
     * Inicjalizacja danych
     */
    public function __construct()
    {
        //ładowanie ACL z bufora
        if (null !== $acl = App::$di->get(CacheInterface::class)->load($cacheKey = self::CACHE_KEY)) {
            $this->acl = $acl;
            return;
        }
        //uzupełnianie ACL
        $this->acl = new CategoryAcl();
        //iteracja po kolekcji rekordów categoryAcl
        foreach ((new \Cms\Orm\CmsCategoryAclQuery())
            ->join('cms_category')->on('cms_category_id')
            ->find() as $aclRecord) {
            //dodawanie ściezek
            $path = trim($aclRecord->getJoined('cms_category')->path . '/' . $aclRecord->getJoined('cms_category')->id, '/');
            $this->acl->addPathPermission($aclRecord->role, $path, 'allow' === $aclRecord->access);
        }
        //zapis cache
        App::$di->get(CacheInterface::class)->save($this->acl, $cacheKey);
    }

    /**
     * Zwraca obiekt ACL
     */
    public function getAcl(): CategoryAcl
    {
        return $this->acl;
    }
}

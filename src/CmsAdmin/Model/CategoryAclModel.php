<?php

namespace CmsAdmin\Model;

use Cms\Orm\CmsCategoryQuery;

/**
 * Model uprawnień edycji kategorii
 */
class CategoryAclModel
{

    const CACHE_KEY = 'mmi-cms-category-acl';

    /**
     * Obiekt ACL
     * @var \Mmi\Security\Acl
     */
    private $_acl;

    /**
     * Inicjalizacja danych
     */
    public function __construct()
    {
        //ładowanie ACL z bufora
        if (null !== $this->_acl = \App\Registry::$cache->load($cacheKey = self::CACHE_KEY)) {
            return;
        }
        //uzupełnianie ACL
        $this->_acl = new CategoryAcl;
        //iteracja po kolekcji rekordów categoryAcl
        foreach ((new \Cms\Orm\CmsCategoryAclQuery)
            ->join('cms_role')->on('cms_role_id')
            ->join('cms_category')->on('cms_category_id')
            ->find() as $aclRecord) {
            //dodawanie ściezek
            $role = $aclRecord->getJoined('cms_role')->name;
            $path = trim($aclRecord->getJoined('cms_category')->path . '/' . $aclRecord->getJoined('cms_category')->id, '/');
            $this->_acl->addPathPermission($role, $path, 'allow' === $aclRecord->access);
        }
        //zapis cache
        \App\Registry::$cache->save($this->_acl, $cacheKey);
    }

    /**
     * Zwraca obiekt ACL
     * @return \Mmi\Security\Acl
     */
    public function getAcl()
    {
        return $this->_acl;
    }

}

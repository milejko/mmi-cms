<?php

namespace CmsAdmin\Model;

use Cms\Orm\CmsCategoryQuery;
use Mmi\App\App;
use Mmi\Cache\CacheInterface;

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
        if (null !== $this->_acl = App::$di->get(CacheInterface::class)->load($cacheKey = self::CACHE_KEY)) {
            return;
        }
        //uzupełnianie ACL
        $this->_acl = new \Mmi\Security\Acl;
        $this->_flatTree = (new \Cms\Model\CategoryModel(new CmsCategoryQuery()))->getCategoryFlatTree();
        //iteracja po kolekcji rekordów categoryAcl
        foreach ((new \Cms\Orm\CmsCategoryAclQuery)
            ->join('cms_role')->on('cms_role_id')
            ->find() as $aclRecord) {
            //aktualizacja acl
            $this->_updateAcl($aclRecord);
        }
        App::$di->get(CacheInterface::class)->save($this->_acl, $cacheKey);
    }

    /**
     * Zwraca obiekt ACL
     * @return \Mmi\Security\Acl
     */
    public function getAcl()
    {
        return $this->_acl;
    }

    /**
     * Aktualizuje ustawienie ACL
     * @param \Cms\Orm\CmsCategoryAclRecord $aclRecord
     */
    protected function _updateAcl(\Cms\Orm\CmsCategoryAclRecord $aclRecord)
    {
        //iteracja po identyfikatorach kategorii
        foreach ($this->_getChildrenCategoryIds($aclRecord->cmsCategoryId) as $categoryId) {
            //dozwalanie lub zabranianie w ACL
            $aclRecord->access == 'allow' ?
                    $this->_acl->allow($aclRecord->getJoined('cms_role')->name, $categoryId) :
                    $this->_acl->deny($aclRecord->getJoined('cms_role')->name, $categoryId);
        }
    }

    /**
     * Pobiera identyfikatory kategorii dzieci kategorii (wraz z własnym ID)
     * @param integer $categoryId
     * @return array
     */
    protected function _getChildrenCategoryIds($categoryId)
    {
        //brak kategorii
        if (null === ($chosenLabel = isset($this->_flatTree[$categoryId]) ? $this->_flatTree[$categoryId] : null)) {
            return [];
        }
        $categories = [];
        //iteracja po spłaszczonym drzewie
        foreach ($this->_flatTree as $id => $label) {
            //porównywanie labelki wzorcowek
            if ($chosenLabel == substr($label, 0, strlen($chosenLabel))) {
                //zgodny - dodwanie identyfikatora do listy
                $categories[$id] = $id;
            }
        }
        //zwrot identyfikatorów kategorii
        return $categories;
    }

}

<?php

namespace CmsAdmin\Model;

use Cms\Orm\CmsCategoryQuery;
use Cms\Orm\CmsCategoryRecord;
use Mmi\Security\Acl;

class CategoryAcl extends Acl
{
    private array $pathPermissions = [];

    /**
     * Nadpisana metoda opierająca się o ściezki a nie zasoby
     */
    public function isRoleAllowed($role, $resource)
    {
        $cache = \App\Registry::$cache;
        //pobranie ketegorii z cache lub bazy
        if (null === $category = $cache->load($cacheKey = CmsCategoryRecord::CATEGORY_CACHE_PREFIX . $resource)) {
            //zapis pobranej kategorii w cache
            $cache->save($category = (new CmsCategoryQuery())->findPk($resource), $cacheKey, 0);
        }
        return $this->isPathPermitted($role, trim($category->path . '/' . $category->id, '/'));
    }

    private function isPathPermitted(string $role, string $path): bool
    {
        //brak uprawnień dla roli
        if (!isset($this->pathPermissions[$role])) {
            return false;
        }
        $checkResult = false;
        foreach ($this->pathPermissions[$role] as $definedPath => $permission) {
            if ($definedPath != trim(substr($path, 0, strlen($definedPath) + 1), '/')) {
                continue;
            }
            if (false === $permission) {
                return false;
            }
            $checkResult = true;
        }
        return $checkResult;
    }

    public function addPathPermission(string $role, string $path, bool $allow): self
    {
        $this->pathPermissions[$role][$path] = $allow;
        return $this;
    }

}

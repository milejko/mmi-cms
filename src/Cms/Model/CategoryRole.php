<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Model;

/**
 * Model do sprawdzania dostępu do kategorii Cms przez użytkowników z rolami
 */
class CategoryRole
{

    /**
     * Obiekt kategorii Cms
     * @var \Cms\Orm\CmsCategoryRecord
     */
    protected $_category;
	
	/**
	 * Role, dla których chcemy sprawdzić dostęp
	 * @var array
	 */
	protected $_roles = [];
	
	/**
	 * Struktura dostępu do kategorii
	 * @var array
	 */
	protected $_acl = [];

    /**
     * Konstruktor
     * @param \Cms\Orm\CmsCategoryRecord $category
	 * @param array $roles
     */
    public function __construct(\Cms\Orm\CmsCategoryRecord $category, $roles = [])
    {
        $this->_category = $category;
		$this->_roles = $roles;
		$this->_prepareData();
    }
	
	/**
	 * Ustawia rekord kategorii
	 * @param \Cms\Orm\CmsCategoryRecord $category
	 * @return \Cms\Model\CategoryRole
	 */
	public function setCategory(\Cms\Orm\CmsCategoryRecord $category) {
		$this->_category = $category;
		return $this;
	}

    /**
     * Czy jest dostęp do kategorii Cms przez ustawione role - można wyświetlić
     * @return boolean
     */
    public function isAllowed()
    {
		//jeśli brak dostępu do aktualnej kategorii
		if (!$this->_checkAccess($this->_category->id)) {
			return false;
		}
		//jeśli brak dostępu do któregoś rodzica
		if (!$this->_checkParents()) {
			return false;
		}
        return true;
    }
	
	/**
	 * Lista ról, które są uprawnione do wyświetlenia ustawionej kategorii
	 * (jeśli brak ograniczeń, to zwracana jest pusta tablica)
	 * @return array
	 */
	public function allowedFor()
	{
		if (array_key_exists($this->_category->id, $this->_acl)) {
			return $this->_acl[$this->_category->id];
		}
		return [];
	}
	
	/**
	 * Przygotowuje dane - strukturę do sprawdzenia uprawnień
	 */
	protected function _prepareData()
	{
        //próba pobrania struktury uprawnień z cache
        if (null !== $this->_acl = \App\Registry::$cache->load($cacheKey = 'categories-roles')) {
			return;
        }
		$this->_acl = [];
		//zapytanie o całą strukturę powiązań: kategorie - role
		foreach ((new \Cms\Orm\CmsCategoryRoleQuery())
					->join('cms_role')->on('cms_role_id')
					->orderAscCmsCategoryId()
					->find() as $record) {
			if (!array_key_exists($record->cmsCategoryId, $this->_acl)) {
				$this->_acl[$record->cmsCategoryId] = [];
			}
			$this->_acl[$record->cmsCategoryId][] = $record->getJoined('cms_role')->name;
		}
        //zapis struktury uprawnień do cache
        \App\Registry::$cache->save($this->_acl, $cacheKey, 0);
	}
	
	/**
	 * Sprawdza dostęp do danej kategorii przez ustawione role
	 * @param integer $categoryId
	 * @return boolean
	 */
	protected function _checkAccess($categoryId)
	{
		//jeśli nie ma klucza, to brak ograniczeń dostępu
		if (!array_key_exists($categoryId, $this->_acl)) {
			return true;
		}
		//dla każdej roli użytkownika
		foreach ($this->_roles as $role) {
			//sprawdzenie czy dopuszczona
			if (in_array($role, $this->_acl[$categoryId])) {
				return true;
			}
		}
		return false;
	}
	
	/**
	 * Sprawdza dostęp do wszystkich rodziców danej kategorii
	 * @return boolean
	 */
	protected function _checkParents()
	{
		//pierwszy rodzic
		$parent = $this->_category->getParentRecord();
		//gdy jest rodzic
		while ($parent !== null) {
			//jeśli nie ma dostępu do tego rodzica
			if (!$this->_checkAccess($parent->id)) {
				return false;
			}
			//pobieramy kolejnego rodzica
			$parent = $parent->getParentRecord();
		}
		return true;
	}

}

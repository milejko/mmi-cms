<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Model;

use Cms\Orm\CmsCategoryQuery;
use Cms\Orm\CmsCategoryRecord;

/**
 * Model nawigacji
 */
class Navigation {

	/**
	 * Multiopcje nawigacji
	 * @return array
	 */
	public static function getMultioptions() {
		return [null => '---'] + (new CmsCategoryQuery)
				->lang()
				->orderAscParentId()
				->orderAscOrder()->findPairs('id', 'name');
	}

	/**
	 * Dodaje do konfiguracji dane z bazy danych
	 * @param \Mmi\Navigation\NavigationConfig $config
	 */
	public function decorateConfiguration(\Mmi\Navigation\NavigationConfig $config) {
		$objectArray = (new CmsCategoryQuery)
			->lang()
			->orderAscParentId()
			->orderAscOrder()
			->find()
			->toObjectArray();
		foreach ($objectArray as $key => $record) {/* @var $record CmsCategoryRecord */
			if ($record->parentId != 0) {
				continue;
			}
			$element = new \Mmi\Navigation\NavigationConfigElement($record->uri);
			$this->_setNavigationElementFromRecord($record, $element);
			$config->addElement($element);
			unset($objectArray[$key]);
			$this->_buildChildren($record, $element, $objectArray);
		}
	}

	/**
	 * Sortuje po zserializowanej tabeli identyfikatorów
	 * @param array $serial tabela identyfikatorów
	 * @return boolean
	 */
	public static function sortBySerial(array $serial = []) {
		foreach ($serial as $order => $id) {
			$record = (new CmsNavigationQuery)
				->findPk($id);
			if (!$record) {
				continue;
			}
			$record->order = $order;
			$record->save();
		}
		return true;
	}

	/**
	 * 
	 * @param \Cms\Orm\CmsNavigationRecord $record
	 * @param \Mmi\Navigation\NavigationConfigElement $element
	 * @param array $objectArray
	 */
	protected function _buildChildren(\Cms\Orm\CmsCategoryRecord $record, \Mmi\Navigation\NavigationConfigElement $element, array $objectArray) {
		foreach ($objectArray as $key => $child) {/* @var $child CmsNavigationRecord */
			if ($child->parentId != $record->id) {
				continue;
			}
			$childElement =  new \Mmi\Navigation\NavigationConfigElement($child->uri);
			self::_setNavigationElementFromRecord($child, $childElement);
			$element->addChild($childElement);
			//usunięcie wykorzystanego obiektu
			unset($objectArray[$key]);
			self::_buildChildren($child, $childElement, $objectArray);
		}
	}

	/**
	 * 
	 * @param \Cms\Orm\CmsNavigationRecord $record
	 * @param \Mmi\Navigation\NavigationConfigElement $element
	 * @return \Mmi\Navigation\NavigationConfigElement
	 */
	protected function _setNavigationElementFromRecord(CmsCategoryRecord $record, \Mmi\Navigation\NavigationConfigElement $element) {
		$params = [];
		parse_str($record->mvcParams, $params);
		$params['uri'] = $record->customUri ? $record->customUri : $record->uri;

		$element
			->setModule('cms')
			->setController('category')
			->setAction('dispatch')
			->setParams($params)
			->setBlank($record->blank ? true : false)
			->setDescription($record->description)
			->setDisabled($record->active ? false : true)
			->setHttps($record->https)
			->setUri($record->redirectUri ? : null)
			->setLabel($record->name)
			->setLang($record->lang)
			->setFollow($record->follow ? true : false)
			->setTitle($record->title)
			->setConfig($record->getConfig())
			->setDateStart($record->dateStart)
			->setDateEnd($record->dateEnd);
		return $element;
	}

}

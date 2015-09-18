<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Model;
use \Cms\Orm;

class Navigation {

	/**
	 * Multiopcje nawigacji
	 * @return array
	 */
	public static function getMultiOptions() {
		return [null => '---'] + Orm\Navigation\Query::lang()
				->orderAscParentId()
				->orderAscOrder()->findPairs('id', 'label');
	}

	/**
	 * Dodaje do konfiguracji dane z bazy danych
	 * @param \Mmi\Navigation\Config $config
	 */
	public static function decorateConfiguration(\Mmi\Navigation\Config $config) {
		$objectArray = Orm\Navigation\Query::lang()
			->orderAscParentId()
			->orderAscOrder()
			->find()
			->toObjectArray();
		foreach ($objectArray as $key => $record) {/* @var $record \Cms\Orm\Navigation\Record */
			if ($record->parentId != 0) {
				continue;
			}
			$element = \Mmi\Navigation\Config::newElement($record->id);
			self::_setNavigationElementFromRecord($record, $element);
			$config->addElement($element);
			unset($objectArray[$key]);
			self::_buildChildren($record, $element, $objectArray);
		}
	}

	/**
	 * Sortuje po zserializowanej tabeli identyfikatorów
	 * @param array $serial tabela identyfikatorów
	 * @return boolean
	 */
	public static function sortBySerial(array $serial = []) {
		foreach ($serial as $order => $id) {
			$record = Orm\Navigation\Query::factory()
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
	 * @param \Cms\Orm\Navigation\Record $record
	 * @param \Mmi\Navigation\Config\Element $element
	 * @param array $objectArray
	 */
	protected static function _buildChildren(\Cms\Orm\Navigation\Record $record, \Mmi\Navigation\Config\Element $element, array $objectArray) {
		foreach ($objectArray as $key => $child) {/* @var $child \Cms\Orm\Navigation\Record */
			if ($child->parentId != $record->id) {
				continue;
			}
			$childElement = \Mmi\Navigation\Config::newElement($child->id);
			self::_setNavigationElementFromRecord($child, $childElement);
			$element->addChild($childElement);
			unset($objectArray[$key]);
			self::_buildChildren($child, $childElement, $objectArray);
		}
	}

	/**
	 * 
	 * @param \Cms\Orm\Navigation\Record $record
	 * @param \Mmi\Navigation\Config\Element $element
	 * @return \Mmi\Navigation\Config\Element
	 */
	protected static function _setNavigationElementFromRecord(\Cms\Orm\Navigation\Record $record, \Mmi\Navigation\Config\Element $element) {
		$https = null;
		if ($record->https === 0) {
			$https = false;
		} elseif ($record->https === 1) {
			$https = true;
		}

		$params = [];
		parse_str($record->params, $params);

		$element
			->setAbsolute($record->absolute ? true : false)
			->setAction($record->action ? : null)
			->setBlank($record->blank ? true : false)
			->setController($record->controller ? : null)
			->setDateEnd($record->dateEnd ? : null)
			->setDateStart($record->dateStart ? : null)
			->setDescription($record->description ? : null)
			->setDisabled($record->active ? false : true)
			->setHttps($https)
			->setIndependent($record->independent ? : null)
			->setKeywords($record->keywords ? : null)
			->setLabel($record->label ? : null)
			->setLang($record->lang ? : null)
			->setModule($record->module ? : null)
			->setNofollow($record->nofollow ? : null)
			->setParams($params)
			->setTitle($record->title ? : null)
			->setUri($record->uri ? : null)
			->setVisible($record->visible ? true : false)
		;
		return $element;
	}

}

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

class Log {

	/**
	 * Dodaje zdarzenie do logu
	 * @param string $operation operacja
	 * @param array $data dane
	 * @return bool czy dodano
	 */
	public static function add($operation = null, array $data = []) {
		$record = new Orm\Log\Record();
		$env = \Mmi\Controller\Front::getInstance()->getEnvironment();
		if (\Mmi\Session::namespaceIsset('Auth')) {
			$authNamespace = new \Mmi\Session\Space('Auth');
			$record->cmsAuthId = $authNamespace->id;
		}
		$record->url = $env->requestUri;
		$record->ip = $env->remoteAddress;
		$record->browser = $env->httpUserAgent;
		$record->dateTime = date('Y-m-d H:i:s');
		$record->operation = $operation;
		$record->success = 1;
		if (!empty($data)) {
			if (isset($data['success'])) {
				$record->success = $data['success'] ? 1 : 0;
				unset($data['success']);
			}
			if (isset($data['object'])) {
				$record->object = $data['object'];
				unset($data['object']);
			}
			if (isset($data['objectId'])) {
				$record->objectId = $data['objectId'];
				unset($data['objectId']);
			}
			if (isset($data['cms_auth_id']) && !$record->cmsAuthId) {
				$record->cmsAuthId = $data['cms_auth_id'];
				unset($data['cms_auth_id']);
			}
			if (!empty($data)) {
				$record->data = serialize($data);
			}
		}
		return $record->save();
	}

	/**
	 * Czyści loga, domyslnie ostatnie 24 miesiace
	 * @param integer $months
	 * @return integer
	 */
	public static function clean($months = 24) {
		return Orm\Log\Query::factory()
				->whereDateTime()->less(date('Y-m-d H:i:s', strtotime('-' . $months . ' month')))
				->find()
				->delete();
	}

}

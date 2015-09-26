<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\App;

/**
 * Plugin front kontrolera (hooki)
 */
class FrontControllerPlugin extends \Mmi\App\FrontControllerPluginAbstract {

	/**
	 * Hook przed routingiem
	 * dodaje do routera routy przechowywane w bazie CMS
	 * @param \Mmi\Http\Request $request
	 */
	public function routeStartup(\Mmi\Http\Request $request) {
		//routy z cms
		if (null === ($routes = \App\Registry::$cache->load('Mmi-Route'))) {
			//zapis rout do cache
			\App\Registry::$cache->save($routes = \Cms\Orm\Route\Query::active()->find(), 'Mmi-Route', 0);
		}
		//aktualizacja konfiguracji routera  routy CMS
		\Cms\Model\Route::updateRouterConfig(\Mmi\App\FrontController::getInstance()->getRouter()->getConfig(), $routes);
	}

	/**
	 * Przed uruchomieniem dispatchera
	 * @param \Mmi\Http\Request $request
	 */
	public function preDispatch(\Mmi\Http\Request $request) {
		//niepoprawny język
		if ($request->__get('lang') && !in_array($request->__get('lang'), \App\Registry::$config->languages)) {
			throw new \Mmi\Mvc\NotFoundException('Language not found');
		}
		//ustawianie widoku
		$this->_viewSetup($request);

		//konfiguracja autoryzacji
		$auth = new \Mmi\Security\Auth();
		$auth->setSalt(\App\Registry::$config->salt);
		$auth->setModelName(\App\Registry::$config->session->authModel ? \App\Registry::$config->session->authModel : '\Cms\Model\Auth');
		\App\Registry::$auth = $auth;
		\Mmi\Mvc\ActionHelper::getInstance()->setAuth($auth);

		//funkcja pamiętaj mnie realizowana poprzez cookie
		$cookie = new \Mmi\Http\Cookie();
		$remember = \App\Registry::$config->session->authRemember ? \App\Registry::$config->session->authRemember : 0;
		if ($remember > 0 && !$auth->hasIdentity() && $cookie->match('remember')) {
			$params = [];
			parse_str($cookie->getValue(), $params);
			if (isset($params['id']) && isset($params['key']) && $params['key'] == md5(\App\Registry::$config->salt . $params['id'])) {
				$auth->setIdentity($params['id']);
				$auth->idAuthenticate();
			}
		}
		//autoryzacja do widoku
		if ($auth->hasIdentity()) {
			\Mmi\App\FrontController::getInstance()->getView()->auth = $auth;
		}

		//ustawienie acl
		if (null === ($acl = \App\Registry::$cache->load('Mmi-Acl'))) {
			$acl = \Cms\Model\Acl::setupAcl();
			\App\Registry::$cache->save($acl, 'Mmi-Acl', 0);
		}
		\App\Registry::$acl = $acl;
		\Mmi\Mvc\ActionHelper::getInstance()->setAcl($acl);
		\Mmi\App\FrontController::getInstance()->getView()->acl = $acl;

		//zablokowane na ACL
		if (!$acl->isAllowed($auth->getRoles(), strtolower($request->getModuleName() . ':' . $request->getControllerName() . ':' . $request->getActionName()))) {
			//brak autoryzacji i kontroler admina - przekierowanie na logowanie
			if (!$auth->hasIdentity() && $request->getModuleName() == 'cmsAdmin') {
				//logowanie admina
				$this->_setAdminLoginRequest($request);
			} elseif (!$auth->hasIdentity()) {
				//logowanie użytkownika
				$this->_setUserLoginRequest($request);
			} else {
				//zalogowany na nieuprawnioną rolę
				throw new \Mmi\Mvc\NotFoundException('Unauthorized access');
			}
		}
		//ustawienie nawigatora
		if (null === ($navigation = \App\Registry::$cache->load('Mmi-Navigation-' . $request->__get('lang')))) {
			/* @var $config \App\Config\Navigation */
			$config = \App\Registry::$config->navigation;
			\Cms\Model\Navigation::decorateConfiguration($config);
			$navigation = new \Mmi\Navigation\Navigation($config);
			\App\Registry::$cache->save($navigation, 'Mmi-Navigation-' . $request->__get('lang'), 0);
		}
		$navigation->setup($request);
		//przypinanie nawigatora do helpera widoku nawigacji
		\Mmi\Mvc\ViewHelper\Navigation::setAcl($acl);
		\Mmi\Mvc\ViewHelper\Navigation::setAuth($auth);
		\Mmi\Mvc\ViewHelper\Navigation::setNavigation($navigation);
	}

	/**
	 * Ustawienie zmiennych w widoku
	 * @param \Mmi\Http\Request $request
	 */
	protected function _viewSetup(\Mmi\Http\Request $request) {
		//ustawienie widoku
		$view = \Mmi\App\FrontController::getInstance()->getView();
		$base = $view->baseUrl;
		$view->domain = \App\Registry::$config->host;
		$view->languages = \App\Registry::$config->languages;
		$jsRequest = $request->toArray();
		$jsRequest['baseUrl'] = $base;
		unset($jsRequest['controller']);
		unset($jsRequest['action']);
		//umieszczenie tablicy w headScript()
		$view->headScript()->appendScript('var request = ' . json_encode($jsRequest));
	}

	/**
	 * Ustawia request na logowanie admina
	 * @param \Mmi\Http\Request $request
	 */
	protected function _setAdminLoginRequest(\Mmi\Http\Request $request) {
		$request->setModuleName('cmsAdmin')
			->setControllerName('index')
			->setActionName('login');
	}

	/**
	 * Ustawia request na logowanie admina
	 * @param \Mmi\Http\Request $request
	 */
	protected function _setUserLoginRequest(\Mmi\Http\Request $request) {
		$request->setModuleName('cms')
			->setControllerName('user')
			->setActionName('login');
	}

}

<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\App;

use App\Registry;
use CmsAdmin\Mvc\ViewHelper\AdminNavigation;
use Mmi\App\FrontController;
use Mmi\App\FrontControllerPluginAbstract;
use Mmi\Mvc\ActionHelper;
use Mmi\Mvc\ViewHelper\Navigation;

/**
 * Plugin front kontrolera (hooki)
 */
class CmsFrontControllerPlugin extends FrontControllerPluginAbstract
{

    /**
     * Przed uruchomieniem dispatchera
     * @param \Mmi\Http\Request $request
     * @return \Mmi\Http\Request|void
     * @throws \Mmi\Mvc\MvcNotFoundException
     */
    public function preDispatch(\Mmi\Http\Request $request)
    {
        //init translation
        $this->_initTranslation($request);
        //konfiguracja autoryzacji
        $auth = new \Mmi\Security\Auth;
        $auth->setSalt(Registry::$config->salt)
            ->setModelName(Registry::$config->session->authModel ? Registry::$config->session->authModel : '\Cms\Model\Auth');
        Registry::$auth = $auth;
        ActionHelper::getInstance()->setAuth($auth);
        Navigation::setAuth($auth);
        AdminNavigation::setAuth($auth);

        //funkcja pamiętaj mnie realizowana poprzez cookie
        $cookie = new \Mmi\Http\Cookie;
        $remember = Registry::$config->session->authRemember ? Registry::$config->session->authRemember : 0;
        if ($remember > 0 && !$auth->hasIdentity() && $cookie->match('remember')) {
            $params = [];
            parse_str($cookie->getValue(), $params);
            if (isset($params['id']) && isset($params['key']) && $params['key'] == md5(Registry::$config->salt . $params['id'])) {
                $auth->setIdentity($params['id']);
                $auth->idAuthenticate();
                //regeneracja ID sesji po autoryzacji
                \Mmi\Session\Session::regenerateId();
            }
        }
        //autoryzacja do widoku
        if ($auth->hasIdentity()) {
            FrontController::getInstance()->getView()->auth = $auth;
        }
        //ustawienie acl
        if (null === ($acl = Registry::$cache->load('mmi-cms-acl'))) {
            $acl = \Cms\Model\Acl::setupAcl();
            Registry::$cache->save($acl, 'mmi-cms-acl', 0);
        }
        FrontController::getInstance()->getView()->acl = Registry::$acl = $acl;
        ActionHelper::getInstance()->setAcl($acl);
        Navigation::setAcl($acl);
        AdminNavigation::setAcl($acl);
        //ustawienie nawigatora
        if (null === ($navigation = Registry::$cache->load('mmi-cms-navigation-' . $request->__get('lang')))) {
            //dekoracja nawigatora danymi z bazy (jeśli włączone w configu)
            Registry::$config->navigationCategoriesEnabled ? (new \Cms\Model\Navigation)->decorateConfiguration(Registry::$config->navigation) : null;
            $navigation = new \Mmi\Navigation\Navigation(Registry::$config->navigation);
            //zapis do cache
            Registry::$cache->save($navigation, 'mmi-cms-navigation-' . $request->__get('lang'), 0);
        }
        $navigation->setup($request);
        //przypinanie nawigatora do helpera widoku nawigacji
        \Mmi\Mvc\ViewHelper\Navigation::setNavigation(Registry::$navigation = $navigation);
        \CmsAdmin\Mvc\ViewHelper\AdminNavigation::setNavigation(Registry::$navigation = $navigation);
        //zablokowane na ACL
        if ($acl->isAllowed($auth->getRoles(), $actionLabel = strtolower($request->getModuleName() . ':' . $request->getControllerName() . ':' . $request->getActionName()))) {
            return;
        }
        $moduleStructure = FrontController::getInstance()->getStructure('module');
        //brak w strukturze
        if (!isset($moduleStructure[$request->getModuleName()][$request->getControllerName()][$request->getActionName()])) {
            throw new \Mmi\Mvc\MvcNotFoundException('Component not found: ' . $actionLabel);
        }
        //brak autoryzacji i kontroler admina - przekierowanie na logowanie
        if (!$auth->hasIdentity()) {
            //logowanie admina
            return $this->_setLoginRequest($request, strpos($request->getModuleName(), 'Admin'));
        }
        Registry::$auth->clearIdentity();
        //zalogowany na nieuprawnioną rolę
        throw new \Mmi\Mvc\MvcNotFoundException('Unauthorized access');
    }

    /**
     * Wykonywana po dispatcherze
     * @param \Mmi\Http\Request $request
     */
    public function postDispatch(\Mmi\Http\Request $request)
    {
        //ustawienie widoku
        $view = FrontController::getInstance()->getView();
        $base = $view->baseUrl;
        $view->domain = Registry::$config->host;
        $view->languages = Registry::$config->languages;
        $jsRequest = $request->toArray();
        $jsRequest['baseUrl'] = $base;
        $jsRequest['locale'] = Registry::$translate->getLocale();
        unset($jsRequest['controller']);
        unset($jsRequest['action']);
        //umieszczenie tablicy w headScript()
        $view->headScript()->prependScript('var request = ' . json_encode($jsRequest));
    }

    /**
     * Wykonywana przed wysłaniem treści
     * @param \Mmi\Http\Request $request
     */
    public function beforeSend(\Mmi\Http\Request $request)
    {
        //pobranie odpowiedzi
        $response = FrontController::getInstance()->getResponse();
        //zmiana contentu
        $response->setContent($response->getContent());
    }

    /**
     * Ustawia request na logowanie admina
     * @param \Mmi\Http\Request $request
     * @return \Mmi\Http\Request
     */
    protected function _setLoginRequest(\Mmi\Http\Request $request, $preferAdmin)
    {
        //logowanie bez preferencji admina, tylko gdy uprawniony
        if (false === $preferAdmin && Registry::$acl->isRoleAllowed('guest', 'cms:user:login')) {
            return $request->setModuleName('cms')
                ->setControllerName('user')
                ->setActionName('login');
        }
        //logowanie admina
        return $request->setModuleName('cmsAdmin')
            ->setControllerName('index')
            ->setActionName('login');
    }

    /**
     * Inicjalizacja tłumaczeń
     */
    protected function _initTranslation(\Mmi\Http\Request $request)
    {
        //języki nie zdefiniowane
        if (empty(Registry::$config->languages)) {
            return;
        }
        //niepoprawny język
        if ($request->__get('lang') && !in_array($request->__get('lang'), Registry::$config->languages)) {
            throw new \Mmi\Mvc\MvcNotFoundException('Language not found');
        }
        //ustawianie języka z requesta
        if ($request->__get('lang')) {
            return Registry::$translate->setLocale($request->__get('lang'));
        }
        //ustawienie języka edycji
        $session = new \Mmi\Session\SessionSpace('cms-language');
        $lang = in_array($session->lang, Registry::$config->languages) ? $session->lang : Registry::$config->languages[0];
        Registry::$translate->setLocale($lang);
    }
}

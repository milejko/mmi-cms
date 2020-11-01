<?php

namespace Cms\App;

use CmsAdmin\App\CmsNavigationConfig;
use CmsAdmin\Mvc\ViewHelper\AdminNavigation;
use Mmi\App\AppEventInterceptorAbstract;
use Mmi\Cache\Cache;
use Mmi\Http\HttpServerEnv;
use Mmi\Http\Request;
use Mmi\Http\Response;
use Mmi\Mvc\ActionHelper;
use Mmi\Mvc\View;
use Mmi\Mvc\ViewHelper\Navigation;
use Mmi\Security\Auth;
use Mmi\Session\Session;
use Mmi\Translate;

class CmsAppEventInterceptor extends AppEventInterceptorAbstract
{

    public function init(): void
    {
        $this->_initTranslation();
    }

    public function beforeDispatch(): void
    {
        $request = $this->container->get(Request::class);
        $this->container->get(Session::class)->start();
        //konfiguracja autoryzacji
        $auth = new \Mmi\Security\Auth;
        $auth->setSalt('@TODO: real salt')
            ->setModelName('\Cms\Model\Auth');

        //setup authorization
        $this->container->set(Auth::class, $auth);
        $this->container->get(ActionHelper::class)->setAuth($auth);
        $this->container->get(View::class)->auth = $auth->hasIdentity() ? $auth : null;
        Navigation::setAuth($auth);
        AdminNavigation::setAuth($auth);

        $cache = $this->container->get(Cache::class);
        //ustawienie acl
        if (null === ($acl = $cache->load('mmi-cms-acl'))) {
            $acl = \Cms\Model\Acl::setupAcl();
            $cache->save($acl, 'mmi-cms-acl', 0);
        }
        $this->container->set('cms.acl', $acl);
        $this->container->get(ActionHelper::class)->setAcl($acl);
        Navigation::setAcl($acl);
        AdminNavigation::setAcl($acl);

        //ustawienie nawigatora
        if (null === ($navigation = $cache->load('mmi-cms-navigation-' . $request->lang))) {
            $config = new CmsNavigationConfig;
            (new \Cms\Model\Navigation)->decorateConfiguration($config);
            $navigation = new \Mmi\Navigation\Navigation($config);
            //zapis do cache
            $cache->save($navigation, 'mmi-cms-navigation-' . $request->lang, 0);
        }
        $navigation->setup($request);
        //przypinanie nawigatora do helpera widoku nawigacji
        \Mmi\Mvc\ViewHelper\Navigation::setNavigation($navigation);
        \CmsAdmin\Mvc\ViewHelper\AdminNavigation::setNavigation($navigation);
        //zablokowane na ACL
        $actionLabel = strtolower($request->getModuleName() . ':' . $request->getControllerName() . ':' . $request->getActionName());
        if ($acl->isAllowed($auth->getRoles(), $actionLabel)) {
            return;
        }
        $moduleStructure = $this->container->get('app.structure')['module'];

        //brak w strukturze
        if (!isset($moduleStructure[$request->getModuleName()][$request->getControllerName()][$request->getActionName()])) {
            throw new \Mmi\Mvc\MvcNotFoundException('Component not found: ' . $actionLabel);
        }
        //brak autoryzacji i kontroler admina - przekierowanie na logowanie
        if (!$auth->hasIdentity()) {
            $this->_setLoginRequest($request, strpos($request->getModuleName(), 'Admin'));
            //logowanie admina
            return;
        }
        $auth->clearIdentity();
        //zalogowany na nieuprawnioną rolę
        throw new \Mmi\Mvc\MvcNotFoundException('Unauthorized access');
    }

    public function afterDispatch(): void
    {
        $request = $this->container->get(Request::class);
        //ustawienie widoku
        $view = $this->container->get(View::class);
        $base = $view->baseUrl;
        $view->domain = $this->container->get(HttpServerEnv::class)->httpHost;
        $view->languages = explode(',', $this->container->get('cms.lang.available'));
        $jsRequest = $request->toArray();
        $jsRequest['baseUrl'] = $base;
        $jsRequest['locale'] = $this->container->get(Translate::class)->getLocale();
        unset($jsRequest['controller']);
        unset($jsRequest['action']);
        //umieszczenie tablicy w headScript()
        $view->headScript()->prependScript('var request = ' . json_encode($jsRequest));
    }

    public function beforeSend(): void
    {
        //pobranie odpowiedzi
        $response = $this->container->get(Response::class);
        //filtracja contentu (ścieki do plików z tinymce)
        $response->setContent((new \Cms\Model\ContentFilter($response->getContent()))->getFilteredContent());
    }

    /**
     * Inicjalizacja tłumaczeń
     */
    protected function _initTranslation()
    {
        /**
         * @var Translate $translate 
         */
        $translate = $this->container->get(Translate::class);
        $request   = $this->container->get(Request::class);
        $translate->setLocale($this->container->get('cms.lang.default'));
        $availableLanguages = explode(',', $this->container->get('cms.lang.available'));
        //języki nie zdefiniowane
        if (empty($availableLanguages)) {
            return;
        }
        //niepoprawny język
        if ($request->lang && !in_array($request->lang, $availableLanguages)) {
            throw new \Mmi\Mvc\MvcNotFoundException('Language not found');
        }
        //ustawianie języka z requesta
        if ($request->lang) {
            return $translate->setLocale($request->lang);
        }
        //ustawienie języka edycji
        $session = new \Mmi\Session\SessionSpace('cms-language');
        if ($session->lang && in_array($session->lang, $availableLanguages)) {
            $translate->setLocale($session->lang);
        }
    }

    /**
     * Ustawia request na logowanie admina
     * @param \Mmi\Http\Request $request
     * @return \Mmi\Http\Request
     */
    protected function _setLoginRequest(\Mmi\Http\Request $request, $preferAdmin)
    {
        //logowanie bez preferencji admina, tylko gdy uprawniony
        if (false === $preferAdmin && $this->container->get('cms.acl')->isRoleAllowed('guest', 'cms:user:login')) {
            return $request->setModuleName('cms')
                ->setControllerName('user')
                ->setActionName('login');
        }
        //logowanie admina
        return $request->setModuleName('cmsAdmin')
            ->setControllerName('index')
            ->setActionName('login');
    }

}
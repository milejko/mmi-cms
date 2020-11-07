<?php

namespace Cms\App;

use Mmi\App\AppEventInterceptorAbstract;
use Mmi\Http\HttpServerEnv;
use Mmi\Http\Request;
use Mmi\Http\Response;
use Mmi\Mvc\View;
use Mmi\Security\Acl;
use Mmi\Security\Auth;
use Mmi\Session\Session;
use Mmi\Translate;

class CmsAppEventInterceptor extends AppEventInterceptorAbstract
{

    public function init(): void
    {}

    public function beforeDispatch(): void
    {
        $this->_initTranslation();
        $request = $this->container->get(Request::class);
        $this->container->get(Session::class)->start();

        //zablokowane na ACL
        $acl = $this->container->get(Acl::class);
        $auth = $this->container->get(Auth::class);
        $actionLabel = strtolower($request->getModuleName() . ':' . $request->getControllerName() . ':' . $request->getActionName());
        if ($acl->isAllowed($auth->getRoles(), $actionLabel)) {
            return;
        }
        //brak autoryzacji
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
        $view->languages = explode(',', $this->container->get('cms.language.list'));
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
        $translate->setLocale($this->container->get('cms.language.default'));
        $availableLanguages = explode(',', $this->container->get('cms.language.list'));
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
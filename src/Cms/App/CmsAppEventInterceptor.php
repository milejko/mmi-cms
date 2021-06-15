<?php

namespace Cms\App;

use Mmi\App\AppEventInterceptorAbstract;
use Mmi\Http\Request;
use Mmi\Mvc\View;
use Mmi\Security\AclInterface;
use Mmi\Security\AuthInterface;
use Mmi\Session\SessionInterface;
use Mmi\Translate\TranslateInterface;

class CmsAppEventInterceptor extends AppEventInterceptorAbstract
{
    const API_CONTROLLER_PATTERN = '/api$/i';

    public function init(): void
    {
        $this->container->get(CmsScopeConfig::class)->setName('other-skin');
    }

    public function beforeDispatch(): void
    {
        $request = $this->container->get(Request::class);
        //api
        if ($this->isApiController($request)) {
            return;
        }
        $this->initTranslation();
        $this->container->get(SessionInterface::class)->start();
        //wybranie domyślnego scope jeśli brak
        if (!$this->container->get(CmsScopeConfig::class)->getName()) {
            $firstSkin = current($this->container->get(CmsSkinsetConfig::class)->getSkins());
            $this->container->get(CmsScopeConfig::class)->setName($firstSkin->getKey());
        }
        //zablokowane na ACL
        $acl = $this->container->get(AclInterface::class);
        $auth = $this->container->get(AuthInterface::class);
        $actionLabel = strtolower($request->getModuleName() . ':' . $request->getControllerName() . ':' . $request->getActionName());
        if ($acl->isAllowed($auth->getRoles(), $actionLabel)) {
            return;
        }
        //brak autoryzacji
        if (!$auth->hasIdentity()) {
            $this->setLoginRequest($request, strpos($request->getModuleName(), 'Admin'));
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
        //api
        if ($this->isApiController($request)) {
            return;
        }
        //ustawienie widoku
        $view = $this->container->get(View::class);
        $base = $view->baseUrl;
        $view->domain = $request->getServer()->httpHost;
        $view->languages = explode(',', $this->container->get('cms.language.list'));
        $jsRequest = $request->toArray();
        $jsRequest['baseUrl'] = $base;
        $jsRequest['locale'] = $this->container->get(TranslateInterface::class)->getLocale();
        unset($jsRequest['controller']);
        unset($jsRequest['action']);
        //umieszczenie tablicy w headScript()
        $view->headScript()->prependScript('var request = ' . json_encode($jsRequest));
    }

    public function beforeSend(): void
    {
    }

    /**
     * Inicjalizacja tłumaczeń
     */
    protected function initTranslation(): void
    {
        /**
         * @var TranslateInterface $translate 
         */
        $translate = $this->container->get(TranslateInterface::class);
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
            $translate->setLocale($request->lang);
        }
    }

    /**
     * Ustawia request na logowanie admina
     * @param \Mmi\Http\Request $request
     * @return \Mmi\Http\Request
     */
    protected function setLoginRequest(Request $request): void
    {
        //logowanie admina
        $request->setModuleName('cmsAdmin')
            ->setControllerName('index')
            ->setActionName('login');
    }

    protected function isApiController(Request $request): bool
    {
        return (bool) \preg_match(self::API_CONTROLLER_PATTERN, $request->getControllerName());
    }
}

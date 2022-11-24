<?php

namespace Cms\App;

use Mmi\App\AppEventInterceptorInterface;
use Mmi\App\AppProfilerInterface;
use Mmi\Http\Request;
use Mmi\Mvc\MvcForbiddenException;
use Mmi\Mvc\MvcNotFoundException;
use Mmi\Mvc\View;
use Mmi\Security\AclInterface;
use Mmi\Security\AuthInterface;
use Mmi\Session\SessionInterface;
use Mmi\Translate\TranslateInterface;
use Psr\Container\ContainerInterface;

class CmsAppEventInterceptor implements AppEventInterceptorInterface
{
    public const API_CONTROLLER_PATTERN = '/api$/i';

    /**
     * @Inject({"cmsLanguageList" = "cms.language.list", "cmsLanguageDefault" = "cms.language.default"})
     */
    public function __construct(
        protected ContainerInterface $container,
        protected AppProfilerInterface $profiler,
        protected Request $request,
        protected CmsScopeConfig $cmsScopeConfig,
        protected SessionInterface $session,
        protected CmsSkinsetConfig $cmsSkinsetConfig,
        protected View $view,
        protected TranslateInterface $translate,
        protected string $cmsLanguageList,
        protected string $cmsLanguageDefault
    ) {
    }

    public function init(): void
    {
    }

    public function beforeDispatch(): void
    {
        //api
        if ($this->isApiController($this->request)) {
            return;
        }
        $this->initTranslation();
        $this->session->start();
        //wybranie domyślnego scope jeśli brak
        if (!$this->cmsScopeConfig->getName()) {
            $firstSkin = current($this->cmsSkinsetConfig->getSkins());
            $firstSkin && $this->cmsScopeConfig->setName($firstSkin->getKey());
        }
        //zablokowane na ACL
        $acl = $this->container->get(AclInterface::class);
        $auth = $this->container->get(AuthInterface::class);
        $actionLabel = strtolower($this->request->getModuleName() . ':' . $this->request->getControllerName() . ':' . $this->request->getActionName());
        //no module
        if (!$this->request->getModuleName()) {
            return;
        }
        //acl allowed
        if ($acl->isAllowed($auth->getRoles(), $actionLabel)) {
            return;
        }
        //brak autoryzacji
        if (!$auth->hasIdentity()) {
            $this->setLoginRequest($this->request, strpos($this->request->getModuleName(), 'Admin'));
            //logowanie admina
            return;
        }
        //zalogowany na nieuprawnioną rolę
        throw new MvcForbiddenException('Unauthorized access');
    }

    public function afterDispatch(): void
    {
        //api
        if ($this->isApiController($this->request)) {
            return;
        }
        //ustawienie widoku
        $this->view->domain = $this->request->getServer()->httpHost;
        $this->view->languages = explode(',', $this->cmsLanguageList);
        $jsRequest = $this->request->toArray();
        $jsRequest['locale'] = $this->translate->getLocale();
        unset($jsRequest['controller']);
        unset($jsRequest['action']);
        //umieszczenie tablicy w headScript()
        $this->view->headScript()->prependScript('var request = ' . json_encode($jsRequest));
    }

    public function beforeSend(): void
    {
    }

    /**
     * Inicjalizacja tłumaczeń
     */
    protected function initTranslation(): void
    {
        $this->translate->setLocale($this->cmsLanguageDefault);
        $availableLanguages = explode(',', $this->cmsLanguageList);
        //języki nie zdefiniowane
        if (empty($availableLanguages)) {
            return;
        }
        //niepoprawny język
        if ($this->request->lang && !in_array($this->request->lang, $availableLanguages)) {
            throw new MvcNotFoundException('Language not found');
        }
        //ustawianie języka z requesta
        if ($this->request->lang) {
            $this->translate->setLocale($this->request->lang);
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

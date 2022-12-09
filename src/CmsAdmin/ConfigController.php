<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2019 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin;

use Mmi\Http\ResponseDebugger;
use Mmi\Mvc\Controller;
use Psr\Container\ContainerInterface;

/**
 * Kontroler podglądu konfiguracji
 */
class ConfigController extends Controller
{
    public const THREE_DOTS = '(...)';

    /**
     * @Inject
     */
    private ContainerInterface $container;

    /**
     * @Inject
     */
    private ResponseDebugger $responseDebugger;

    /**
     * Widok konfiguracji
     */
    public function indexAction()
    {
        $containerEntries = [];
        foreach ($this->container->getKnownEntryNames() as $entryName) {
            if (!strpos($entryName, '.')) {
                continue;
            }
            $entry = $this->container->get($entryName);
            $containerEntries[$entryName] = strlen(\json_encode($entry)) > 160 ? self::THREE_DOTS : $entry;
        }
        $this->view->config = \Mmi\Http\ResponseDebugger\Colorify::colorify(print_r($containerEntries, true));
        $this->view->server = \Mmi\Http\ResponseDebugger\Colorify::colorify(print_r(\getenv(), true));
        $this->view->phpconfig = \Mmi\Http\ResponseDebugger\Colorify::colorify(print_r(ini_get_all(), true));
        $this->view->debugger = \Mmi\Http\ResponseDebugger\Colorify::colorify(print_r($this->responseDebugger->getArray(), true));
    }
}

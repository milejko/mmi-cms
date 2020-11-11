<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Model;

use Mmi\App\App;
use Mmi\Mvc\Messenger;
use Mmi\Security\AuthInterface;

class CategoryBuffering
{

    /**
     * Obiekt żądania
     * @var \Mmi\Http\Request
     */
    protected $_request;

    /**
     * @var AuthInterface
     */
    protected $auth;

    /**
     * @var Messenger
     */
    protected $messenger;

    /**
     * Konstruktor
     * @param \Mmi\Http\Request $request
     */
    public function __construct(\Mmi\Http\Request $request)
    {
        $this->_request = $request;
        //@TODO: proper DI
        $this->auth      = App::$di->get(AuthInterface::class);
        $this->messenger = App::$di->get(Messenger::class);
    }

    /**
     * Czy buforowanie danej kategorii (strony) jest dozwolone
     * @return $boolean
     */
    public function isAllowed()
    {
        foreach (get_class_methods($this) as $method) {
            if (substr($method, -4) !== 'Test') {
                continue;
            }
            if (!$this->$method()) {
                return false;
            }
        }
        return true;
    }

    /**
     * Jeśli użytkownik jest zalogowany, nie buforujemy
     * @return boolean
     */
    protected function _identityTest()
    {
        if ($this->auth->hasIdentity()) {
            return false;
        }
        return true;
    }

    /**
     * Jeśli messenger ma wiadomości, nie buforujemy
     * @return boolean
     */
    protected function _messengerTest()
    {
        if ($this->messenger->hasMessages()) {
            return false;
        }
        return true;
    }

    /**
     * Jeśli metoda żądania różna od GET, nie buforujemy
     * @return boolean
     */
    protected function _methodTest()
    {
        if ($this->_request->getRequestMethod() !== 'GET') {
            return false;
        }
        return true;
    }

}

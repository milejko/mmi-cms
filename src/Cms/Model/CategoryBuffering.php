<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Model;

class CategoryBuffering
{

    /**
     * Obiekt żądania
     * @var \Mmi\Http\Request
     */
    protected $_request;

    /**
     * Konstruktor
     * @param \Mmi\Http\Request $request
     */
    public function __construct(\Mmi\Http\Request $request)
    {
        $this->_request = $request;
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
        if (\App\Registry::$auth->hasIdentity()) {
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
        if (\Mmi\Message\MessengerHelper::getMessenger()
                        ->hasMessages()) {
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

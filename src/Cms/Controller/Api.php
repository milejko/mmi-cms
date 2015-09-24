<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Cms\Controller;

/**
 * Kontroler serwujący API JSON-RPC i SOAP
 */
class Api extends \Mmi\Controller\Action {

	/**
	 * Akcja serwera JSON-RPC
	 */
	public function jsonServerAction() {
		try {
			//ustawienie nagłówków
			$this->getResponse()
				//możliwość odczytu przez AJAX z innej domeny
				->setHeader('Access-Control-Allow-Origin', '*')
				->setHeader('Access-Control-Allow-Headers', 'Content-Type')
				//typ application/json
				->setTypeJson();
			$apiModel = $this->_getModelName($this->obj);
			//serwer z autoryzacją HTTP
			if (\Mmi\App\FrontController::getInstance()->getEnvironment()->authUser) {
				$apiModel .= '_Private';
				$auth = new \Mmi\Security\Auth();
				$auth->setModelName($apiModel);
				//autoryzacja basic
				$auth->httpAuth('Private API', 'Access denied!');
			}
			//obsługa żądania
			return \Mmi\Json\Rpc\Server::handle($apiModel);
		} catch (Exception $e) {
			//wyrzucenie internal server error
			return $this->_internalError($e);
		}
	}

	/**
	 * Akcja serwera SOAP
	 */
	public function soapServerAction() {
		try {
			$apiModel = $this->_getModelName($this->obj);
			//parametry WSDL
			$wsdlParams = [
				'module' => 'cms',
				'controller' => 'api',
				'action' => 'wsdl',
				'obj' => $this->obj,
			];
			//prywatny serwer
			if (\Mmi\App\FrontController::getInstance()->getEnvironment()->authUser) {
				$apiModel .= '_Private';
				$auth = new \Mmi\Security\Auth();
				$auth->setModelName($apiModel);
				$auth->httpAuth('Private API', 'Access denied!');
				$wsdlParams['type'] = 'private';
			}
			//ścieżka do WSDL
			$url = $this->view->url($wsdlParams, true, true, $this->_isSsl());
			//typ odpowiedzi na application/xml
			$this->getResponse()->setTypeXml();
			//powołanie klasy serwera SOAP
			$soap = new SoapServer($url);
			$soap->setClass($apiModel);
			//obsługa żądania
			$soap->handle();
			return '';
		} catch (Exception $e) {
			//rzuca wyjątek internal
			return $this->_internalError($e);
		}
	}

	/**
	 * Akcja zwracająca WSDL
	 */
	public function wsdlAction() {
		try {
			$apiModel = $this->_getModelName($this->obj);
			$serverParams = [
				'module' => 'cms',
				'controller' => 'api',
				'action' => 'soapServer',
				'obj' => $this->obj,
			];
			//serwer z autoryzacją (WSDL jest publiczny)
			if ($this->type == 'private' || \Mmi\App\FrontController::getInstance()->getEnvironment()->authUser) {
				$apiModel .= '_Private';
			}
			//link do serwera SOAP
			$url = $this->view->url($serverParams, true, true, $this->_isSsl());
			//typ odpowiedzi application/xml
			$this->getResponse()->setTypeXml();
			//@TODO: przepisać do ZF2
			require_once BASE_PATH . '/vendors/Zend/Soap/AutoDiscover.php';
			//rozpoznanie usługi (klasy)
			$autodiscover = new \Zend_Soap_AutoDiscover();
			$autodiscover->setClass($apiModel);
			$autodiscover->setUri($url);
			//obsługa żądania
			$autodiscover->handle();
			return '';
		} catch (Exception $e) {
			//internal server error
			$this->_internalError($e);
		}
	}

	/**
	 * Pobiera nazwę modelu na podstawie obiektu
	 * @param string $object
	 * @return string
	 */
	protected function _getModelName($object) {
		$obj = explode('\\', preg_replace('/[^\p{L}\p{N}-_]/u', '', $object));
		foreach ($obj as $k => $v) {
			$obj[$k] = ucfirst($v);
		}
		$class = $obj[0] . '\\Model\\';
		unset($obj[0]);
		return rtrim($class . implode('\\', $obj), '\\') . '\\Api';
	}

	/**
	 * Loguje błąd i wyrzuca 500tkę
	 * @param \Exception $e
	 * @return string
	 */
	protected function _internalError(\Exception $e) {
		\Mmi\App\ExceptionLogger::log($e);
		$this->getResponse()->setCodeError();
		return '<html><body><h1>Soap service failed</h1></body></html>';
	}

	/**
	 * Sprawdza czy połączenie SSL
	 * @return boolean
	 */
	protected function _isSsl() {
		return \Mmi\App\FrontController::getInstance()->getEnvironment()->httpSecure;
	}

}

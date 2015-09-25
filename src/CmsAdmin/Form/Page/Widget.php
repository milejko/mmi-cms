<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\Form\Page;

class Widget extends \Mmi\Form\Form {

	public function init() {

		$this->addElementText('name')
			->setLabel('Nazwa widgetu');

		$this->addElementSelect('widget')
			->setMultiOptions(array_merge(['' => ''], $this->_availableWidgetsAndComponents()))
			->setLabel('Wybierz widget lub komponent')
			->setValue($this->getOption('widget'));

		$this->addElementText('params')
			->setLabel('Domyślne parametry');

		$this->addElementCheckbox('active')
			->setLabel('Aktywny');

		$this->addElementSubmit('submit')
			->setLabel('Zapisz');
	}

	protected function _availableWidgetsAndComponents() {
		$widgets = [];
		$components = [];
		foreach (glob(BASE . '/src/*') as $module) {
			$moduleName = substr($module, strrpos($module, '/') + 1);
			foreach (glob($module . '/Controller/*.php') as $controller) {
				$var = file_get_contents($controller);
				$controllerName = substr($controller, strrpos($controller, '/') + 1, -4);
				if (preg_match_all('/function ([a-zA-Z0-9]+WidgetAction)\(/', $var, $actions) && isset($actions[1])) {
					foreach ($actions[1] as $action) {
						$action = substr($action, 0, -6);
						$widgets[$moduleName . ':' . $controllerName . ':' . $action] = $moduleName . ' - ' . $controllerName . ' - ' . $action;
					}
				}
				if ($moduleName === 'Component') {
					$var = file_get_contents($controller);
					$controllerName = substr($controller, strrpos($controller, '/') + 1, -4);
					if (preg_match_all('/function ([a-zA-Z0-9]+Action)\(/', $var, $actions) && isset($actions[1])) {
						foreach ($actions[1] as $action) {
							$action = substr($action, 0, -6);
							$components[$moduleName . ':' . $controllerName . ':' . $action] = $moduleName . ' - ' . $controllerName . ' - ' . $action;
						}
					}
				}
			}
		}
		return array_merge($widgets, $components);
	}

}

<?php

namespace app;
use router;
use FilesystemIterator;
use dbDriver;

Class starter {

	private $controller;
	private $action;
	private $params = '';
	private $config;
	private $router;

	//Подготоваливаем родительские классы для контроллеров и моделей, проверяем существование контроллера, грузим модели и нужный контроллер
	public function init($controller, $action, $params = null) {

		$controllerPath = $_SERVER['DOCUMENT_ROOT'].'/controllers/'.$controller.'.php';

		if($this->checkFile($controllerPath)) {

			require (__DIR__.'/baseController.php');
			require (__DIR__.'/baseModel.php');
			$this->loadModels();

			if(isset($params)) {

				$this->params = $params;
			}

			$this->loadController($controllerPath, $controller, $action);
		}

		else {

			$page = $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
			require(__DIR__.'/404.php');
		}
	}

	private function loadModels() {
		
		$modelsDir = $_SERVER['DOCUMENT_ROOT'].'/models/';

		$models = new FilesystemIterator($modelsDir);

		foreach ($models as $modelPath) {
			
			require_once($modelPath);
		}
	}

	private function checkFile($file) {

		return file_exists($file);
	}

	private function loadController($file, $controller, $action) {

		require ($file);
		//Добавляем пространство имен контроллеров и создаем объект контроллера
		$namespaceController = 'app\controllers\\'.$controller;
		$controllerObject = new $namespaceController;

		//Если экшн есть в контроллере, то вызвать его, иначе 404
		if(method_exists($controllerObject, $action)) {
			
			$controllerObject->$action($this->params);
		}

		else {

			$page = $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
			require(__DIR__.'/404.php');
		}

		//Добавляем viewProvider.php который занимается подключением и передачей данных на представление
		if($controllerObject->controller == $controller) {

			require_once (__DIR__.'/viewProvider.php');
		}
	}
}
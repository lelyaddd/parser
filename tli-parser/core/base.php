<?php

use app\starter;

//Запускатор контроллеров и класс работы с БД
require_once ($coreDir.'starter.php');
require_once ($coreDir.'dbDriver.php');

Class base {


	private $controller;
	private $action;
	private $params;
	private static $instance = false;

	private function __construct() {

	}

	private function __clone() {

	}

	private function __wakeup() {

	}

	public static function run() {

		if(!self::$instance) {

			//Если есть конфиг, то загружаем его, если нет, то это новая установка, то есть уводим маршрутизацию главной страницы на инсталятор
			if(file_exists($_SERVER['DOCUMENT_ROOT'].'/core/config.php')) {

				$config = self::loadConfig('uri');
			}

			else {

				$config = ['/' => '/installer'];
			}

			//Запускаем роутер на основе полученного конфига, создаем объект текущего класса и сразу же дергаем init
			
			$router = self::loadRouter($config);
			self::$instance = new self;
			self::$instance->init($router);
		}

	}

	public static function loadConfig($key) {

		require ($_SERVER['DOCUMENT_ROOT'].'/core/config.php');

		return $config[$key];
	}

	public static function loadRouter($config) {

		require ($_SERVER['DOCUMENT_ROOT'].'/core/router.php');

		$uri = $_SERVER['REQUEST_URI'];

		if(array_key_exists($uri, $config)) {

			$uri = $config[$uri];
		}

		$router = new router($uri);

		return $router;
	}

	public static function loadDb($config) {

		//Создается объект БД и возвращается
		$db = new dbDriver($config);
		return $db;
	}

	//Определяет контроллер и экшн из переданного роутера и создает объект starter, который вызывает заданный контроллер
	private function init($router) {

		$this->controller = $router->controller;
		$this->action = $router->action;

		if(!is_null($router->params)) {

			$this->params = $router->params;
			$starter = new starter;
			$starter->init($this->controller, $this->action, $this->params);
		}

		else {

			$starter = new starter;
			$starter->init($this->controller, $this->action);
		}
	}
}
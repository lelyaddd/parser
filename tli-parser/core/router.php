<?php

Class router {

	private $controller;
	private $action = 'index';
	private $params;

	public function __construct($uri) {

		$modifiedUri = urldecode(trim($uri, '/'));
		$uriArr = explode('/', $modifiedUri);

		$this->controller = strtolower($uriArr[0]);

		if(count($uriArr) >= 2) {

			$this->action = strtolower($uriArr[1]);
		}

		if(count($uriArr) == 3) {

			$this->params = $uriArr[2];
		}

		if(count($uriArr) > 3) {

			unset($uriArr[0]);
			unset($uriArr[1]);

			$this->params = implode('/', $uriArr);
		}

	}

	public function __get($property) {

		return $this->$property;
	}
}
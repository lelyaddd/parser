<?php

Interface iBaseController {

	//Обязательно должен быть реализован render для передачи переменных на представление.
	public function render($view, $data);
}

abstract Class baseController implements iBaseController {

	private $viewProvider;
	private $controller;
	private $template;

	public function index($params = null) {

	}

	//Метод передачи данных на представление
	public function render($view, $data, $template = true) {

		$reflect = new ReflectionClass(get_called_class());
		$this->controller = $reflect->getShortName();
		$viewPath = $_SERVER['DOCUMENT_ROOT'].'/views/'.$this->controller.'/'.$view.'.php';
		$this->viewProvider = [$viewPath, $data];

		//Если 3 параметром передавать false, то представление подключается без общего шаблона
		if($template) {

			$this->template = $_SERVER['DOCUMENT_ROOT'].'/template.php';
		}

		else {
			
			$this->template = $viewPath;
		}
		
	}
	
	public function __get($property) {

		return $this->$property;
	}

	//Убивает скрипт, если запрос был не через ajax
    protected function isAjax() {

        if($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') {

            die('Доступен только по AJAX');
        }
    }

    protected function redirect($url) {

    	header('Location: '.$url);
    }
}
<?php

namespace app\controllers;
use baseController;
use base;

Class installer extends baseController {

	public function index()
    {

        if ($_SERVER['REQUEST_URI'] != '/') {

            die('Приложение уже установлено');
        }

        $this->render('index', [], false);
    }

    public function check() {

	    $this->isAjax();
		$source = $_SERVER['DOCUMENT_ROOT'].'/configBlank.php';
		$dest = $_SERVER['DOCUMENT_ROOT'].'/core/config.php';

		if(!file_exists($dest)) {
			
			$configBlank = file_get_contents($source);
			//Значения ключей этого массива должны приходить с формы
			$dbOptions = ['Host' => $_POST['host'], 'User' => $_POST['user'], 'Password' => $_POST['password'], 'Name' => $_POST['name']];

			foreach ($dbOptions as $option => $value) {

                $configBlank = str_replace('BlankDb'.$option, $value, $configBlank);
			}

			$configFile = fopen($dest, 'w+');
			fwrite($configFile, $configBlank);
			fclose($configFile);
			require($dest);
			$db = base::loadDb($config['db']);
			$response = $db->conObject;
			if(is_object($response)) {

			    $this->nextStep($db);
			    $response = 'success';
			    $this->redirect('/');
            }

			else {
			    unlink($dest);
            }
		}

		$this->render('response', ['data' => $response], false);
	}

	private function nextStep($db) {

	    $db->exec('DROP TABLE IF EXISTS results');
	    $db->exec('CREATE TABLE results(id int AUTO_INCREMENT, url varchar(255), result text, count int, PRIMARY KEY (id))');
    }
}
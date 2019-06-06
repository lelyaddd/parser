<?php

namespace app\controllers;
use baseController;
use app\models\results;
use base;
use DOMDocument;

Class main extends baseController {

	public function index($params = null) {

		$this->render('index', []);
	}

	public function results($id = null) {

		$model = new results;

		if($id > 0) {

			$model->condition = ['column' => 'id', 'value' => $id];
			$results = $model->provide('select', 'url, result');
			$this->render('resultsDetail', ['results' => $results]);
		}

		else {

			$model->order = ['column' => 'id', 'type' => 'DESC'];
			$results = $model->provide('select', 'url, id, count');
			$this->render('results', ['results' => $results]);
		}
	}

}
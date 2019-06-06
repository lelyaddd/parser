<?php

Interface iBaseModel {

	//Обязательно должен быть реализован метод provide для того, чтобы обращаться из контроллера
	public function provide($operation, $data);
}

abstract Class baseModel implements iBaseModel {

	protected $table;
	protected $db;
	protected $columns;
	public $condition = [];
	public $limit = null;
	public $order = [];
	protected $column = '*';

	//Метод взаимодействия с классом БД
	public function provide($operation, $data) {

		$this->setTable();
		$this->loadDb();

		if($operation == 'insert') {

			$exec = $this->db->$operation($this->table, $data);
		}

		if($operation == 'select') {
			//В случае select в метод provide вторым параметром передается строка с именем столбца или столбцов через запятую
			$exec = $this->db->$operation($this->table, $this->condition, $this->limit, $this->order, $data);
		}

		return $exec;
	}

	private function setTable() {

		//Если свойство таблицы не заполнялось вручную, то получить его из имени класса модели
		if(!isset($this->table)) {

			$class = get_called_class();
			$reflect = new ReflectionClass($class);
			$this->table = $reflect->getShortName();
		}
	}

	private function loadDb() {

		$config = base::loadConfig('db');
		$this->db = base::loadDb($config);
	}

	private function getColumns() {

		$describe = 'DESCRIBE '.$this->table;
		$columns = $this->db->query($describe);

		foreach ($columns as $column) {
			
			$this->columns[] = $column->Field;
		}
	}
}
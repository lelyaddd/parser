<?php

class dbDriver {

	public $conObject;

	public function __construct($config) {

		$dbDriver = $config['dbDriver'];
		unset($config['dbDriver']);
		$this->$dbDriver($config);

        return $this->conObject;
	}


	private function pdoMySQL($config)
    {
        try {
            $this->conObject = new PDO('mysql:host=' . $config['dbHost'] . ';port=' . $config['dbName'] . ';dbname=' . $config['dbName'] . ';charset=' . $config['dbCharset'], $config['dbUser'], $config['dbPassword'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        } catch (PDOException $pe) {

            $this->conObject = $pe->getMessage();
        }
    }
//Метод формирования WHERE - если оно было задано, доступен только внутри класса
	private function formatCondition($condition) {

		$conditionString = '';

		if(isset($condition[0]['column'])) {

			foreach ($condition as $index => $conditionItem) {

				if(!isset($conditionItem['operator'])) {

					$conditionItem['operator'] = '=';
				}
				
				if($index == 0) {

					$conditionString = ' WHERE '.$conditionItem['column'].$conditionItem['operator'].'"'.$conditionItem['value'].'"';
				}

				else {

					if(!isset($conditionItem['separator'])) {

						$conditionItem['separator'] = 'AND';
					}

					$conditionString .= ' '.$conditionItem['separator'].' '.$conditionItem['column'].$conditionItem['operator'].'"'.$conditionItem['value'].'"';
				}			

			}
		}

		if(isset($condition['column'])) {

			if(!isset($condition['operator'])) {

				$condition['operator'] = '=';
			}

			$conditionString = ' WHERE '.$condition['column'].$condition['operator'].'"'.$condition['value'].'"';
		}

		return $conditionString;
	}

//Делаем query для чтения, который будет возвращать массив объектов, где объекты - строки таблицы, и exec для записи, возвращает количество затронутых строк
//Оставляем модификатор доступа public для реализации запросов, которые не будут предусмотрены в методах ниже
	public function query($sql) {

		$query = $this->conObject->query($sql);
		while ($result = $query->fetchObject()) {
			
			$resultArr[] = $result;
		}
		return $resultArr;
	}

	public function exec($sql) {

		$query = $this->conObject->exec($sql);

		return $query;
	}

//Основные типы запросов select, insert, update, delete, собираем нужный нам sql и дергаем $this->query()
	public function select($table, $condition = [ [ 'separator' => 'AND', 'column' => null, 'operator' => '=', 'value' => null ] ], $limit = null, $order = [ 'column' => null, 'type' => 'ASC' ], $column = '*') {

		$conditionString = $this->formatCondition($condition);

		$limitString = '';

		if(isset($limit)) {

			$limitString = ' LIMIT '.$limit;
		}

		$orderString = '';

		if(isset($order['column'])) {

			if(!isset($order['type'])) {

				$order['type'] = 'ASC';
			}

			$orderString = ' ORDER BY '.$order['column'].' '.$order['type'];
		}

		$sql = 'SELECT '.$column.' FROM '.$table.$conditionString.$orderString.$limitString;

		return $this->query($sql);
	}

	public function insert($table, $data) {

		foreach ($data as $column => $value) {
			
			$columns[] = $column;
			$values[] = '\''.$value.'\'';
		}

		$columnString = implode(',', $columns);
		$valueString = implode(',', $values);

		$sql = 'INSERT INTO '.$table.'('.$columnString.') VALUES ('.$valueString.')';

		return $this->exec($sql);
	}

	public function update($table, $data, $condition = [ [ 'separator' => 'AND', 'column' => null, 'operator' => '=', 'value' => null ] ]) {

		$conditionString = $this->formatCondition($condition);

		foreach ($data as $column => $value) {

			$dataSet[] = $column.'="'.$value.'"';
		}

		$dataString = implode(',', $dataSet);

		$sql = 'UPDATE '.$table.' SET '.$dataString.$conditionString;

		return $this->exec($sql);
	}

	 public function delete($table, $condition = [ [ 'separator' => 'AND', 'column' => null, 'operator' => '=', 'value' => null ] ]) {

	 	$conditionString = $this->formatCondition($condition);

	 	$sql = 'DELETE FROM '.$table.$conditionString;

	 	return $this->exec($sql);
	 }
}
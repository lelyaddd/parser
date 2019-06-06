<?php

namespace app\controllers;
use baseController;
use app\models\results;
use DOMDocument;
use base;

Class engine extends baseController {



	public function ajax($params) {

		$this->isAjax();

		header('Content-Type: application/json');

		//Настройки парсинга
		$config = base::loadConfig('parser');

		$results = new results;

		$paramsArr = explode('/', $params);

		$select = $paramsArr[0];
		unset($paramsArr[0]);

		if($select == 'text') {

			$pattern = $paramsArr[1];
			unset($paramsArr[1]);
		}

		//Область (тег) внутри которой ищем
		$tags = $config['searchArea'];

		//По дефолту поиск не чувствителен к регистру, но если в конфиге задействовать эту настройку, то он станет чувствительным
		$caseSensetive = 'i';

		if($config['caseSensetive']) {

			$caseSensetive = '';
		}

		$url = implode('/', $paramsArr);
		unset($paramsArr);

		$url = $this->punycode($url);

		$curl = $this->curl($url, ['lastUrl', 'output']);
		$output = $curl['output'];
		$lastUrl = $curl['lastUrl'];

		//Через DOM получаем ноды текущего тега и их количество
		$dom = new DOMDocument('1.0', 'UTF-8');
		$dom->loadHTML(mb_convert_encoding($output, 'HTML-ENTITIES', 'UTF-8'));
		$nodes = $dom->getElementsByTagName($tags[$select]);
		$tagsCount = $nodes->length;

		//Чтобы потом разбирать результаты первым элементом добавляется вид парсинга, который был задействован
		$result[] = $select;

		$attr = 'src';

		if($select == 'links') {

			$attr = 'href';
		}

		for ($i=0; $i < $tagsCount; $i++) { 

			if($select == 'text') {

				$tagValue = $nodes->item($i)->textContent;

				if(preg_match('/'.$pattern.'/'.$caseSensetive.'u', $tagValue)) {

					$result[] = $tagValue;
				}
			}


			if($select == 'links' || $select == 'images') {

				$tagValue = $nodes->item($i)->getAttribute($attr);
				$baseUri = parse_url($lastUrl);
				$currentUrl = parse_url($tagValue);

				//Убираем // в начале ссылки
				$tagValue = preg_replace('/^\/\//', $baseUri['scheme'].'://', $tagValue);
				
				//Если не является урлом, то ссылка относительная
				if(is_null($currentUrl['host'])) {

					$tagValue = trim($tagValue, '/');
					$downloadUri = $baseUri['scheme'].'://'.$baseUri['host'].'/'.$tagValue;

					if (preg_match('/^(tel|mailto):/', $tagValue)) {
						
						unset($downloadUri);
					}
				}

				//Если ссылка указана абсолютно на текущий домен
				elseif($currentUrl['host'] == $baseUri['host']) {

					$downloadUri = $tagValue;
				}

				//В ином случае это абсолютная ссылка на удаленный ресурс и нужно сделать запрос, если данная проверка прописана в конфиге
				else {


					if($config['checkExistingRemoteElements']) {

						$downloadUri = $this->curl($tagValue, 'lastUrl');
					}

					else {

						$downloadUri = $tagValue;
					}
				}

				
				if($select == 'images') {

					//Технически svg не картинка, поэтому размер не проверить
					if(!preg_match('/\.svg$/iu', $downloadUri)) {

						$imageSize = getimagesize($downloadUri);
						
						//Убираем все, что не видим
						if($imageSize[0] <= 1 || $imageSize[1] <=1) {

							unset($downloadUri);
						}
					}
				}
				

				if(!empty($downloadUri)) {

					$result[] = urldecode($downloadUri);
				}
			}
		}

		if(isset($result)) {
			
			//Если задана настройка только уникальных результатов, то убираем повторы из результирующего массива
			if($config['unique']) {

				$result = array_unique($result);
			}
			//Минус 1 из-за добавленного в начало массива вида парсинга
			$count = count($result) - 1;
			//Для ajax будем отдавать json, в базу MySQL писать сериализованную строку, а не json, дабы не ужесточать требования к окружению, так как старые версии MySQL не поддерживают json
			$resultJson = $result;
			unset($resultJson[0]);
			$resultJson = json_encode($resultJson, JSON_UNESCAPED_UNICODE);

			$result = serialize($result);

			//Хорошей идей было бы сделать составной индекс result_url и прикрепить к нему UNIQUE KEY, но к сожалению result слишком длинный
			//А дублей в базе избежать надо, поэтому делаем SELECT и проверяем, что результаты не идентичны
			$results->condition = ['column' => 'url', 'value' => $lastUrl];
			$double = $results->provide('select', 'result')[0]->result;

			if($double != $result && $count > 0) {

				$data = ['url' => $lastUrl, 'result' => $result, 'count' => $count];
				$results->provide('insert', $data);
			}

			$this->render('json', ['result' => $resultJson], false);
		}
	}


	public function checkurl($url) {

		$this->isAjax();

		$url = $this->punycode($url);

		$curl = $this->curl($url, ['lastUrl', 'status']);

		$checkedUrl = '';
		if($curl['status'] == 200) {

			$checkedUrl = $curl['lastUrl'];
		}

		curl_close($curl);

		$this->render('check', ['url' => $checkedUrl], false);
		
	}

	private function punycode($url) {

		if(!preg_match('/^https?:\/\//', $url)) {

			$url = 'http://'.$url;
		}

		$parsedUrl = parse_url($url);

		if(preg_match('/^[а-яА-Я]/iu', $parsedUrl['host'])) {

			$url = idn_to_ascii($parsedUrl['host']).$parsedUrl['path'];
		}

		return $url;
	}

	//Все сеансы curl из этого метода
	//Если вторым параметром указано имя переменной, то ее значение отдается методом, если получить надо несколько переменных, то указываем их как массив и возвращается массив
	private function curl($url, $returnOutput = false) {

		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_TIMEOUT_MS, 3000);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:66.0) Gecko/20100101 Firefox/66.0');
		$output = curl_exec($curl);
		$lastUrl = curl_getinfo($curl, CURLINFO_EFFECTIVE_URL);
		$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl);

		if(is_string($returnOutput)) {

			return $$returnOutput;
		}

		if(is_array($returnOutput)) {

			foreach ($returnOutput as $var) {
				
				$returnOutputArr[$var] = $$var;
			}

			return $returnOutputArr;
		}
	}
}
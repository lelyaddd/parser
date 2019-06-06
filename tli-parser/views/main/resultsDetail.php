<div class="search-result">
<?php
$results = $results[0];
$result = unserialize($results->result);
$searchType = $result[0];
unset($result[0]);

$searchCases = [

	'text' => ['tag' => 'p'],
	'links' => ['tag' => 'a', 'source' => 'href', 'target' => 'target=\'blank\''],
	'images' => ['tag' => 'img', 'source' => 'src'],
];

$source = '';

echo 'Проверяемый URL-адрес: '.$results->url.'<br>';
echo 'Результаты: <br><br>';

foreach ($result as $resultItem) {
	
	$item = $resultItem;

	if(isset($searchCases[$searchType]['source'])) {

		$source = ' '.$searchCases[$searchType]['source'].'="'.$resultItem.'" ';
	}

	if($searchType == 'images') {

		$item = '';
	}

    if(isset($searchCases[$searchType]['target'])) {

        $target = $searchCases[$searchType]['target'];
    }

	echo '<'.$searchCases[$searchType]['tag'].$source.$target.'>'.$item.'</'.$searchCases[$searchType]['tag'].'><br>';
}
?>
</div>

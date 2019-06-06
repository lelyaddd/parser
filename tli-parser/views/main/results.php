<div class="search-result">
<?php
foreach ($results as $resultRow) {

	echo 'Проверяемый URL-адрес: <a href="/main/results/'.$resultRow->id.'">'.$resultRow->url.'</a><br>';
	echo 'Количество результатов: '.$resultRow->count;
	echo '<br><br><br>';
}
//echo json_last_error();
?>
</div>



<?php

$viewProvider = $controllerObject->viewProvider;
//echo '<pre>';
//var_dump($viewProvider);die();

$viewPath = $viewProvider[0];

foreach ($viewProvider[1] as $variable => $value) {
	
	$$variable = $value;
}
unset($viewProvider);

require_once ($controllerObject->template);
//require ($viewPath);
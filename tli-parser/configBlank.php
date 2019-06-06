<?php

$config = [

	'db' => [

		'dbDriver' => 'pdoMysql',
		'dbHost' => 'BlankDbHost',
		'dbUser' => 'BlankDbUser',
		'dbPassword' => 'BlankDbPassword',
		'dbName' => 'BlankDbName',
		'dbCharset' => 'utf8',
		'dbPort' => 3306,
	],

	'uri' => [

		'/' => '/main',
		'/results' => '/main/results',
	],

	'parser' => [

			'unique' => true,
			'caseSensetive' => false,
			'checkExistingRemoteElements' => false,
			'searchArea' => [

				'text' => 'p',
				'links' => 'a',
				'images' => 'img',
			],
	],

];
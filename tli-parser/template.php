<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Парсер</title>
    <link rel="stylesheet" href="/frontend/css/style.css">
</head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="/frontend/js/mobile.js"></script>
<script src="/frontend/js/form.js"></script>
<body>
<div class="loading"></div>

<header>
    <a href="#" class="burger">
        <span></span>
    </a>

    <div class="menu">
        <div class="substrate">
            <ul>
                <li><a  href="/">Главная</a></li>
                <li><a href="/results">Результаты</a></li>
            </ul>
        </div>
    </div>
</header>
<div class="container"></div>

<?php

require_once ($viewPath);

?>
</body>
</html>
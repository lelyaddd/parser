# parser
tli-parser
Text links images parser Парсер текста, ссылок и картинок

Установка:
Необходимо загрузить файлы парсера на хостинг и обратиться к корню сайта, после чего запустится установщик, который предложит заполнить данные для подключения к БД. После успешной установки парсер будет готов к работе.

Требования:
Apache, MySQL, php5.4 и выше, совместим с php7.

Настройки:
Настройки парсера выполняются в файле core/config.php

Настройки представлены в виде интуитивно понятного ассоциативного массива с разделениям по секциям.

В секции db представлены настройки подключения к базе, которые задаются установщиком.

В секции uri указываются сокращенные варианты uri: в качестве ключа - сокращенный uri, в качестве значения - реальный.

В секции parser 3 параметра и вложенная секция searchArea:

unique - только уникальные результаты поиска, по умолчанию включена

caseSensetive - чувствительность к регистру, по умолчанию выключена

checkExistingRemoteElements - дополнительная проверка доступности удаленной ссылки или изображения, по умолчанию выключена

Секция searchArea отвечает за то, в каких тегах будет происходить поиск соответствующих элементов, например, если ключу 'text' задать значение div, вместо p, то поиск текста будет осуществляться в блоках div.

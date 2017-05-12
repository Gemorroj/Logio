# Logio log parser

[![Build Status](https://secure.travis-ci.org/Gemorroj/Logio.png?branch=master)](https://travis-ci.org/Gemorroj/Logio)

### Парсер error логов.

Из коробки поддерживаются:
 - apache
 - nginx
 - php
 - php-fpm
 - mysql

Добавление нового парсера заключается в написании в конфигурации регулярных выражений для нового парсера.

### Example: 
```php
<?php

$config = Logio\Config::createFromYaml('/path_to_config/config.yml');
$logio = new Logio\Logio($config);

$parser = $logio->run('php');
foreach ($parser as $data) {
    print_r($data);
    /*
    Array
    (
        [date] => DateTime Object
            (
                [date] => 2017-02-22 06:11:51.000000
                [timezone_type] => 3
                [timezone] => Europe/Moscow
            )
    
        [type] => PHP Warning
        [message] => session_start(): open(/var/lib/php/session/sess_2u61qee1kg9p7rr69mgka5ddf4, O_RDWR) failed: Permission denied (13)
        [file] => /var/www/forum/register.php
        [line] => 2
    )
     */
}
```

# Logio

[![Continuous Integration](https://github.com/Gemorroj/Logio/workflows/Continuous%20Integration/badge.svg)](https://github.com/Gemorroj/Logio/actions?query=workflow%3A%22Continuous+Integration%22)

### Log parser

Из коробки поддерживаются:
 - apache
 - nginx
 - php
 - php-fpm
 - mysql

Добавление нового парсера заключается в написании в конфигурации регулярных выражений для нового парсера.


### Installation:
```bash
composer require gemorroj/logio
```


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



### TODO
добавить в конфиги новый параметр который, говорил бы о добавлении данных в массив до того момента. пока не сработает регулярка.
Например:
```yml
mysql:
    path: 'tests/fixtures/mysql.log'
    format:
        date: '/^([0-9A-Z\.\-:]+) /'
        thread: '/^(?:[0-9A-Z\.\-:]+) ([0-9]+) \[/'
        type: '/(?:[0-9]+) \[(.+)\] /'
        message: '/ (?:[0-9]+ \[.+\] ){0,1}(.+)+/'
    cast:
        date: '\DateTime'
    append:
        message: '/todo/'
```

```txt
2016-03-01T22:22:38.769531Z 0 [Note] InnoDB: Progress in MB:
 100 200
2016-03-01T22:22:39.884951Z 0 [Note] InnoDB: Renaming log file ./ib_logfile101 to ./ib_logfile0
```

Должно получиться 2 элемента массива, " 100 200" должны присоединиться к message предыдущей строки.

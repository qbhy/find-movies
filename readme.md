# find-movies

## 安装
```
$ composer require 96qbhy/find-movies
```

## 使用
```php
require 'vendor/autoload.php';

$results = Qbhy\FindMovies\Finder::find('一拳超人', 5);

print_r($results);
```
> 或者直接运行 `example.php` , 浏览器或者 `cli` 都可以。

96qbhy@gmaill.com
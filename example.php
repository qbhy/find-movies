<?php

require 'vendor/autoload.php';

try {
    $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '神奇女侠';
    dump(Qbhy\FindMovies\Finder::find($keyword));
} catch (Exception $exception) {
    dump($exception);
}




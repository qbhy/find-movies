<?php

namespace Qbhy\FindMovies;

use GuzzleHttp\Client;

class Finder
{

    /**
     * @var Pipe
     */
    static $pipe;


    public static function init()
    {
        static::$pipe = new Pipe(__DIR__ . '/bin/findMovies');
    }

    public static function find($keyword, $limit = 5)
    {
        if (!(static::$pipe instanceof Pipe)) {
            static::init();
        }
        return static::$pipe->execute(compact('keyword', 'limit'));
    }
}

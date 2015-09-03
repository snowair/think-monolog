<?php
/**
 * User: Administrator
 * Date: 2015/9/2
 * Time: 15:12
 */
namespace Snowair\Think;

use Monolog\Logger as Mloger;

class Logger
{
    /** @var  Mloger */
    static protected $logger;

    public  function app_init( &$param )
    {
        if (!self::$logger instanceof Mloger) {
            self::$logger = new Mloger('think');
        }
    }

    static public function getLogger()
    {
        return self::$logger;
    }

}
<?php
/**
 * User: Administrator
 * Date: 2015/9/2
 * Time: 15:12
 */
namespace Snowair\Think;

use Monolog\Handler\HandlerInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Logger as Mlogger;

/**
 * @method Mlogger pushHandler( HandlerInterface $handler) Pushes a handler on to the stack.
 * @method Mlogger pushProcessor( callable $callback)
 * @method Mlogger setHandlers(array $handlers)  Set handlers, replacing all existing ones. If a map is passed, keys will be ignored.
 * @method HandlerInterface popHandler() Pops a handler from the stack
 * @method HandlerInterface[] getHandlers()
 * @method callable popProcessor()
 * @method callable[] getProcessors()
 *
 * @method bool debug(string $message, array $context = array())
 * @method bool info(string $message, array $context = array())
 * @method bool notice(string $message, array $context = array())
 * @method bool warn(string $message, array $context = array())
 * @method bool warning(string $message, array $context = array())
 * @method bool err(string $message, array $context = array())
 * @method bool error(string $message, array $context = array())
 * @method bool crit(string $message, array $context = array())
 * @method bool critical(string $message, array $context = array())
 * @method bool alert(string $message, array $context = array())
 * @method bool emerg(string $message, array $context = array())
 * @method bool emergency(string $message, array $context = array())
 *
 * @method bool addRecord(string $level, $message, array $context = array())
 * @method bool addDebug(string $message, array $context = array())
 * @method bool addInfo(string $message, array $context = array())
 * @method bool addNotice(string $message, array $context = array())
 * @method bool addWarning(string $message, array $context = array())
 * @method bool addError(string $message, array $context = array())
 * @method bool addCritical(string $message, array $context = array())
 * @method bool addAlert(string $message, array $context = array())
 * @method bool addEmergency(string $message, array $context = array())
 *
 */
class Logger
{
    const DEBUG = 100;
    const INFO = 200;
    const NOTICE = 250;
    const WARNING = 300;
    const ERROR = 400;
    const CRITICAL = 500;
    const ALERT = 550;
    const EMERGENCY = 600;

    /** @var  Mlogger */
    static protected $logger;

    public function app_begin( &$param=[] )
    {
        C('SHOW_PAGE_TRACE',false); // 关闭, 以将日志记录到 \Think\Log::$log
        if (!self::$logger instanceof Mlogger) {
            self::$logger = new Mlogger('think');
        }
    }

    static public function getLogger()
    {
        if (!self::$logger instanceof Mlogger) {
            self::$logger = new Mlogger('think');
            self::$logger->pushHandler(new StreamHandler( C('LOG_PATH'), Logger::DEBUG)); // 文件
        }
        return self::$logger;
    }


    static public function __callStatic( $method, $paramters )
    {
        if (method_exists( self::$logger, $method )) {
            return call_user_func_array(array(self::$logger,$method), $paramters);
        }
        if (method_exists( 'Mlogger',$method )) {
            return forward_static_call_array(array('Mlogger',$method), $paramters);
        }else{
            throw new \RuntimeException('方法不存在');
        }
    }

}
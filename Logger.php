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
use Monolog\Processor\WebProcessor;

/**
 * @method static Mlogger pushHandler( HandlerInterface $handler) Pushes a handler on to the stack.
 * @method static Mlogger pushProcessor( callable $callback)
 * @method static Mlogger setHandlers(array $handlers)  Set handlers, replacing all existing ones. If a map is passed, keys will be ignored.
 * @method static HandlerInterface popHandler() Pops a handler from the stack
 * @method static HandlerInterface[] getHandlers()
 * @method static callable popProcessor()
 * @method static callable[] getProcessors()
 *
 * @method static bool debug(string $message, array $context = array())
 * @method static bool info(string $message, array $context = array())
 * @method static bool notice(string $message, array $context = array())
 * @method static bool warn(string $message, array $context = array())
 * @method static bool warning(string $message, array $context = array())
 * @method static bool err(string $message, array $context = array())
 * @method static bool error(string $message, array $context = array())
 * @method static bool crit(string $message, array $context = array())
 * @method static bool critical(string $message, array $context = array())
 * @method static bool alert(string $message, array $context = array())
 * @method static bool emerg(string $message, array $context = array())
 * @method static bool emergency(string $message, array $context = array())
 *
 * @method static bool addRecord(string $level, $message, array $context = array())
 * @method static bool addDebug(string $message, array $context = array())
 * @method static bool addInfo(string $message, array $context = array())
 * @method static bool addNotice(string $message, array $context = array())
 * @method static bool addWarning(string $message, array $context = array())
 * @method static bool addError(string $message, array $context = array())
 * @method static bool addCritical(string $message, array $context = array())
 * @method static bool addAlert(string $message, array $context = array())
 * @method static bool addEmergency(string $message, array $context = array())
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
            self::$logger = new Mlogger('Monolog');
            $handler = new StreamHandler( C('LOG_PATH').date('y_m_d').'.log', Logger::DEBUG);
            $handler->getFormatter()->allowInlineLineBreaks();
            $handler->getFormatter()->ignoreEmptyContextAndExtra();
            self::$logger->pushProcessor( new WebProcessor() );
            self::$logger->pushHandler($handler); // 文件
        }
    }

    static public function getLogger()
    {
        if (!self::$logger instanceof Mlogger) {
            self::$logger = new Mlogger('Monolog');
            $handler = new StreamHandler( C('LOG_PATH').date('y_m_d').'.log', Logger::DEBUG);
            $handler->getFormatter()->allowInlineLineBreaks();
            $handler->getFormatter()->ignoreEmptyContextAndExtra();
            self::$logger->pushProcessor( new WebProcessor() );
            self::$logger->pushHandler($handler); // 文件
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
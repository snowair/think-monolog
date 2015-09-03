<?php
/**
 * User: Administrator
 * Date: 2015/9/2
 * Time: 16:40
 */
namespace Think\Log\Driver;

use Snowair\Think\Logger;
use Monolog\Logger as Mlogger;
use Think\Log;

class Monolog
{

    /**
     * 日志写入接口
     * @access public
     * @param string $log 日志信息
     * @param string $destination  写入目标
     * @return void
     */
    public function write($log,$destination='') {
        $logger = Logger::getLogger();
        if ($logger->getHandlers()) {
            if (false !== strpos( $log, 'INFO: [ app_begin ] --START--' )) {
                $logger->addRecord(Mlogger::EMERGENCY,"\r\n".$log);
            }else{
                $level = strstr($log,':',true);
                $msg   = ltrim(strstr($log,':'),':');
                switch ($level){
                    case Log::ERR:
                        $level=Mlogger::ERROR;
                        break;
                    case Log::EMERG:
                        $level=Mlogger::EMERGENCY;
                        break;
                    case Log::INFO:
                        $level=Mlogger::INFO;
                        break;
                    case Log::WARN:
                        $level=Mlogger::WARNING;
                        break;
                    case Log::NOTICE:
                        $level=Mlogger::NOTICE;
                        break;
                    case Log::ALERT:
                        $level=Mlogger::ALERT;
                        break;
                    case Log::CRIT:
                        $level=Mlogger::CRITICAL;
                        break;
                    default:
                        $level=Mlogger::DEBUG;
                }
                $logger->addRecord($level,$msg);
            }
        }
    }
}
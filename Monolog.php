<?php
/**
 * User: Administrator
 * Date: 2015/9/2
 * Time: 16:40
 */
namespace Think\Log\Driver;

use Snowair\Think\Logger;
use Monolog\Logger as Mloger;
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
            $line = count(array_filter(explode("\r\n",$log)));
            if ($line>1) {
                $logger->addDebug($log);
            }else{
                $level = strstr($log,':',true);
                $msg   = ltrim(strstr($log,':'),':');
                switch ($level){
                    case Log::ERR:
                        $level=Mloger::ERROR;
                        break;
                    case Log::DEBUG:
                        $level=Mloger::DEBUG;
                        break;
                    case Log::EMERG:
                        $level=Mloger::EMERGENCY;
                        break;
                    case Log::INFO:
                        $level=Mloger::INFO;
                        break;
                    case Log::WARN:
                        $level=Mloger::WARNING;
                        break;
                    case Log::NOTICE:
                        $level=Mloger::NOTICE;
                        break;
                    case Log::ALERT:
                        $level=Mloger::ALERT;
                        break;
                    case Log::CRIT:
                        $level=Mloger::CRITICAL;
                        break;
                }
                $logger->addRecord($level,$msg);
            }
        }
    }
}
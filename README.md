介绍
============

ThinkPHP 3.2 集成 monolog

monolog简介
----------

是 Laravel,Symfony,Silex 默认集成的日志库, 同时大量其他框架提供了集成扩展. 

它最流行的 php log库, 自带超多handler, 长期维护, 稳定更新. 

它支持以各种方式记录日志: 记录到文件,mail,nosql,mail,irc,firephp,elasticsearch服务器....

* monolog: <https://github.com/Seldaek/monolog>
* monolog docs: <https://github.com/Seldaek/monolog/tree/master/doc>

注意: 

> 由于 `SHOW_PAGE_TRACE` 设为 `true` 以后, TP不再将trace数据记录到log.

> 也就是说, 在不修改TP源码的情况下想用monolog收集trace数据, TRACE BAR 和 monolog 你只能二选一.
 
> 而本人扩展框架的原则是, 为了不影响升级框架, 对框架的功能扩展绝不修改源码.

> 因此, 集成monolog后, 为了能收集到trace数据, 在内部已将 `SHOW_PAGE_TRACE` 设为了 `false`.


安装
------

### step1

```
composer requrie snowair/think-monolog
```

### step2

在`Common/Conf/tags.php` 中注册一个`app_begin`行为:

```
return array(
    'app_begin'=>array('Snowair\Think\Logger'),
);
```

### step3

在 `Common/Conf/config.php` 中把 `LOG_TYPE` 配置设为 `monolog`

至此, ThinkPHP默认产生的log, 以及您手动使用 `trace`函数, `Think\Log::write()` 记录的日志都将由monolog的StreamHandler记录(日志级别:debug), 无需额外配置.


### step4(可选)

> 既然我们用monolog, 肯定是为了使用其提供的丰富的 handlers. 而不是为了仅仅在文件中记录日志.

自己建一个行为类, 在这个行为类中完成 monolog 实例的 handlers 和 processors 的添加.

#### 示例: 

创建 `Common/Behavior/MonologBehavior.class.php` :

```
<?php
namespace Common\Behavior;

use Think\Behavior;
use Snowair\Think\Logger;
use Monolog\Handler\MongoDBHandler;

class MonologBehavior extends Behavior
{

    public function run( &$params )
    {
        $logger = Logger::getLogger();
        
        /**
         think-monolog 默认注册的StreamHandler的日志级别为 debug. 
         如果你想改变它的级别或者不想使用StreamHandler, 就需要取出这个handler.
         假设,我们现在的在生产环境下的日志需求是这样:
            1. 只想在本地文件中记录Error以上级别的日志, 供常规检查
            2. info 以上的日志向发到外部的 MongoDb 数据库中,供日志监控和分析
        */
        
        
        $stream_handler = $logger->popHandler();
        $stream_handler = $logger->setLevel(Logger::ERROR);
        $logger->pushHandler($stream_handler); // 注册修改后的stream_handler
        
        $mongodb = new MongoDBHandler(new \Mongo("mongodb://***.***.***.***:27017"), "logs", "prod");
        $logger->pushHandler($mongodb); // 文件
    }
}
```

在`Common/Conf/tags.php` 再增加一个`app_begin`行为:

```
return array(
    'app_begin' =>array(
        'Snowair\Think\Logger',
        'Common\Behavior\MonologBehavior'
        ),
);
```

现在, 你可以像过去一样使用TP的trace函数记录日志, 但所有的trace数据是以**一条日志**的形式记录. 

如果你希望单独记录一些日志, 需要使用 monolog:

```
\Snowair\Think\Logger::debug('这是一条debug日志');
\Snowair\Think\Logger::info('这是一条info日志');
\Snowair\Think\Logger::warn('这是一条warn日志');
\Snowair\Think\Logger::error('这是一条error日志');
```

注意: 

1. 行为类的注册顺序不能错.
2. handler的日志级别设置仅对直接通过 monolog 添加的日志有效. 无论handler的日志级别如何, TP的trace日志一定会被无条件记录.



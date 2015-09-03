介绍
============

ThinkPHP 3.2 集成 monolog

* monolog: <https://github.com/Seldaek/monolog>
* monolog docs: <https://github.com/Seldaek/monolog/tree/master/doc>

安装
------

### step1

```
composer requrie snowair/think-monolog
```

### step2

在项目的Conf/tags.php中注册一个行为:

```
return array(
    'app_init'=>array('Snowair\Think\Logger'),
);
```

### step3

在 config.php 中把 `LOG_TYPE` 配置设为 `monolog`

### step4


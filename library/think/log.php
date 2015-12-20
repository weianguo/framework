<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2015 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

namespace think;

class Log
{
    const LOG   = 'log';
    const ERROR = 'error';
    const INFO  = 'info';
    const SQL   = 'sql';
    const WARN  = 'warn';
    const ALERT = 'alert';

    // 日志信息
    protected static $log = [];
    // 日志类型
    protected static $type = ['log', 'error', 'info', 'sql', 'warn', 'alert'];
    // 日志写入驱动
    protected static $driver = null;

    // 日志初始化
    public static function init($config = [])
    {
        $type  = isset($config['type']) ? $config['type'] : 'File';
        $class = '\\think\\log\\driver\\' . ucwords($type);
        unset($config['type']);
        self::$driver = new $class($config);
    }

    /**
     * 获取全部日志信息
     * @return array
     */
    public static function getLog()
    {
        return self::$log;
    }

    /**
     * 记录调试信息
     * @param string $msg 调试信息
     * @param string $type 信息类型
     * @return void
     */
    public static function record($msg, $type = 'log')
    {
        self::$log[] = ['type' => $type, 'msg' => $msg];
    }

    /**
     * 保存调试信息
     * @return void
     */
    public static function save()
    {
        self::$driver && self::$driver->save(self::$log);
    }

    /**
     * 写入调试信息
     * @return void
     */
    public static function write($msg, $type, $destination)
    {
        $log[] = ['type' => $type, 'msg' => $msg];
        self::$driver && self::$driver->save($log);
    }

    // 静态调用
    public static function __callStatic($method, $args)
    {
        if (in_array($method, self::$type)) {
            array_push($args, $method);
            return call_user_func_array('\think\Log::record', $args);
        }
    }

}
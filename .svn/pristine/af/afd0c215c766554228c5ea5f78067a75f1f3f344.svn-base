<?php
namespace app\common\helper;

class Redisdata
{
    /**
     * REDIS实例
     * @var Redis
     */
    static $_instance;
    
    /**
     * 返回实例
     */
    static function get_instance($host,$port)
    {
        if(is_object(self::$_instance))
        {
            return self::$_instance;
        }else{

            self::$_instance = new \Redis();
            self::$_instance->connect($host,$port);
            return self::$_instance;
        }
    }
    
    /**
     * REDIS 加密数据
     * @param mixed $data
     * @return string
     */
    static function encode($data)
    {
        return serialize($data);
    }
    
    static function decode($data)
    {
        return unserialize($data);
    }

}
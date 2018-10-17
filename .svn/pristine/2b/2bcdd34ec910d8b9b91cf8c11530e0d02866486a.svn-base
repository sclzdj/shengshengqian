<?php
namespace app\api\model;
use think\Model;
use think\Db;
use think\Cache;

/**
 * 插件公共模型
 * @package app\admin\model
 */
class UserToken extends Model
{
    const TOKEN_CACHE_PREFIX = 'TOKEN_CACHE';
    /**
     * 生成CACHEID
     * @param string $token
     */
    public static function getFormatCacheId($token)
    {
        return sprintf("%s-%s",self::TOKEN_CACHE_PREFIX,trim($token));
    }
    
    /**
     * 获取用户ID,根据token, $device_number为空则不限制设备，非空限制必须为统一设备
     * @param string $token
     * @param string $device_number
     */
    public static function getUserId($token,$device_number = '')
    {
        $token = trim($token);
        $device_number = trim($device_number);
        //1.先检查是否存在缓存数据
        $row = Cache::get(self::getFormatCacheId($token));
        if($row === false)
        {
            $row = Db::name("users_token")->where("token = ?",[$token])->find();
            if($row)
            {
                //设置缓存
                Cache::set(self::getFormatCacheId($token),json_encode($row));
            }
        }else{
            $row = json_decode($row,true);
        }
        if($row)
        {
            if($device_number && $device_number !== $row['device_number'])
            {
                return 0;//device_number 不同
            }else if(time() > $row['exptime'] && $row['exptime'] > 0){
                return 0;//过期
            }else{
                return $row['user_id'];
            }
        }else{
            return 0;
        }
    }
    
    public static function getUserRow($token,$device_number = '')
    {
        $token = trim($token);
        $device_number = trim($device_number);
        //1.先检查是否存在缓存数据
        $row = Cache::get(self::getFormatCacheId($token));
        if($row === false)
        {
            $row = Db::name("users_token")->where("token = ?",[$token])->find();
            if($row)
            {
                //设置缓存
                Cache::set(self::getFormatCacheId($token),json_encode($row));
            }
        }else{
            $row = json_decode($row,true);
        }
        if($row)
        {
            if($device_number && $device_number !== $row['device_number'])
            {
                return 0;//device_number 不同
            }else if(time() > $row['exptime'] && $row['exptime'] > 0){
                return 0;//过期
            }else{
                return $row['user_id'];
            }
        }else{
            return 0;
        }
    }
    /**
     * 生成token,
     * @param int $user_id 用户ID
     * @param string $device_number 设备编号
     * @param bool $is_kickOtherToken 是否剔除其他在线的TOKEN
     * @param number $exp TOKEN过期时间,单位秒
     * @return false|string 生成成功返回token，失败返回false
     */
    public static function makeToken($user_id,$device_number = '',$is_kickOtherToken = true,$exp = 31536000)
    {
        $user_id = intval($user_id);
        $exptime = time()+$exp;
        if($is_kickOtherToken)
        {
            //剔除其他token
            Db::name("users_token")->where("user_id = ?",[$user_id])->delete();
        }
        $uuid = self::uuid();
        $data = [
            'user_id'   =>  $user_id,
            'token'     =>  $uuid,
            'exptime'   =>  $exptime,
            'device_number' =>  $device_number
        ];
        if(Db::name("users_token")->insert($data)){
            //写入缓存
            Cache::set(self::getFormatCacheId($uuid),json_encode($data));
            return $uuid;
        }else{
            return false;
        }
    }
    
    /**
     * Generates an UUID
     *
     * @author Anis uddin Ahmad
     * @param
     *            string an optional prefix
     * @return string the formatted uuid
     */
    static function uuid($prefix = '')
    {
        $chars = md5(uniqid(mt_rand(), true));
        $uuid = substr($chars, 0, 8) . '-';
        $uuid .= substr($chars, 8, 4) . '-';
        $uuid .= substr($chars, 12, 4) . '-';
        $uuid .= substr($chars, 16, 4) . '-';
        $uuid .= substr($chars, 20, 12);
        return $prefix . $uuid;
    }
}

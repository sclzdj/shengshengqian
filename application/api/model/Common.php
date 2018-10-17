<?php
namespace app\api\model;
use think\Model;
use think\Db;

/**
 * 插件公共模型
 * @package app\admin\model
 */
class Common extends Model
{

   //通过手机查询用信息
   static function getUserInfoName($userName){
       return Db::name('user_list')->where('username',$userName)->find();
   }

}

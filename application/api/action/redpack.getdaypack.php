<?php
// +----------------------------------------------------------------------
// | TPPHP框架 [ DolphinPHP ]
// +----------------------------------------------------------------------
// | 版权所有 2016~2017 成都锐萌软件开发有限公司 [ http://www.ruimeng898.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://www.ruimeng898.com
// +----------------------------------------------------------------------
// | 开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------

namespace app\api\controller;

use app\common\helper\Redisdata;
use think\Db;;
use app\admin\model\Config;
/**
 * API action控制器
 * @package app\api\controller
 */
class redpackgetdaypack extends Baseaction
{
    function run()
    {
      //输入你的代码 
      
      //获取每日红包数据？
      $day = date('Ymd');
      //1.获取当日缓存ID，如果有数据 返回，没有数据 查询数据库并按算法 加入到缓存数据中 并且返回
      //总额调整到55元,180个，每天5个，30次机会
      
      $r = $this->isAppLogin(true);
      if($r->isSuccess())
      {
          $uid = $this->getUserId();
          $redTotal = Config::getConfig('redpack_daynum');
          $redTotal = intval($redTotal);
          $redHit = Config::getConfig('redpack_hitnum');
          $redHit = intval($redHit);
          $redpack_cacheversion = Config::getConfig('redpack_cacheversion');
          $cacheId = sprintf("%d-%s-%d/%d-%d",$uid,$day,$redHit,$redTotal,$redpack_cacheversion);//20170526-5/30-红包版本号
          $cacheId = md5($cacheId);
          
          $helper = new Redisdata();
          $host = trim(Config::getConfig('redis_host'));
          $port = intval(Config::getConfig('redis_port'));
          $redis = $helper->get_instance($host,$port);
          $data = $redis->get($cacheId);
          if($data === false)
          {
              
              //没有缓存 第一次请求，重新计算
              //1.查询用户当日已经领取的红包量
              $allowGet = Db::name("user_redpackage_log")->where("user_id = ? AND day = ?",[$uid,$day])->count("id");
              if($allowGet >= $redHit)
              {
                  //超出今日可领取范围
                  $this->response([], 201, '对不起,您已经使用完今日的抽红包次数,请明日再来!');
              }else{
                  
                  $diffNum = $redHit - $allowGet;
                  $where = [
                      'user_id'=>$uid,
                      'get_status'=>0,
                      'app_pop'=>0,
                  ];
                  $row = Db::name("user_redpackage")->where($where)->order("weight DESC")->field("id,red_balance,red_title,red_remark,pic")->limit($diffNum)->select();
                  if(!$row)
                  {
                      $this->response([], 202, '非常抱歉,您目前没有可用红包了!');
                  }else{
                      foreach ($row as &$v)
                      {
                          $v['red_balance'] = sprintf("%.2f",$v['red_balance']/100);
                          $v['pic'] = api_get_file_path($v['pic']);
                      }
                      $row = toHashmap($row, 'id');
                      $row = array_pad($row,$redTotal,'');
                      shuffle($row);
                      //保存进缓存
                      $redis->set($cacheId,$helper->encode($row));
                      $this->response($row, 200, '');
                  }
              }
          }else{
              $data = $helper->decode($data);
              $this->response($data, 200, '');
          }
          
      }else{
          return $this->response([], $this->_notLoginCode, '');
      }
    }
}
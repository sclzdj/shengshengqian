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

use think\Db;
/**
 * API action控制器
 * @package app\api\controller
 */
class tbhelperuppayorder extends Baseaction
{
    function run()
    {
        file_log(print_r($_REQUEST,true),__CLASS__);
      //输入你的代码
      $this->checkNeedParam(['orderid'=>'上传淘宝订单号']);
      $r = $this->isAppLogin(false);
      if(!$r->isSuccess())
      {
          $this->response([], $this->_notLoginCode, '');
      }else{
          $uid = $this->getUserId();
          $orderid = $this->getRequest('orderid','');
          if(!$orderid)
          {
              $this->response([], 201, '请上传正确的淘宝订单号');
          }else{
              $order = Db::name("user_orders")->where("order_id = ?",[$orderid])->find();
              if($order)
              {
                  $this->response([], 202, '已提交过数据');
              }else{
                  $data = [
                      'order_id'=>$orderid,
                      'created'=>time(),
                      'user_id'=>$uid
                  ];
                  Db::name("user_orders")->insert($data);
                  $this->response([], 200, '');
              }
          }
      }
    }
}
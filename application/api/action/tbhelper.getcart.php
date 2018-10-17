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
class tbhelpergetcart extends Baseaction
{
    function run()
    {
      //输入你的代码
      $r = $this->isAppLogin(false);
      if($r->isSuccess())
      {
          $data = [];
          $uid = $this->getUserId();
          $sql = "SELECT * FROM `%s`.`%s` WHERE user_id = ".$uid;
          $sql = sprintf($sql,config('database.database'),config('database.prefix').'user_taobao_cart');
          $row = Db::query($sql);
          if(!$row)
          {
              $this->response([], 200, '空空如也');
          }else{
              foreach ($row as $v)
              {
                  $key = $v['seller_id'];
                  if(!isset($data[$key]))
                  {
                      $data[$key] = [
                          'seller'=>$v['seller'],
                          'seller_id'=>$v['seller_id'],
                          'shop_id'=>$v['shop_id'],
                          'shop_name'=>$v['shop_name'],
                          'shop_type'=>$v['shop_type'],
                          'items'=>[]
                      ];
                  }
                  $item_key = $v['item_id'];
                  $data[$key]['items'][] = [
                      'title'=>$v['title'],
                      'item_id'=>$v['item_id'],
                      'origin'=>sprintf("%.2f",$v['origin']/100),
                      'sum'=>sprintf("%.2f",$v['sum']/100),
                      'skus'=>$v['skus'],
                      'coupon_id'=>$v['coupon_id'],
                      'commission_rate'=>$v['commission_rate'],
                      'commission_price'=>$v['commission_price'],
                  ];
              }
              $this->response($data, 200, '');
          }
      }else{
          $this->response([], $this->_notLoginCode, '');
      }
    }
}
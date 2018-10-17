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
class cashaccountdel extends Baseaction
{
    function run()
    {
      //输入你的代码
      $this->checkNeedParam(['id'=>'请选择要删除的数据!']);
      $id = ($this->getRequest('id',0));
      $r = $this->isAppLogin(false);
      if($r->isSuccess())
      {
          if($id)
          {
              $i = Db::name("users_shareinfo_cash_account")->where("id","IN",$id)->where("user_id","=",$this->getUserId())->delete();
              if($i)
              {
                  $this->response([], 200, '');
              }else{
                  $this->response([], 201, '对不起,不能删除此条数据!');
              }
          }else{
              $this->response([], 202, '对不起,请选择要删除的数据!');
          }
      }else{
          $this->response([], $this->_notLoginCode , '');
      }
    }
}
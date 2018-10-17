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
class cashaccountedit extends Baseaction
{
    function run()
    {
      //输入你的代码
      $r = $this->isAppLogin(false);
      if($r->isSuccess())
      {
          $data = $_REQUEST;
          $id = intval($this->getRequest('id',0));
          $edit = Db::name("users_shareinfo_cash_account")->where("user_id = ? AND id = ?",$this->getUserId(),$id)->update($data);
          if($edit)
          {
              $this->response([], 200, '');
          }else{
              $this->response([], 201, '更新失败!');
          }
      }else{
          $this->response([], $this->_notLoginCode, '');
      }
    }
}
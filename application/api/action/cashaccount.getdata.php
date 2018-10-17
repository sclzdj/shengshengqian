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
class cashaccountgetdata extends Baseaction
{
    function run()
    {
      //输入你的代码
      $r = $this->isAppLogin(false);
      if($r->isSuccess())
      {
          $row = Db::name("users_shareinfo_cash_account")->where("user_id = ?",[$this->getUserId()])->order("account_type ASC")->select();
          $this->response($row, 200, '');
      }else{
          $this->response([], $this->_notLoginCode, '');
      }
    }
}
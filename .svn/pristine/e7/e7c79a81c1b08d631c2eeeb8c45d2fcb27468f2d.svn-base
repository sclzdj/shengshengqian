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
class appmsgdeleted extends Baseaction
{
    function run()
    {
      //输入你的代码
      $this->checkNeedParam(['ids'=>'必须上传数据ID']);
      $user = $this->isAppLogin(false);
      if($user->isSuccess())
      {
          Db::name("user_message")->where("user_id = ? AND id in(?)",[$this->getUserId(),trim($this->getRequest('ids',''))])->delete();
          $this->response([], 200, '');
      }else{
          $this->response([], $this->_notLoginCode, '');
      }
    }
}
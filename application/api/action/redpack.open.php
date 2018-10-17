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
use app\common\model\UserRedbalanceLog;
/**
 * API action控制器
 * @package app\api\controller
 */
class redpackopen extends Baseaction
{
    function run()
    {
      //输入你的代码
      $this->checkNeedParam(['id'=>'请传入红包ID']);
      $id = intval($this->getRequest('id',0));
      $r = $this->isAppLogin(true);
      if($r->isSuccess())
      {
          $user = $r->getData();
          $redpack = Db::name("user_redpackage")->where('id = ?',[$id])->find();
          if(!$redpack)
          {
              $this->response([], 201, '没有找到红包数据');
          }else if($redpack['user_id'] <> $this->getUserId())
          {
              $this->response([], 202, '对不起,您不能打开不属于您的红包!');
          }else if($redpack['get_status'] == 1)
          {
              $this->response([], 203, '对不起,您的红包已经被拆开!');
          }else{
              //1.更新红包状态 2.添加红包记录 3.增加红包收益
              Db::name("user_redpackage")->where("id",$id)->update(['get_status'=>1,'get_time'=>time()]);
              //添加红包拆开记录
              $log = [
                  'user_id'=>$this->getUserId(),
                  'get_balance'=>sprintf(".2f",$redpack['red_balance'] / 100),
                  'get_time'=>time(),
                  'day'=>date('Ymd'),
              ];
              Db::name("user_redpackage_log")->insert($log);
              //添加红包收益记录
              $redlog = new UserRedbalanceLog();
              $redlog->setUser_id($this->getUserId());
              $redlog->setOp_redbalance(sprintf(".2f",$redpack['red_balance'] / 100));
              $redlog->setOp_class('openred');
              $redlog->setOp_remark('拆红包');
              $redlog->setBefore_redbalace($user['red_balance']);
              $redlog->saveLog();
              $this->response($redpack, 200, '');
          }
      }else{
          $this->response([], $this->_notLoginCode, '');
      }
    }
}
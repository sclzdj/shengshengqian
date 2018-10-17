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
use app\common\model\Users;
/**
 * API action控制器
 * @package app\api\controller
 */
class userteam extends Baseaction
{
    function run()
    {
      	$login = $this->isAppLogin(false);
      	if($login->isSuccess()){
          $level = $this->getRequest('level',1);
          $page = intval($this->getRequest('page',1));
          $pageSize = intval($this->getRequest('pageSize',10));
          $offset=($page-1)*$pageSize;
      		$user_id=$this->getUserId();
          $team=Users::get_level_team($user_id,$level);
          $team=array_slice($team, $offset, $pageSize);
          $data=[];
          foreach ($team as $k => $v) {
            $info=db('users')->find($v['id']);
            $pix=[];
            $pix['id']=$info['id'];
            $pix['header_ico']=$info['header_ico'];
            $pix['nickname']=$info['nickname'];
            $pix['username']=$info['username'];
            $pix['reg_time']=$info['reg_time'];
            $pix['first_order_time']=db('user_orders')->where('user_id',$info['id'])->order('created desc,id desc')->value('created');
            $pix['income']=0;//收益未做
            $data[]=$pix;
          }
          $this->response($data, 200, '');
      	}else{
            $this->response([], $this->_notLoginCode, '');
      	}
    }
}
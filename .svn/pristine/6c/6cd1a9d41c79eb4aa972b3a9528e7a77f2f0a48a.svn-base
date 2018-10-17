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

/**
 * API action控制器
 * @package app\api\controller
 */
class userdelwithdrawtype extends Baseaction
{
    function run()
    {
      	$login = $this->isAppLogin(false);
      	if($login->isSuccess()){
      		$user_id=$this->getUserId();
	        $id=$this->getRequest('id',0);
	        if($id!='0'){
            $rt=db('withdraw_type')->where('user_id',$user_id)->where('id','in',$id)->delete();
          }else{
            $rt=db('withdraw_type')->where('user_id',$user_id)->delete();
          }
        	if($rt!==false){
        		$this->response([], 200, '');
        	}else{
        		$this->response([], 201, '删除提现方式失败！');
        	}
      	}else{
            $this->response([], $this->_notLoginCode, '');
      	}
    }
}
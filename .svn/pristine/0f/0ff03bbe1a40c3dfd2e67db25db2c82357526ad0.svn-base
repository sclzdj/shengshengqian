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
class usercancelwithdraw extends Baseaction
{
    function run()
    {
      	$login = $this->isAppLogin(false);
      	if($login->isSuccess()){
      		$user_id=$this->getUserId();
      		//限制必要参数，可选，设置后会先 检查参数完整性，如果不完整会返回错误码，并且errmsg为value值
	        $this->checkNeedParam(['id'=>'请输入id']);
	        //获取请求参数
        	$id = $this->getRequest('id');
        	$withdraw=db('user_withdrawals_log a')->join('user_withdrawals_card b','a.card_id=b.id','LEFT')->field('a.status,b.card_base_price')->where(['a.user_id'=>$user_id,'a.id'=>$id])->find();
        	if(!$withdraw){
        		$this->response([], 202, '记录不存在');
        	}
        	if($withdraw['status']>0){
        		$this->response([], 202, '该提现申请已处理，不能撤销');
        	}
        	$user=db('users')->find($user_id);
        	$rt=db('users')->update(['id'=>$user_id,'red_balance'=>$user['red_balance']+$withdraw['card_base_price']]);
        	if($rt!==false){
        		db('user_withdrawals_log')->delete($id);
    			$this->response([], 200, '');
        	}else{
        		$this->response([], 201, '修改失败');
        	}
      	}else{
            $this->response([], $this->_notLoginCode, '');
      	}
    }
}
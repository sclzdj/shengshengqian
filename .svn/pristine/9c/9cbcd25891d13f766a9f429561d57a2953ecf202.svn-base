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
class userwithdraw extends Baseaction
{
    function run()
    {
      	$login = $this->isAppLogin(false);
      	if($login->isSuccess()){
      		$user_id=$this->getUserId();
	        $this->checkNeedParam(['card_id'=>'请选择提现卡ID','type_id'=>'请选择提现方式ID']);
	        $card_id = $this->getRequest('card_id');
	        $type_id = $this->getRequest('type_id');
	        $withdrawals_card=db('user_withdrawals_card a')->join('withdrawals_card b','a.card_id=b.id','LEFT')->where(['a.user_id'=>$user_id,'a.id'=>$card_id])->field('a.id,a.card_base_price,a.is_active,a.used,a.created,b.remark')->find();
	        if(!$withdrawals_card){
	        	$this->response([], 203, '红包提现卡未找到');
	        }
	        $withdraw_type=db('withdraw_type')->where(['id'=>$type_id,'user_id'=>$user_id])->find();
	        if(!$withdraw_type){
	        	$this->response([], 204, '提现方式未找到');
	        }
	        $user=db('users')->find($user_id);
	        if($user['red_balance']<$withdrawals_card['card_base_price']){
	        	$this->response([], 201, '余额不足，不能提现');
	        }
	        if($withdrawals_card['is_active']!=1){
	        	$this->response([], 202, '红包提现卡未激活');
	        }
	        if($withdrawals_card['used']==1){
	        	$this->response([], 205, '红包提现卡已使用');
	        }
	        db('users')->update(['id'=>$user_id,'red_balance'=>$user['red_balance']-$withdrawals_card['card_base_price']]);
	        db('user_withdrawals_card')->update(['id'=>$card_id,'used'=>1]);
	        db('user_withdrawals_log')->insert(['user_id'=>$user_id,'type_id'=>$type_id,'card_id'=>$card_id,'addtime'=>time()]);
		    $this->response([], 200, '');
      	}else{
            $this->response([], $this->_notLoginCode, '');
      	}
    }
}
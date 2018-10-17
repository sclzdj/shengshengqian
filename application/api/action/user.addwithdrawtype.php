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
class useraddwithdrawtype extends Baseaction
{
    function run()
    {
      	$login = $this->isAppLogin(false);
      	if($login->isSuccess()){
      		$user_id=$this->getUserId();
	        $this->checkNeedParam(['type'=>'请选择类型']);
        	$type = $this->getRequest('type');
        	if($type=='0' || $type=='1'){
        		if($type=='0'){
	        		$this->checkNeedParam(['card_num'=>'请输入银行卡号','card_name'=>'请输入银行卡姓名','bank_id'=>'请输入银行ID','bank_address'=>'请输入开户行地址']);
	        		$withdraw_type=db('withdraw_type')->where(['user_id'=>$user_id,'type'=>$type,'card_num'=>$this->getRequest('card_num','')])->find();
	        		if($withdraw_type){
	        			$this->response([], 202, '该银行卡账号已添加');
	        		}
	        		$data=['user_id'=>$user_id,'type'=>$type,'card_num'=>$this->getRequest('card_num',''),'card_name'=>$this->getRequest('card_name',''),'bank_id'=>$this->getRequest('bank_id',''),'bank_address'=>$this->getRequest('bank_address','')];
	        	}elseif($type=='1'){
	        		$this->checkNeedParam(['alipay'=>'请输入支付宝账号','alipay_name'=>'请输入支付宝姓名']);
	        		$withdraw_type=db('withdraw_type')->where(['user_id'=>$user_id,'type'=>$type,'alipay'=>$this->getRequest('alipay','')])->find();
	        		if($withdraw_type){
	        			$this->response([], 202, '该支付宝账号已添加');
	        		}
	        		$data=['user_id'=>$user_id,'type'=>$type,'alipay'=>$this->getRequest('alipay',''),'alipay_name'=>$this->getRequest('alipay_name','')];
	        	}
	        	$id=db('withdraw_type')->insertGetId($data);
	        	if($id>0){
	        		$this->response([], 200, '');
	        	}else{
	        		$this->response([], 201, '添加提现方式失败！');
	        	}
        	}else{
        		$data=['imgurl'=>'http://ntsv2.com/uploads/images/20170310/227afa7785bc7c8b512f983ebf4d0247.png','remark'=>'假文说明假文说明假文说明假文说明假文说明'];
        		$this->response($data, 200, '');
        	}
      	}else{
            $this->response([], $this->_notLoginCode, '');
      	}
    }
}
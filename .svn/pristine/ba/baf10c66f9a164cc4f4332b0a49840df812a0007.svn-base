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
class userwithdrawtype extends Baseaction
{
    function run()
    {
      	$login = $this->isAppLogin(false);
      	if($login->isSuccess()){
	        $id=$this->getRequest('id',0);
      		$user_id=$this->getUserId();
	        if($id>0){
	        	$withdraw_type=db('withdraw_type a')->join('bank b','a.bank_id=b.id','LEFT')->where(['a.id'=>$id,'a.user_id'=>$user_id])->field('a.*,b.name bank_name')->find();
		        if(!$withdraw_type){
		        	$this->response([], 201, '该提现方式不存在');
		        }
		        if($withdraw_type['type']=='0'){
		        	$data=['id'=>$withdraw_type['id'],'card_num'=>$withdraw_type['card_num'],'card_name'=>$withdraw_type['card_name'],'bank_id'=>$withdraw_type['bank_id'],'bank_name'=>$withdraw_type['bank_name'],'bank_address'=>$withdraw_type['bank_address']];
		        }elseif($withdraw_type['type']=='1'){
		        	$data=['id'=>$withdraw_type['id'],'alipay'=>$withdraw_type['alipay'],'alipay_name'=>$withdraw_type['alipay_name']];
		        }else{
		        	$data=['id'=>$withdraw_type['id']];
		        }
		        $data['type']=$withdraw_type['type'];
		        $this->response($data, 200, '');
	        }
	        $page = intval($this->getRequest('page',1));
        	$pageSize = intval($this->getRequest('pageSize',10));
        	$offset=($page-1)*$pageSize;
	        $withdraw_types=db('withdraw_type a')->join('bank b','a.bank_id=b.id','LEFT')->where(['a.user_id'=>$user_id])->field('a.*,b.name bank_name')->limit($offset,$pageSize)->select();
	        foreach ($withdraw_types as $k => $v) {
	        	if($v['type']=='0'){
		        	$withdraw_types[$k]=['id'=>$v['id'],'card_num'=>$v['card_num'],'card_name'=>$v['card_name'],'bank_id'=>$v['bank_id'],'bank_name'=>$v['bank_name'],'bank_address'=>$v['bank_address']];
		        }elseif($v['type']=='1'){
		        	$withdraw_types[$k]=['id'=>$v['id'],'alipay'=>$v['alipay'],'alipay_name'=>$v['alipay_name']];
		        }else{
		        	$withdraw_types[$k]=['id'=>$v['id']];
		        }
		        $withdraw_types[$k]['type']=$v['type'];
	        }
		    $this->response($withdraw_types, 200, '');
      	}else{
            $this->response([], $this->_notLoginCode, '');
      	}
    }
}
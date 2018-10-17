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
class userselectwithdraw extends Baseaction
{
    function run()
    {
      	$login = $this->isAppLogin(false);
      	if($login->isSuccess()){
      		$user_id=$this->getUserId();
	        $page = intval($this->getRequest('page',1));
        	$pageSize = intval($this->getRequest('pageSize',10));
        	$offset=($page-1)*$pageSize;
        	$withdraws=db('user_withdrawals_log a')->join('user_withdrawals_card b','a.card_id=b.id','LEFT')->join('withdraw_type c','a.type_id=c.id','LEFT')->field('a.id,a.status,a.addtime,b.card_base_price,c.type')->where(['a.user_id'=>$user_id])->order('a.addtime desc,a.id desc')->limit($offset,$pageSize)->select();
        	if($withdraws!==false){
    			$this->response($withdraws, 200, '');
        	}else{
        		$this->response([], 201, '查出提现申请失败');
        	}
      	}else{
            $this->response([], $this->_notLoginCode, '');
      	}
    }
}
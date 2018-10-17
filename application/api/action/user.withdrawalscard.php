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
class userwithdrawalscard extends Baseaction
{
    function run()
    {
      	$login = $this->isAppLogin(false);
      	if($login->isSuccess()){
      		$user_id=$this->getUserId();
	        $page = intval($this->getRequest('page',1));
        	$pageSize = intval($this->getRequest('pageSize',10));
        	$offset=($page-1)*$pageSize;
	        $withdrawals_cards=db('user_withdrawals_card a')->join('withdrawals_card b','a.card_id=b.id','LEFT')->where(['a.user_id'=>$user_id,'used'=>0])->field('a.id,a.card_base_price,a.is_active,a.used,a.created,b.remark')->limit($offset,$pageSize)->select();
          $user=db('users')->find($user_id);
          foreach ($withdrawals_cards as $k => $v) {
            if($user['red_balance']<$v['card_base_price'] || $v['is_active']!=1){
              $withdrawals_cards[$k]['color']=0;
            }else{
              $withdrawals_cards[$k]['color']=1;
            }
          }
		    $this->response($withdrawals_cards, 200, '');
      	}else{
            $this->response([], $this->_notLoginCode, '');
      	}
    }
}
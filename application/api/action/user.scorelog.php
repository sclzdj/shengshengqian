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
class userscorelog extends Baseaction
{
    function run()
    {
      	$login = $this->isAppLogin(false);
      	if($login->isSuccess()){
      		$user_id=$this->getUserId();
	        $page = intval($this->getRequest('page',1));
        	$pageSize = intval($this->getRequest('pageSize',10));
        	$offset=($page-1)*$pageSize;
	        $score_logs=db('user_score_log')->where(['user_id'=>$user_id])->limit($offset,$pageSize)->select();
		    $this->response($score_logs, 200, '');
      	}else{
            $this->response([], $this->_notLoginCode, '');
      	}
    }
}
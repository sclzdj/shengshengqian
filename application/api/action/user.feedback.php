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
class userfeedback extends Baseaction
{
    function run()
    {
        $login = $this->isAppLogin(false);
      	if($login->isSuccess()){
      		$user_id=$this->getUserId();
      		//限制必要参数，可选，设置后会先 检查参数完整性，如果不完整会返回错误码，并且errmsg为value值
	        $this->checkNeedParam(['content'=>'请输入意见反馈内容']);
	        //获取请求参数
        	$content = $this->getRequest('content');
        	$id=db('feedback')->insertGetId(['content'=>$content,'user_id'=>$user_id,'created'=>time()]);
        	if($id>0){
        		$this->response([], 200, '');
        	}else{
        		$this->response([], 201, '反馈意见提交失败！');
        	}
      	}else{
            $this->response([], $this->_notLoginCode, '');
      	}
    }
}
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
class userupdatepass extends Baseaction
{
    function run()
    {
      	$login = $this->isAppLogin(false);
      	if($login->isSuccess()){
      		$user_id=$this->getUserId();
      		//限制必要参数，可选，设置后会先 检查参数完整性，如果不完整会返回错误码，并且errmsg为value值
	        $this->checkNeedParam(['old_password'=>'请输入旧密码','new_password'=>'请输入新密码']);
	        //获取请求参数
        	$old_password = $this->getRequest('old_password');
        	$new_password = $this->getRequest('new_password');
        	$user=db('users')->find($user_id);
        	if(md5(md5($old_password).$user['salt'])===$user['password']){
        		if(md5(md5($new_password).$user['salt'])!==$user['password']){
        			$salt = mt_rand(100000, 999999);
    					$password=Users::makePassword($new_password, $salt);
    					$rt=db('users')->update(['id'=>$user_id,'password'=>$password,'salt'=>$salt]);
    					if($rt!==false){
    						$this->response([], 200, '');
    					}else{
    						$this->response([], 203, '修改密码失败');
    					}
        		}else{
        			$this->response([], 202, '新密码不能与原密码相同');
        		}
        	}else{
        		$this->response([], 201, '原密码输入错误');
        	}
      	}else{
            $this->response([], $this->_notLoginCode, '');
      	}
    }
}
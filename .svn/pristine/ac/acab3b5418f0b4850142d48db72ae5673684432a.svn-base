<?php
// +----------------------------------------------------------------------
// | TPPHP框架 [ DolphinPHP ]
// +----------------------------------------------------------------------
// | 版权所有 2016~2017   [ http://www.ruimeng898.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://www.ruimeng898.com
// +----------------------------------------------------------------------
// | 开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------

namespace app\api\controller;
use think\Cache;
/**
 * 前台首页控制器
 * @package app\index\controller
 */
class User extends Baseaction
{



    public function login()
    {
        //数据库处理
        //$this->_data  为当前请求的参数 包含get 和post
        //获取请求参数
        $username = $this->getRequest('username');

        //API输出
        $this->response($this->_data, 200, 'testok');
    }


   /**
    * 注册
    */
    function reg(){

        $needParam = ['username'=>'必须输入用户号码','password'=>'必须输入密码'];
        $this->checkNeedParam($needParam);
        $username = $this->getRequest('username');
        $this->response(['fuck'=>12],200,'ok');
    }

}

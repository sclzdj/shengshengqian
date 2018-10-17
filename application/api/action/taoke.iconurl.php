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
class taokeiconurl extends Baseaction
{
    function run()
    {
      	$login = $this->isAppLogin(false);
      	if($login->isSuccess()){
	        $icon_urls=db('icon_url a')->join('admin_attachment b','a.icon=b.id','LEFT')->field('a.id,a.title,a.url,b.path')->select();
		    $this->response($icon_urls, 200, '');
      	}else{
            $this->response([], $this->_notLoginCode, '');
      	}
    }
}
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
class userbanklist extends Baseaction
{
    function run()
    {
    	$list=db('bank')->order('sort asc,id desc')->field('id,name')->select();
      	if($list!==false){
    		$this->response($list, 200, '');
    	}else{
    		$this->response([], 201, '查询银行失败！');
    	}
    }
}
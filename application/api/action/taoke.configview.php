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
class taokeconfigview extends Baseaction
{
    function run()
    {
      	$views=db('config')->field('id,title')->where('type','view')->select();
      	foreach ($views as $k => $v) {
          $views[$k]['id']='传入id='.$v['id'].'时为：'.$v['title'];
      		$views[$k]['url']='index/appweb/news/id/'.$v['id'];
          unset($views[$k]['title']);
      	}
        dump($views);die;
      	if($views!==false){
			$this->response($views, 200, '');
    	}else{
    		$this->response([], 201, '查出失败');
    	}
    }
}
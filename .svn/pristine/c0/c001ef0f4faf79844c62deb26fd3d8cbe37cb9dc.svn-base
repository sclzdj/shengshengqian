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
class usercollection extends Baseaction
{
    function run()
    {
      	$login = $this->isAppLogin(false);
      	if($login->isSuccess()){
      		$user_id=$this->getUserId();
	        $page = intval($this->getRequest('page',1));
        	$pageSize = intval($this->getRequest('pageSize',10));
        	$offset=($page-1)*$pageSize;
	        $collections=db('user_collection a')->join('taobao_items b','a.item_id=b.item_id','LEFT')->join('taobao_url c','b.item_id=c.itemid','LEFT')->where(['a.user_id'=>$user_id])->field('a.id,a.item_id,a.created,b.title,b.pic')->limit($offset,$pageSize)->select();
		    $this->response($collections, 200, '');
      	}else{
            $this->response([], $this->_notLoginCode, '');
      	}
    }
}
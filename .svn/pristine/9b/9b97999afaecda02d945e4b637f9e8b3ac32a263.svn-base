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
class useraddcollection extends Baseaction
{
    function run()
    {
      	$login = $this->isAppLogin(false);
      	if($login->isSuccess()){
      		$user_id=$this->getUserId();
	        $this->checkNeedParam(['id'=>'商品id']);
	        $id = $this->getRequest('id');
	        $item=db('taobao_items')->where('item_id',$id)->find();
	        if(!$item){
	        	$this->response([], 201, '该商品不存在');
	        }
	        $collection=db('user_collection')->where(['user_id'=>$user_id,'item_id'=>$id])->find();
	        if($collection){
	        	$this->response([], 202, '该商品已收藏过了');
	        }
	        $id=db('user_collection')->insertGetId(['user_id'=>$user_id,'item_id'=>$id,'created'=>time()]);
	        if($id>0){
	        	$this->response([], 200, '');
	        }else{
	        	$this->response([], 202, '收藏失败');
	        }
	    }else{
            $this->response([], $this->_notLoginCode, '');
      	}
    }
}
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
class taokeappload extends Baseaction
{
    function run()
    {
      	$configs=db('config')->where('load','1')->where('agent_id',AdminClientkey::getAgentId($this->getAppid()))->select();
      	$data=[];
      	foreach ($configs as $k => $v) {
      		if($v['type']=='image'){
      			$data[$v['name']]=api_get_file_path($v['val']);
      		}elseif($v['type']=='view'){
      			$data[$v['name']]='index/appweb/news/id/'.$v['id'];
      		}else{
      			$data[$v['name']]=$v['val'];
      		}
      	}
      	//注释
      	/*$remark=[];
      	foreach ($configs as $k => $v) {
      		$remark[$v['name']]=$v['title'];
      	}
      	dump($remark);*/
      	$this->response($data, 200, '');
    }
}
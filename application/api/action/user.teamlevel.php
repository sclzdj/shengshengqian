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
class userteamlevel extends Baseaction
{
    function run()
    {
      	$login = $this->isAppLogin(false);
      	if($login->isSuccess()){
      		$user_id=$this->getUserId();
      		$level=(int)G_CF('api_team_level');
	        $level=$level>0?$level:1;
	        $levelname=G_CF('api_team_levelname');
	        $levelname=str_replace('，', ',', $levelname);
	        $levelname=explode(',', $levelname);
	        $count=count($levelname);
	        if($count<$level){
	        	for ($i=0; $i < $level-$count; $i++) { 
	        		$levelname[]=($count+$i+1).'级';
	        	}
	        }
	        $data=[];
	        foreach ($levelname as $k => $v) {
	        	$pix=[];
	        	$pix['level']=$k+1;
	        	$pix['name']=$v;
	        	$data[]=$pix;
	        }
	        $data=array_slice($data,0,$level);
	        $this->response($data, 200, '');
      	}else{
            $this->response([], $this->_notLoginCode, '');
      	}
    }
}
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
use think\Db;

/**
 * API action控制器
 * @package app\api\controller
 */
class taokebrowsinghistory extends Baseaction
{
    function run()
    {
		$this->checkNeedParam(['gid'=>'商品ID,必须','imei'=>'手机唯一码,必须']);
		$r = $this->isAppLogin(false);
		$uid = $r->isSuccess() ? $this->getUserId() : 0;
        $gid = intval($this->getRequest('gid'));
        $imei = trim($this->getRequest('imei'));
		$data = [
			'imei' => $imei,
			'user_id' => $uid,
			'item_id' => $gid,
			'created' => time()
		];
		$browse = Db::name('browse_log')->insert($data);
		$browse?$this->response([],200,'记录成功'):$this->response([],201,'记录失败');
    }
}
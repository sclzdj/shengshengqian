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

namespace app\common\model;
use think\Model;
use think\Db;
// use util\Tree;
// use app\admin\model\Menu as MenuModel;

/**
 * 角色模型
 * @package app\admin\model
 */
class MerchantUser extends Model
{
	protected $autoWriteTimestamp = true;
	protected $createTime = 'created';
    // 设置当前模型对应的完整数据表名称
    protected $table = 'v2_merchant_user';
    //返回商家列表
    public static function getShopTree(){
    	$shopTree = Db::table('v2_merchant_user')
            ->where(['agent_id' => UID,'status'=>1])
            ->field('id,nickname')
            ->select();
    	if(count($shopTree))
    	$result = [0=>'默认代理'];
    	foreach($shopTree as $key => $val){
    	   $result[$val['id']] = $val['nickname'];
    	}
        return $result;
    }
    
    
}
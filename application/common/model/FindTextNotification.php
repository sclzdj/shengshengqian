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
use util\Tree;
// use app\admin\model\Menu as MenuModel;

/**
 * 角色模型
 * @package app\admin\model
 */
class FindTextNotification extends Model
{
	protected $autoWriteTimestamp = true;
	protected $createTime = 'created';
    // 设置当前模型对应的完整数据表名称
    protected $table = 'v2_find_text_notification';
    //返回按钮功能
    public static function getButtonTree(){
        return ['url'=>'链接','nts_go_view'=>'跳转客户端视图','call'=>'拨打电话'];
    }
}
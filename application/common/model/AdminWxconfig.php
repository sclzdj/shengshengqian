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
use think\fn\Result;
/**
 * 公共模型
 * @package app\common\model
 */
class AdminWxconfig extends Model
{
    /**
     * 获取指定代理的微信配置
     * @param int $agent_id
     */
    public static function getWXconfig($agent_id)
    {
        $agent_id = intval($agent_id);
        $row = Db::name("admin_wxconfig")->where("agent_id = ?",[$agent_id])->find();
        if($row)
        {
            return new Result(200, $row, '');
        }else{
            return new Result(201, [], '没有配置微信数据!');
        }
    }
}
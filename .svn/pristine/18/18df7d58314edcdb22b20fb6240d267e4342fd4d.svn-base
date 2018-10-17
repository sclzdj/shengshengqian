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

/**
 * 公共模型
 * @package app\common\model
 */
class UsersBills extends Model
{
    /**
     * 添加账户变动记录
     * @param int $user_id
     * @param decimal $money
     * @param string $remark
     * @param string $opt_class
     */
    public static function addLog($user_id,$money,$remark,$opt_class)
    {
        $data = [
            'user_id'=>intval($user_id),
            'remark'=>trim($remark),
            'balance'=>sprintf("%.4f",$money),
            'created'=>time(),
            'opt_class'=>trim($opt_class)
        ];
        self::insert($data);
        return true;
    }
}
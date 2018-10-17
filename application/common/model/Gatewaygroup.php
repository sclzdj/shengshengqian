<?php
// +----------------------------------------------------------------------
// | TPPHP框架 [ DolphinPHP ]
// +----------------------------------------------------------------------
// | 版权所有 2016~2017 成都软件开发有限公司 [ http://www.ruimeng898.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://www.ruimeng898.com
// +----------------------------------------------------------------------
// | 开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------

namespace app\common\model;
use think\Model;
use think\paginator;

/**
 * 后台用户模型
 * @package app\admin\model
 */
class Gatewaygroup extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = '__GATEWAY_GROUP__';

    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    /**
     * 根据附件id获取路径
     * @param  string $id 附件id
     * @return string     路径
     */
    public function getFilePath($id = '')
    {
        return $this->where('id', $id)->value('path');
    }

    /**
     * 根据图片id获取缩略图路径，如果缩略图不存在，则返回原图路径
     * @param string $id 图片id
     * @author 蔡伟明 <460932465@qq.com>
     * @return mixed
     */
    public function getThumbPath($id = '')
    {
        $result = $this->where('id', $id)->field('path,thumb')->find();
        if ($result) {
            return $result['thumb'] != '' ? $result['thumb'] : $result['path'];
        } else {
            return $result;
        }
    }

    /**
     * 根据附件id获取名称
     * @param  string $id 附件id
     * @return string     名称
     */
    public function getFileName($id = '')
    {
        return $this->where('id', $id)->value('name');
    }
}

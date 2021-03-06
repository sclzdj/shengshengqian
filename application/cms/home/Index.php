<?php
// +----------------------------------------------------------------------
// | TPPHP框架 [ DolphinPHP ]
// +----------------------------------------------------------------------
// | 版权所有 2016~2017   [ http://www.ruimeng898.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://www.ruimeng898.com
// +----------------------------------------------------------------------
// | 开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------

namespace app\cms\home;

/**
 * 前台首页控制器
 * @package app\cms\admin
 */
class Index extends Common
{
    /**
     * 首页
     * @author 蔡伟明 <460932465@qq.com>
     * @return mixed
     */
    public function index()
    {
        return $this->fetch(); // 渲染模板
    }
}
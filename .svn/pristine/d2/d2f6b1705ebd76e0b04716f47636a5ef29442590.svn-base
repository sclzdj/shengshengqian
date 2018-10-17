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
class refundshopgetclass extends Baseaction
{
    function run()
    {
      //输入你的代码
      $row = Db::name("fanli_class")->where("is_show = 1")->field("id,name")->order("id DESC")->select();
      $row[0] = ['id'=>0,'name'=>'推荐商城'];
      $this->response($row, 200, '');
    }
}
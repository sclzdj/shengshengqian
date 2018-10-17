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
class refundshopgetdata extends Baseaction
{
    function run()
    {
      //输入你的代码
      $this->checkNeedParam(['pid'=>'请传入分类']);
      $pid = intval($this->getRequest('pid',0));
      $where = ['is_show'=>1];
      if($pid)
      {
          $where['class_id'] = $pid;
      }else {
          $where['is_recommend'] = 1;
      }
      $row = Db::name("fanli_shop")->where($where)->field("id,ico,label")->order("id DESC")->select();
      foreach ($row as &$v)
      {
          $v['ico'] = api_get_file_path($v['ico']);
      }
      $this->response($row, 200, '');
    }
}
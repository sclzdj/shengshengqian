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
class itemclassgetdata extends Baseaction
{
    function run()
    {
      //输入你的代码
      $this->checkNeedParam(['pid'=>'传入上级分类id']);
      $pid = intval($this->getRequest('pid',0));
      $where['is_show'] = 1; 
      if($pid)
      {
          $where['parent_id'] = $pid;
      }
      $row = Db::name("taobao_itemclass")->where($where)->order("weight DESC")->field("id,name,pic")->select();
      foreach ($row as &$v)
      {
          if(intval($v['pic']))
          {
              $v['pic'] = api_get_file_path($v['pic']);
          }else{
              $v['pic'] = '';
          }
      }
      if($pid == 0)
      {
          $default[] = ['name'=>'全部','pic'=>0,'id'=>0];
          $row = array_merge($default,$row);
      }
      $this->response($row, 200, '');
    }
}
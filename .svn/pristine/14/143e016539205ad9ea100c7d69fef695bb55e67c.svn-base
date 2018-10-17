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
class picadgetdata extends Baseaction
{
    function run()
    {
      //输入你的代码
      $this->checkNeedParam(['key1'=>'广告关键字1','key2'=>'广告关键字2']);
      $key1 = trim($this->getRequest('key1',''));
      $key2 = trim($this->getRequest('key2',''));
      $where = [];
      if($key1)
      {
          $where['ad_key'] = $key1;
      }
      if($key2)
      {
          $where['ad_key2'] = $key2;
      }
      $row = Db::name('pic_ad')->where($where)->field("method,target,target_param,pic")->select();
      foreach ($row as $k=>&$v)
      {
          $v['pic'] = api_get_file_path($v['pic']);
      }
      
      $this->response($row, 200, '');
    }
}
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
class exchangeproduct extends Baseaction
{
    function run()
    {
      //输入你的代码
      $this->checkNeedParam(['p_type'=>'请传入兑换产品类型!','page'=>'请输入页码','pagesize'=>'请输入每页数据量']);
      $ptype = intval($this->getRequest('p_type',0));
      $page = intval($this->getRequest('page',1));
      if($page < 1)
      {
          $page = 1;
      }
      $pagesize = intval($this->getRequest('pagesize',10));
      
      $row = Db::name("exchange_product")->where("p_type = ? AND is_show = 1",[$ptype])->field("id,title,remark,pic,red_price,score,stock")->order("weight DESC")->page($page,$pagesize)->select();
      foreach ($row as $k=>&$v)
      {
          $v['pic'] = api_get_file_path($v['pic']);
      }
      $this->response($row, 200, '');
    }
}
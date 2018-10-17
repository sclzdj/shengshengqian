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
class itemsfind extends Baseaction
{
    function run()
    {
      //输入你的代码
      $this->checkNeedParam(['q'=>'请传入搜索字符']);
      $q = $this->getRequest('q','');
      if($q)
      {
          //专柜正品巨补水春夏护肤品套装 持久补水保湿嫩肤白皙淡斑收毛孔
          //【专柜正品巨补水春夏护肤品套装 持久补水保湿嫩肤白皙淡斑收毛孔】，点击链接再选择浏览器打开http://c.b0yp.com/h.6DOXQA?cv=ANA1Zus356y&sm=e4f36a，或复制这条信息￥ANA1Zus356y￥后打开手机淘宝
          if(strpos($q,'【'))
          {
              $q = get_split('【','】',$q);
          }
          $item = Db::name("taobao_items")->where("title = ?",[$q])->field("id,item_id,title,pic")->find();
          if($item)
          {
              $this->response($item, 200, '');
          }else{
              $this->response([], 201, 'not find');
          }
      }
    }
}
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
class itemsgetdata extends Baseaction
{
    function run()
    {
      //输入你的代码
      $this->checkNeedParam(['page'=>'请输入页码','pagesize'=>'请输入页面数据量','q'=>'输入搜索关键字','pid'=>'产品分类ID必须传入','sort'=>'必须传入排序方式','sort_type'=>'传入排序类型']);
      $page = intval($this->getRequest('page',1));
      $pagesize = intval($this->getRequest('pagesize',10));
      $q = trim($this->getRequest('q',''));
      $pid = intval($this->getRequest('pid',0));
      $sort = trim($this->getRequest('sort','default'));//default=推荐[默认]、sales_num=销量、coupon=有卷、refund=返现、buy_price=到手价、share=分享赚
      $sort_type = intval($this->getRequest('sort_type',0));
      if($sort_type == 0)
      {
          $sort_type = 'DESC';
      }else{
          $sort_type = 'ASC';
      }
      if($sort == '' || strtolower($sort) == 'default')
      {
          $sort = 'id';
      }else if($sort == 'sales_num')
      {
          $sort = 'month_sales';
      }else if($sort == 'coupon')
      {
          $sort = 'coupon_have';
      }else if($sort == 'refund')
      {
          $sort = 'commission_price';
      }else if($sort == 'buy_price')
      {
          $sort = 'last_price';
      }else if($sort == 'share')
      {
          $sort = 'share_red_price';
      }
      $where = 'is_show = 1';
      $param = [];
      if($q)
      {
          $where .= ' AND (title like :q1 or class_name like :q2)';
          $param['q1'] = $q.'%';
          $param['q2'] = $q.'%';
      }
      if($pid)
      {
          $pinfo = Db::name("taobao_itemclass")->where("id = ?",[$pid])->field("id,name")->find();
          if($pinfo)
          {
              $where .= " AND class_name like :q3";
              $param['q3'] = $pinfo['name'].'%';
          }
      }
      $row = Db::name("taobao_items")->where($where,$param)->field("id,item_id,title,pic,price,month_sales,commission_price,coupon_have,share_red_have,seller_type,coupon_price")->order($sort,$sort_type)->page($page,$pagesize)->select();
      $this->response($row  , 200, '');
    }
}
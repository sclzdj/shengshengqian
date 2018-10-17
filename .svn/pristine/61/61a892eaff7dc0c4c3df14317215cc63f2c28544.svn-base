<?php
namespace app\taoke\admin;

use app\admin\controller\Admin;
use think\Db;
use think\Request;
use think\Validate;
use app\common\builder\ZBuilder; // 引入ZBuilder

/**
 * Userorders 后台模块
 */
class Userorders extends Admin
{
	//用户管理
	public function index()
	{
        // 获取排序
        $order = $this->getOrder();
        if($order===''){
            $order='a.created desc,a.id desc';
        }
        // 获取筛选
        $map = $this->getMap();
        if(isset($map['a.created'])){
            $map['a.created'][1][0]=strtotime($map['a.created'][1][0]);
            $map['a.created'][1][1]=strtotime($map['a.created'][1][1]);
        }
        // 读取用户数据
        $data_list = Db::name('user_orders a')->join('users b','a.user_id=b.id','LEFT')->field('a.*,b.username')->where($map)->order($order)->paginate();
        // 分页数据
        $page = $data_list->render();
        return ZBuilder::make('table')
            ->setPageTitle('用户订单列表') // 设置页面标题
            ->setPageTips('删除可能会导致其他的相关数据失效，请谨慎操作') // 设置页面提示信息
            ->setTableName('user_orders') // 指定数据表名
            ->addOrder('a.id,a.created,a.order_id,a.user_get_balance,a.total_price,a.get_time,a.income_estimate') // 添加排序
            ->addTimeFilter('a.created') // 添加时间段筛选
            ->setSearch(['a.id' => 'ID','a.order_id'=>'订单ID','b.username'=>'用户名']) // 设置搜索参数
            ->addFilter('a.status', ['0'=>'未查到订单', '1'=>'已付款', '2'=>'已返现', '3'=>'失败']) // 添加标题字段筛选
            ->addColumns([
                    ['id', 'ID'],
                    ['username', '用户名'],
                    ['order_id','订单ID'],
                    ['total_price','订单金额','callback','str_linked','元'],
                    ['user_get_balance','返利金额','callback','str_linked','元'],
                    ['get_time','返利到账时间','datetime','未知'],
                    ['income_estimate','预估收入','callback','str_linked','元'],
                    ['status', '状态', 'callback', 'array_v', array('未查到订单','已付款','已返现','失败')],
                    ['created', '订单时间', 'datetime','未知'],
                    ['right_button', '操作', 'btn'],
                ]) //添加多列数据
            ->addRightButtons(['delete'=>['table'=>'user_orders']]) // 批量添加右侧按钮
            ->addTopButtons(['delete'=>['table'=>'user_orders'],'custom'=>['title'=>'无筛选','href'=>url('index')]]) // 批量添加顶部按钮
            ->setRowList($data_list) // 设置表格数据
            ->setPages($page) // 设置分页数据
            ->fetch();
	}
}
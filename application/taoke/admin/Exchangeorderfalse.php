<?php
namespace app\taoke\admin;

use app\admin\controller\Admin;
use think\Db;
use think\Request;
use think\Validate;
use app\common\builder\ZBuilder; // 引入ZBuilder

/**
 * Exchangeorderfalse 后台模块
 */
class Exchangeorderfalse extends Admin
{
	//银行管理
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
		// 读取银行数据
		$data_list = Db::name('exchange_order a')->join('exchange_product b','a.pid=b.id','LEFT')->join('users c','a.user_id=c.id','LEFT')->field('a.*,b.title,c.username')->where($map)->where('a.p_type','0')->order($order)->paginate();
		// 分页数据
		$page = $data_list->render();
		// 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
        	->setPageTitle('兑换虚拟商品列表') // 设置页面标题
        	->setPageTips('删除可能会导致其他的相关数据失效，请谨慎操作') // 设置页面提示信息
        	->addOrder('id,a.created,red_price,score') // 添加排序
            ->addTimeFilter('a.created') // 添加时间段筛选
            ->setSearch(['a.id' => 'ID','b.title'=>'商品名称','b.username'=>'用户名']) // 设置搜索参数
        	->addColumns([
        			['id', 'ID'],
                    ['username', '用户'],
        			['title', '商品名称'],
        			['red_balance','金额','callback','str_linked','元'],
        			['score', '积分'],
        			['recharge_account', '充值账号'],
        			['created', '添加时间','datetime','未知'],
        			['right_button', '操作', 'btn'],
        		]) //添加多列数据
        	->addRightButtons(['edit','delete'=>['table'=>'exchange_order']]) // 批量添加右侧按钮
    		->addTopButtons(['add','delete'=>['table'=>'exchange_order']]) // 批量添加顶部按钮
        	->setRowList($data_list) // 设置表格数据
        	->setPages($page) // 设置分页数据
        	->fetch();
	}
}
<?php
namespace app\taoke\admin;

use app\admin\controller\Admin;
use think\Db;
use think\Request;
use think\Validate;
use app\common\builder\ZBuilder; // 引入ZBuilder

/**
 * Redpackagelog 后台模块
 */
class Redpackagelog extends Admin
{
	//银行管理
	public function index(){
		// 获取排序
        $order = $this->getOrder();
        if($order===''){
            $order='a.get_time desc,a.id desc';
        }
        // 获取筛选
        $map = $this->getMap();
        if(isset($map['get_time'])){
            $map['a.get_time'][1][0]=strtotime($map['a.get_time'][1][0]);
            $map['a.get_time'][1][1]=strtotime($map['a.get_time'][1][1]);
        }
        // 读取用户数据
        $data_list = Db::name('user_redpackage_log a')->join('users b','a.user_id=b.id','LEFT')->field('a.*,b.username')->where($map)->order($order)->paginate();
        // 分页数据
        $page = $data_list->render();
        return ZBuilder::make('table')
            ->setPageTitle('领取红包记录列表') // 设置页面标题
            ->setPageTips('删除可能会导致其他的相关数据失效，请谨慎操作') // 设置页面提示信息
            ->setTableName('user_redpackage_log') // 指定数据表名
            ->addOrder('a.id,a.get_time,a.get_balance') // 添加排序
            ->addTimeFilter('a.get_time') // 添加时间段筛选
            ->setSearch(['a.id' => 'ID','b.username'=>'用户名']) 
            ->addColumns([
                    ['id', 'ID'],
                    ['username', '用户名'],
                    ['get_balance','领取金额','callback','str_linked','元'],
                    ['get_time', '领取时间', 'datetime','未知'],
                    ['remark','说明'],
                    ['right_button', '操作', 'btn'],
                ]) //添加多列数据
            ->addRightButtons(['delete'=>['table'=>'user_redpackage_log']]) // 批量添加右侧按钮
            ->addTopButtons(['delete'=>['table'=>'user_redpackage_log'],'custom'=>['title'=>'无筛选','href'=>url('index')]]) // 批量添加顶部按钮
            ->setRowList($data_list) // 设置表格数据
            ->setPages($page) // 设置分页数据
            ->fetch();
	}
}
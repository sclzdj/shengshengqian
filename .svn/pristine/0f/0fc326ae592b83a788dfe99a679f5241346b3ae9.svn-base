<?php
namespace app\taoke\admin;

use app\admin\controller\Admin;
use think\Db;
use think\Request;
use think\Validate;
use app\common\builder\ZBuilder; // 引入ZBuilder

/**
 * Alimamaorders 后台模块
 */
class Alimamaorders extends Admin
{
	//用户管理
	public function index()
	{
		// 获取排序
        $order = $this->getOrder();
        if($order===''){
            $order='id desc';
        }
        // 获取筛选
        $map = $this->getMap();
		// 读取用户数据
		$data_list = Db::name('alimama_orders')->where($map)->order($order)->paginate();
		// 分页数据
		$page = $data_list->render();
     
		// 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
        	->setPageTitle('淘客产品列表') // 设置页面标题
        	->setPageTips('修改和删除可能会导致其他的相关数据失效，请谨慎操作') // 设置页面提示信息
        	->addOrder('id,order_id,income_rate,total_price,income_estimate,settlement_amount,commission_rate') // 添加排序
            ->addTimeFilter('buy_time') // 添加时间段筛选
            ->setSearch(['id' => 'ID','order_id' => '订单号','product_info'=>'商品']) // 设置搜索参数
        	->addColumns([
        			['id', 'ID'],
                    ['order_id', '订单号'],
        			['click_time', '点击时间'],
                    ['buy_time', '购买时间'],
                    ['product_info', '商品'],
                    ['product_num', '购买数量'],
                    ['order_status', '订单状态'],
                    ['order_type', '订单类型'],
                    ['income_rate', '收入比例','callback','str_linked','%'],
                    ['total_price', '付款金额','callback','str_linked','元'],
                    ['income_estimate', '预估收入','callback','str_linked','元'],
                    ['settlement_amount', '结算金额','callback','str_linked','元'],
                    ['settlement_time', '结算时间'],
                    ['commission_rate', '佣金比例','callback','str_linked','%'],
                    ['seller_nickname', '卖家昵称'],
                    ['seller_shopname', '卖家店铺'],
        		]) //添加多列数据
        	->addRightButtons(['edit','delete'=>['table'=>'alimama_orders']]) // 批量添加右侧按钮
    		->addTopButtons(['add','delete'=>['table'=>'alimama_orders'],'custom'=>['title'=>'无筛选','href'=>url('index')]]) // 批量添加顶部按钮
            ->addTopButton('custom',['title'=>'导入excel文件','href'=>url('excel')])
        	->setRowList($data_list) // 设置表格数据
        	->setPages($page) // 设置分页数据
        	->fetch();
	}
    //导入excel
    public function excel(){
        //判断是否为post请求
        if (Request::instance()->isPost()) {
            //获取请求的post数据
            $data=input('post.');
            $attachment=Db::name('admin_attachment')->find($data['excel']);
            $file = $attachment['path'];
            //导入代码
            //...
            ////...
            /////...
            /////...
            /////...
            /////...
            ///////////
            /////... //
            ///////////
            //...
            ////...
            /////...
            ///
        }
        // 使用ZBuilder快速创建表单
        return ZBuilder::make('form')
            ->setPageTitle('导入excel') // 设置页面标题
            ->setPageTips('导入后系统会自动更新淘客产品数据') // 设置页面提示信息
            //->setUrl('') // 设置表单提交地址
            //->hideBtn(['back']) //隐藏默认按钮
            ->setBtnTitle('submit', '确定') //修改默认按钮标题
            ->addBtn('<button type="reset" class="btn btn-default">重置</button>') //添加额外按钮
            ->addFile('excel', '请选择excel文件', '只能选择后缀为xls的文件', '', '2048', 'xls')
            ->isAjax(false) //默认为ajax的post提交
            ->fetch();
    }
    //修改商品
    public function edit($id=""){
        //判断是否为post请求
        if (Request::instance()->isPost()) {
            //获取请求的post数据
            $data=input('post.');
            //数据输入验证
            if(!preg_match("/^\d+$/",$data['read_number'])){
                return $this->error('浏览量必须为整数');
            }
            if(!preg_match("/^\d+(\.\d+)?$/",$data['share_red_price'])){
                return $this->error('分享红包格式错误');
            }
            //数据处理
            $update=array();
            $update['id']=$data['id'];
            $update['read_number']=$data['read_number'];
            $update['share_red_price']=$data['share_red_price'];
            //数据更新
            $rt=Db::name("taobao_items")->update($update);
            //跳转
            if($rt!==false){
                return $this->success('修改用户成功','index','',1);
            } else {
                return $this->error('修改用户失败');
            }
        }
        // 接收id
        if ($id>0) {
            // 查处数据
            $taobao_items=Db::name("taobao_items")->where('id',$id)->find();
            // 使用ZBuilder快速创建表单
            return ZBuilder::make('form')
                ->setPageTitle('修改淘客商品') // 设置页面标题
                ->setPageTips('该操作可能会导致其他的相关数据失效，请勿随意修改信息') // 设置页面提示信息
                //->setUrl('edit') // 设置表单提交地址
                //->hideBtn(['back']) //隐藏默认按钮
                ->setBtnTitle('submit', '确定') //修改默认按钮标题
                ->addBtn('<button type="reset" class="btn btn-default">重置</button>') //添加额外按钮
                ->addText('share_red_price', '分享红包金额','请尽量不要在此处修改，精确到分',$taobao_items['share_red_price'],['', '元'])
                ->addText('read_number', '浏览量','请输入整数',$taobao_items['read_number'])
                ->addHidden('id',$taobao_items['id'])
                //->isAjax(false) //默认为ajax的post提交
                ->fetch();
        }
    }
}
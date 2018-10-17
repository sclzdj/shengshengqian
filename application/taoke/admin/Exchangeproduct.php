<?php
namespace app\taoke\admin;

use app\admin\controller\Admin;
use think\Db;
use think\Request;
use think\Validate;
use app\common\builder\ZBuilder; // 引入ZBuilder

/**
 * Exchangeproduct 后台模块
 */
class Exchangeproduct extends Admin
{
	//银行管理
	public function index()
	{
		// 获取排序
        $order = $this->getOrder();
        if($order===''){
            $order='weight asc,created desc,id desc';
        }
        // 获取筛选
        $map = $this->getMap();
        if(isset($map['created'])){
            $map['created'][1][0]=strtotime($map['created'][1][0]);
            $map['created'][1][1]=strtotime($map['created'][1][1]);
        }
		// 读取银行数据
		$data_list = Db::name('exchange_product')->where($map)->order($order)->paginate();
		// 分页数据
		$page = $data_list->render();
		// 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
        	->setPageTitle('兑换商品列表') // 设置页面标题
        	->setPageTips('删除可能会导致其他的相关数据失效，请谨慎操作') // 设置页面提示信息
        	->addOrder('id,created,red_price,score,cash,weight,stock') // 添加排序
            ->addTimeFilter('created') // 添加时间段筛选
            ->setSearch(['id' => 'ID','title'=>'名称']) // 设置搜索参数
        	->addColumns([
        			['id', 'ID'],
        			['title', '商品名称'],
        			['pic','图片','picture'],
        			['remark','描述'],
        			['red_price','红包','callback','str_linked','元'],
        			['score', '积分'],
        			['cash','现金','callback','str_linked','元'],
        			['stock', '库存'],
        			['weight', '权重'],
        			['p_type', '类型', 'status', '', ['虚拟', '实物']],
        			['is_show', '是否显示', 'status', '', ['否', '是']],
        			['created', '添加时间','datetime','未知'],
        			['right_button', '操作', 'btn'],
        		]) //添加多列数据
        	->addRightButtons(['edit','delete'=>['table'=>'exchange_product']]) // 批量添加右侧按钮
    		->addTopButtons(['add','delete'=>['table'=>'exchange_product']]) // 批量添加顶部按钮
        	->setRowList($data_list) // 设置表格数据
        	->setPages($page) // 设置分页数据
        	->fetch();
	}
	//添加分类
	public function add()
	{
		//判断是否为post请求
		if (Request::instance()->isPost()) {
			//获取请求的post数据
			$data=input('post.');
			//数据处理
			$insert=array();
			$insert['title']=$data['title'];
			$insert['pic']=$data['pic'];
			$insert['remark']=$data['remark'];
			$insert['red_price']=$data['red_price'];
			$insert['score']=$data['score'];
			$insert['cash']=$data['cash'];
			$insert['stock']=$data['stock'];
			$insert['weight']=$data['weight'];
			$insert['p_type']=$data['p_type'];
			$insert['is_show']=$data['is_show'];
			$insert['created']=time();
			//数据入库
			$exchange_product_id=Db::name("exchange_product")->insert($insert);
			//跳转
			if($exchange_product_id>0){
				return $this->success('添加商品成功','index','',1);
	        } else {
	            return $this->error('添加商品失败');
	        }
		}
		// 使用ZBuilder快速创建表单
		return ZBuilder::make('form')
			->setPageTitle('添加商品') // 设置页面标题
			->setPageTips('请认真填写相关信息') // 设置页面提示信息
			//->setUrl('add') // 设置表单提交地址
			//->hideBtn(['back']) //隐藏默认按钮
			->setBtnTitle('submit', '确定') //修改默认按钮标题
			->addBtn('<button type="reset" class="btn btn-default">重置</button>') //添加额外按钮
			->addText('title', '商品名称','请最好不要超过20个汉字')
			->addImage('pic', '商品图片','')
			->addTextarea('remark', '商品描述','请最好不要超过100个汉字')
			->addText('red_price', '红包','')
			->addText('score', '积分','')
			->addText('cash', '现金','')
			->addText('stock', '库存量','')
			->addRadio('p_type', '类型','', ['0' => '虚拟', '1' => '实物'],'1')
			->addRadio('is_show', '是否显示','', ['0' => '不显示', '1' => '显示'],'1')
			->addText('weight', '权重','必须为非负整数','0')
			//->isAjax(false) //默认为ajax的post提交
			->fetch();
	}
	//修改淘客分类
	public function edit($id='')
	{
		//判断是否为post请求
		if (Request::instance()->isPost()) {
			//获取请求的post数据
			$data=input('post.');
			//数据输入验证
			
			//数据处理
			$update=array();
			$update['id']=$data['id'];
			$update['title']=$data['title'];
			$update['pic']=$data['pic'];
			$update['remark']=$data['remark'];
			$update['red_price']=$data['red_price'];
			$update['score']=$data['score'];
			$update['cash']=$data['cash'];
			$update['stock']=$data['stock'];
			$update['weight']=$data['weight'];
			$update['p_type']=$data['p_type'];
			$update['is_show']=$data['is_show'];
			//数据入库
			$rt=Db::name("exchange_product")->update($update);
			//跳转
			if($rt!==false){
				return $this->success('返利商品成功','index','',1);
	        } else {
	            return $this->error('返利商品失败');
	        }
		}
		if($id>0){
			$exchange_product=db('exchange_product')->find($id);
			// 使用ZBuilder快速创建表单
			return ZBuilder::make('form')
				->setPageTitle('添加商品') // 设置页面标题
				->setPageTips('请认真填写相关信息') // 设置页面提示信息
				//->setUrl('add') // 设置表单提交地址
				//->hideBtn(['back']) //隐藏默认按钮
				->setBtnTitle('submit', '确定') //修改默认按钮标题
				->addBtn('<button type="reset" class="btn btn-default">重置</button>') //添加额外按钮
				->addText('title', '商品名称','请最好不要超过20个汉字',$exchange_product['title'])
				->addImage('pic', '商品图片','',$exchange_product['pic'])
				->addTextarea('remark', '商品描述','请最好不要超过100个汉字',$exchange_product['remark'])
				->addText('red_price', '红包','',$exchange_product['red_price'])
				->addText('score', '积分','',$exchange_product['score'])
				->addText('cash', '现金','',$exchange_product['cash'])
				->addText('stock', '库存量','',$exchange_product['stock'])
				->addRadio('p_type', '类型','', ['0' => '虚拟', '1' => '实物'],$exchange_product['p_type'])
				->addRadio('is_show', '是否显示','', ['0' => '不显示', '1' => '显示'],$exchange_product['is_show'])
				->addText('weight', '权重','必须为非负整数',$exchange_product['weight'])
				->addHidden('id',$exchange_product['id'])
				//->isAjax(false) //默认为ajax的post提交
				->fetch();
		}
	}
}
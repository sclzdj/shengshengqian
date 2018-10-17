<?php
namespace app\taoke\admin;

use app\admin\controller\Admin;
use think\Db;
use think\Request;
use think\Validate;
use app\common\builder\ZBuilder; // 引入ZBuilder

/**
 * Taobaoitemclass 后台模块
 */
class Taobaoitemclass extends Admin
{
	//银行管理
	public function index()
	{
		// 读取银行数据
		$data_list = Db::name('taobao_itemclass')->order('weight asc,id asc')->select();
		$data_list=myRuleCategory($data_list);
		foreach ($data_list as $k => $v) {
			$data_list[$k]['name']=str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $v['level']-1).$v['name'];
		}
		// 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
        	->setPageTitle('淘客产品分类列表') // 设置页面标题
        	->setPageTips('删除可能会导致其他的相关数据失效，请谨慎操作') // 设置页面提示信息
        	->addColumns([
        			['id', 'ID'],
        			['name', '分类名称'],
        			['pic', '分类图片','picture','无图'],
        			['level', '级别'],
        			['weight', '权重'],
        			['is_show', '是否显示', 'status', '', ['否', '是']],
        			['right_button', '操作', 'btn'],
        		]) //添加多列数据
        	->addRightButtons(['custom'=>['title'=>'添加子分类','icon'=>'fa fa-fw fa-plus','href'=>url('addchild',['id'=>'__ID__'])],'edit','delete'=>['table'=>'taobao_itemclass']]) // 批量添加右侧按钮
    		->addTopButtons(['add','delete'=>['table'=>'taobao_itemclass']]) // 批量添加顶部按钮
        	->setRowList($data_list) // 设置表格数据
        	->fetch();
	}
	//添加分类
	public function add()
	{
		//判断是否为post请求
		if (Request::instance()->isPost()) {
			//获取请求的post数据
			$data=input('post.');
			//数据输入验证
			$validate = new Validate([
			    'name|淘客分类名称'  => 'require|length:1,50',
			]);
			if (!$validate->check($data)) {
			    return $this->error($validate->getError());
			}
			if(!($data['weight']=='0' || preg_match("/^[1-9]\d*$/",$data['weight']))){
				return $this->error('权重必须为非负整数');
			}
			//数据处理
			$insert=array();
			$insert['parent_id']=(int)$data['parent_id'];
			$insert['name']=$data['name'];
			$insert['pic']=$data['pic'];
			$insert['is_show']=$data['is_show'];
			$insert['weight']=$data['weight'];
			//数据入库
			$taobao_itemclass_id=Db::name("taobao_itemclass")->insert($insert);
			//跳转
			if($taobao_itemclass_id>0){
				return $this->success('添加淘客分类成功','index','',1);
	        } else {
	            return $this->error('添加淘客分类失败');
	        }
		}
		//选择银行下拉框数据
		$taobao_itemclass=db('taobao_itemclass')->field('id,name,parent_id')->order('weight asc')->select();
		$taobao_itemclass=myRuleCategory($taobao_itemclass);
		$select_class=[];
		foreach ($taobao_itemclass as $k => $v) {
			$select_class[$v['id']]=str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $v['level']-1).$v['name'];
		}
		// 使用ZBuilder快速创建表单
		return ZBuilder::make('form')
			->setPageTitle('添加淘客分类') // 设置页面标题
			->setPageTips('请认真填写相关信息') // 设置页面提示信息
			//->setUrl('add') // 设置表单提交地址
			//->hideBtn(['back']) //隐藏默认按钮
			->setBtnTitle('submit', '确定') //修改默认按钮标题
			->addBtn('<button type="reset" class="btn btn-default">重置</button>') //添加额外按钮
			->addSelect('parent_id', '父级分类', '不选代表顶级分类', $select_class)
			->addText('name', '淘客分类名称','请最好不要超过20个汉字')
			->addImage('pic', '分类图片')
			->addRadio('is_show', '是否显示','', ['0' => '不显示', '1' => '显示'],'1')
			->addText('weight', '权重','必须为非负整数','0')
			//->isAjax(false) //默认为ajax的post提交
			->fetch();
	}
	//添加子分类
	public function addchild($id="")
	{
		//判断是否为post请求
		if (Request::instance()->isPost()) {
			//获取请求的post数据
			$data=input('post.');
			//数据输入验证
			$validate = new Validate([
			    'name|淘客分类名称'  => 'require|length:1,50',
			]);
			if (!$validate->check($data)) {
			    return $this->error($validate->getError());
			}
			if(!($data['weight']=='0' || preg_match("/^[1-9]\d*$/",$data['weight']))){
				return $this->error('权重必须为非负整数');
			}
			//数据处理
			$insert=array();
			$insert['parent_id']=(int)$data['parent_id'];
			$insert['name']=$data['name'];
			$insert['pic']=$data['pic'];
			$insert['is_show']=$data['is_show'];
			$insert['weight']=$data['weight'];
			//数据入库
			$taobao_itemclass_id=Db::name("taobao_itemclass")->insert($insert);
			//跳转
			if($taobao_itemclass_id>0){
				return $this->success('添加淘客分类成功','index','',1);
	        } else {
	            return $this->error('添加淘客分类失败');
	        }
		}
		if($id>0){
			//选择银行下拉框数据
			$taobao_itemclass=db('taobao_itemclass')->field('id,name,parent_id')->order('weight asc')->select();
			$taobao_itemclass=myRuleCategory($taobao_itemclass);
			$select_class=[];
			foreach ($taobao_itemclass as $k => $v) {
				$select_class[$v['id']]=str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $v['level']-1).$v['name'];
			}
			// 使用ZBuilder快速创建表单
			return ZBuilder::make('form')
				->setPageTitle('添加淘客分类') // 设置页面标题
				->setPageTips('请认真填写相关信息') // 设置页面提示信息
				//->setUrl('add') // 设置表单提交地址
				//->hideBtn(['back']) //隐藏默认按钮
				->setBtnTitle('submit', '确定') //修改默认按钮标题
				->addBtn('<button type="reset" class="btn btn-default">重置</button>') //添加额外按钮
				->addSelect('parent_id', '父级分类', '不选代表顶级分类', $select_class,$id)
				->addText('name', '淘客分类名称','请最好不要超过20个汉字')
				->addImage('pic', '分类图片')
				->addRadio('is_show', '是否显示','', ['0' => '不显示', '1' => '显示'],'1')
				->addText('weight', '权重','必须为非负整数','0')
				//->isAjax(false) //默认为ajax的post提交
				->fetch();
			}
	}
	//修改淘客分类
	public function edit($id='')
	{
		//判断是否为post请求
		if (Request::instance()->isPost()) {
			//获取请求的post数据
			$data=input('post.');
			//数据输入验证
			$validate = new Validate([
			    'name|淘客分类名称'  => 'require|length:1,50',
			]);
			if (!$validate->check($data)) {
			    return $this->error($validate->getError());
			}
			if(!($data['weight']=='0' || preg_match("/^[1-9]\d*$/",$data['weight']))){
				return $this->error('权重必须为非负整数');
			}
			//数据处理
			$update=array();
			$update['id']=$data['id'];
			$update['parent_id']=(int)$data['parent_id'];
			$update['name']=$data['name'];
			$update['pic']=$data['pic'];
			$update['is_show']=$data['is_show'];
			$update['weight']=$data['weight'];
			//数据入库
			$rt=Db::name("taobao_itemclass")->update($update);
			//跳转
			if($rt!==false){
				return $this->success('修改淘客分类成功','index','',1);
	        } else {
	            return $this->error('修改淘客分类失败');
	        }
		}
		if($id>0){
			//选择分类下拉框数据
			$class=db('taobao_itemclass')->find($id);
			$taobao_itemclass=db('taobao_itemclass')->field('id,name,parent_id')->order('weight asc')->select();
			$taobao_itemclass=myRuleCategory($taobao_itemclass);
			$select_class=[];
			foreach ($taobao_itemclass as $k => $v) {
				$select_class[$v['id']]=str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $v['level']-1).$v['name'];
			}
			// 使用ZBuilder快速创建表单
			return ZBuilder::make('form')
				->setPageTitle('添加淘客分类') // 设置页面标题
				->setPageTips('请认真填写相关信息') // 设置页面提示信息
				//->setUrl('add') // 设置表单提交地址
				//->hideBtn(['back']) //隐藏默认按钮
				->setBtnTitle('submit', '确定') //修改默认按钮标题
				->addBtn('<button type="reset" class="btn btn-default">重置</button>') //添加额外按钮
				->addSelect('parent_id', '父级分类', '不选代表顶级分类', $select_class,$class['parent_id'])
				->addText('name', '淘客分类名称','请最好不要超过20个汉字',$class['name'])
				->addImage('pic', '分类图片','',$class['pic'])
				->addRadio('is_show', '是否显示','', ['0' => '不显示', '1' => '显示'],$class['is_show'])
				->addText('weight', '权重','必须为非负整数',$class['weight'])
				->addHidden('id',$class['id'])
				//->isAjax(false) //默认为ajax的post提交
				->fetch();
		}
	}
}
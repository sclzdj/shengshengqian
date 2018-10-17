<?php
namespace app\taoke\admin;

use app\admin\controller\Admin;
use think\Db;
use think\Request;
use think\Validate;
use app\common\builder\ZBuilder; // 引入ZBuilder

/**
 * Fanliclass 后台模块
 */
class Fanliclass extends Admin
{
	//银行管理
	public function index()
	{
		// 读取银行数据
		$data_list = Db::name('fanli_class')->order('id asc')->select();
		// 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
        	->setPageTitle('返利分类列表') // 设置页面标题
        	->setPageTips('删除可能会导致其他的相关数据失效，请谨慎操作') // 设置页面提示信息
        	->addColumns([
        			['id', 'ID'],
        			['name', '分类名称'],
        			['is_show', '是否显示', 'status', '', ['否', '是']],
        			['right_button', '操作', 'btn'],
        		]) //添加多列数据
        	->addRightButtons(['edit','delete'=>['table'=>'fanli_class']]) // 批量添加右侧按钮
    		->addTopButtons(['add','delete'=>['table'=>'fanli_class']]) // 批量添加顶部按钮
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
			//数据处理
			$insert=array();
			$insert['name']=$data['name'];
			$insert['is_show']=$data['is_show'];
			//数据入库
			$fanli_class_id=Db::name("fanli_class")->insert($insert);
			//跳转
			if($fanli_class_id>0){
				return $this->success('添加淘客分类成功','index','',1);
	        } else {
	            return $this->error('添加淘客分类失败');
	        }
		}
		// 使用ZBuilder快速创建表单
		return ZBuilder::make('form')
			->setPageTitle('添加淘客分类') // 设置页面标题
			->setPageTips('请认真填写相关信息') // 设置页面提示信息
			//->setUrl('add') // 设置表单提交地址
			//->hideBtn(['back']) //隐藏默认按钮
			->setBtnTitle('submit', '确定') //修改默认按钮标题
			->addBtn('<button type="reset" class="btn btn-default">重置</button>') //添加额外按钮
			->addText('name', '淘客分类名称','请最好不要超过20个汉字')
			->addRadio('is_show', '是否显示','', ['0' => '不显示', '1' => '显示'],'1')
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
			$validate = new Validate([
			    'name|淘客分类名称'  => 'require|length:1,50',
			]);
			if (!$validate->check($data)) {
			    return $this->error($validate->getError());
			}
			//数据处理
			$update=array();
			$update['id']=$data['id'];
			$update['name']=$data['name'];
			$update['is_show']=$data['is_show'];
			//数据入库
			$rt=Db::name("fanli_class")->update($update);
			//跳转
			if($rt!==false){
				return $this->success('修改淘客分类成功','index','',1);
	        } else {
	            return $this->error('修改淘客分类失败');
	        }
		}
		if($id>0){
			//选择分类下拉框数据
			$class=db('fanli_class')->find($id);
			// 使用ZBuilder快速创建表单
			return ZBuilder::make('form')
				->setPageTitle('修改淘客分类') // 设置页面标题
				->setPageTips('请认真填写相关信息') // 设置页面提示信息
				//->setUrl('add') // 设置表单提交地址
				//->hideBtn(['back']) //隐藏默认按钮
				->setBtnTitle('submit', '确定') //修改默认按钮标题
				->addBtn('<button type="reset" class="btn btn-default">重置</button>') //添加额外按钮
				->addText('name', '淘客分类名称','请最好不要超过20个汉字',$class['name'])
				->addRadio('is_show', '是否显示','', ['0' => '不显示', '1' => '显示'],$class['is_show'])
				->addHidden('id',$class['id'])
				//->isAjax(false) //默认为ajax的post提交
				->fetch();
		}
	}
}
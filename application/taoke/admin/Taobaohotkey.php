<?php
namespace app\taoke\admin;

use app\admin\controller\Admin;
use think\Db;
use think\Request;
use think\Validate;
use app\common\builder\ZBuilder; // 引入ZBuilder

/**
 * Taobaohotkey 后台模块
 */
class Taobaohotkey extends Admin
{
	//淘客热词管理
	public function index()
	{
		// 读取银行数据
		$data_list = Db::name('taobao_hotkey')->order('num desc,id asc')->select();
		// 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
        	->setPageTitle('淘客热词列表') // 设置页面标题
        	->setPageTips('删除可能会导致其他的相关数据失效，请谨慎操作') // 设置页面提示信息
        	->addColumns([
        			['id', 'ID'],
        			['keyword', '淘客热词'],
        			['num', '搜索数量'],
        			['right_button', '操作', 'btn'],
        		]) //添加多列数据
        	->addRightButtons(['edit','delete'=>['table'=>'taobao_hotkey']]) // 批量添加右侧按钮
    		->addTopButtons(['add','delete'=>['table'=>'taobao_hotkey']]) // 批量添加顶部按钮
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
			    'keyword|淘客热词'  => 'require|length:1,50',
			]);
			if (!$validate->check($data)) {
			    return $this->error($validate->getError());
			}
			if(!($data['num']=='0' || preg_match("/^[1-9]\d*$/",$data['num']))){
				return $this->error('搜索数量必须为非负整数');
			}
			//数据处理
			$insert=array();
			$insert['keyword']=$data['keyword'];
			$insert['num']=$data['num'];
			//数据入库
			$taobao_hotkey_id=Db::name("taobao_hotkey")->insert($insert);
			//跳转
			if($taobao_hotkey_id>0){
				return $this->success('添加淘客热词成功','index','',1);
	        } else {
	            return $this->error('添加淘客热词失败');
	        }
		}
		// 使用ZBuilder快速创建表单
		return ZBuilder::make('form')
			->setPageTitle('添加淘客热词') // 设置页面标题
			->setPageTips('请认真填写相关信息') // 设置页面提示信息
			//->setUrl('add') // 设置表单提交地址
			//->hideBtn(['back']) //隐藏默认按钮
			->setBtnTitle('submit', '确定') //修改默认按钮标题
			->addText('keyword', '淘客分类名称','请最好不要超过20个汉字')
			->addText('num', '搜索数量','必须为非负整数','0')
			//->isAjax(false) //默认为ajax的post提交
			->fetch();
	}
	//修改淘客热词
	public function edit($id='')
	{
		//判断是否为post请求
		if (Request::instance()->isPost()) {
			//获取请求的post数据
			$data=input('post.');
			//数据输入验证
			$validate = new Validate([
			    'keyword|淘客热词'  => 'require|length:1,50',
			]);
			if (!$validate->check($data)) {
			    return $this->error($validate->getError());
			}
			if(!($data['num']=='0' || preg_match("/^[1-9]\d*$/",$data['num']))){
				return $this->error('搜索数量必须为非负整数');
			}
			//数据处理
			$update=array();
			$update['id']=$data['id'];
			$update['keyword']=$data['keyword'];
			$update['num']=$data['num'];
			//数据入库
			$rt=Db::name("taobao_hotkey")->update($update);
			//跳转
			if($rt!==false){
				return $this->success('修改淘客热词成功','index','',1);
	        } else {
	            return $this->error('修改淘客热词失败');
	        }
		}
		if($id>0){
			//选择分类下拉框数据
			$taobao_hotkey=db('taobao_hotkey')->find($id);
			// 使用ZBuilder快速创建表单
			return ZBuilder::make('form')
				->setPageTitle('添加淘客热词') // 设置页面标题
				->setPageTips('请认真填写相关信息') // 设置页面提示信息
				//->setUrl('add') // 设置表单提交地址
				//->hideBtn(['back']) //隐藏默认按钮
				->setBtnTitle('submit', '确定') //修改默认按钮标题
				->addBtn('<button type="reset" class="btn btn-default">重置</button>') //添加额外按钮
				->addText('keyword', '淘客分类名称','请最好不要超过20个汉字',$taobao_hotkey['keyword'])
				->addText('num', '搜索数量','必须为非负整数',$taobao_hotkey['num'])
				->addHidden('id',$taobao_hotkey['id'])
				//->isAjax(false) //默认为ajax的post提交
				->fetch();
		}
	}
}
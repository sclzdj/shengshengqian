<?php
namespace app\taoke\admin;

use app\admin\controller\Admin;
use think\Db;
use think\Request;
use think\Validate;
use app\common\builder\ZBuilder; // 引入ZBuilder

/**
 * Withdrawalscard 后台模块
 */
class Withdrawalscard extends Admin
{
	public function index()
	{
		$data_list = Db::name('withdrawals_card')->order('id asc')->select();
		// 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
        	->setPageTitle('提现卡列表') // 设置页面标题
        	->setPageTips('删除可能会导致其他的相关数据失效，请谨慎操作') // 设置页面提示信息
        	->addColumns([
        			['id', 'ID'],
        			['base_price', '提现额度'],
        			['remark', '说明'],
        			['right_button', '操作', 'btn'],
        		]) //添加多列数据
        	->addRightButtons(['edit','delete'=>['table'=>'withdrawals_card']]) // 批量添加右侧按钮
    		->addTopButtons(['add','delete'=>['table'=>'withdrawals_card']]) // 批量添加顶部按钮
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
			if(!preg_match("/^\d+(\.\d+)?$/",$data['base_price'])){
				return $this->error('提现卡额度格式错误');
			}
			//数据处理
			$insert=array();
			$insert['base_price']=$data['base_price'];
			$insert['remark']=$data['remark'];
			//数据入库
			$withdrawals_card_id=Db::name("withdrawals_card")->insertGetId($insert);
			//跳转
			if($withdrawals_card_id>0){
				return $this->success('添加提现卡成功','index','',1);
	        } else {
	            return $this->error('添加提现卡失败');
	        }
		}
		// 使用ZBuilder快速创建表单
		return ZBuilder::make('form')
			->setPageTitle('添加提现卡') // 设置页面标题
			->setPageTips('请认真填写相关信息') // 设置页面提示信息
			//->setUrl('add') // 设置表单提交地址
			//->hideBtn(['back']) //隐藏默认按钮
			->setBtnTitle('submit', '确定') //修改默认按钮标题
			->addBtn('<button type="reset" class="btn btn-default">重置</button>') //添加额外按钮
			->addText('base_price', '提现卡额度','')
			->addTextarea('remark', '说明')
			//->isAjax(false) //默认为ajax的post提交
			->fetch();
	}
	//修改提现卡
	public function edit($id='')
	{
		//判断是否为post请求
		if (Request::instance()->isPost()) {
			//获取请求的post数据
			$data=input('post.');
			//数据输入验证
			if(!preg_match("/^\d+(\.\d+)?$/",$data['base_price'])){
				return $this->error('提现卡额度格式错误');
			}
			//数据处理
			$update=array();
			$update['id']=$data['id'];
			$update['base_price']=$data['base_price'];
			$update['remark']=$data['remark'];
			//数据入库
			$rt=Db::name("withdrawals_card")->update($update);
			//跳转
			if($rt!==false){
				return $this->success('修改提现卡成功','index','',1);
	        } else {
	            return $this->error('修改提现卡失败');
	        }
		}
		if($id>0){
			//选择分类下拉框数据
			$withdrawals_card=db('withdrawals_card')->find($id);
			// 使用ZBuilder快速创建表单
			return ZBuilder::make('form')
				->setPageTitle('添加提现卡') // 设置页面标题
				->setPageTips('请认真填写相关信息') // 设置页面提示信息
				//->setUrl('add') // 设置表单提交地址
				//->hideBtn(['back']) //隐藏默认按钮
				->setBtnTitle('submit', '确定') //修改默认按钮标题
				->addBtn('<button type="reset" class="btn btn-default">重置</button>') //添加额外按钮
				->addText('base_price', '提现卡额度','',$withdrawals_card['base_price'])
				->addTextarea('remark', '说明','',$withdrawals_card['remark'])
				->addHidden('id',$withdrawals_card['id'])
				//->isAjax(false) //默认为ajax的post提交
				->fetch();
		}
	}
}
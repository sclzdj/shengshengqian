<?php
namespace app\taoke\admin;

use app\admin\controller\Admin;
use think\Db;
use think\Request;
use think\Validate;
use app\common\builder\ZBuilder; // 引入ZBuilder

/**
 * Apploaderpic 后台模块
 */
class Apploaderpic extends Admin
{
	//APP启动广告管理
	public function index()
	{
		// 获取排序
        $order = $this->getOrder();
        if($order===''){
            $order='weight asc,id asc';
        }
        // 获取筛选
        $map = $this->getMap();
		// 读取用户数据
		$data_list = Db::name('apploader_pic')->where($map)->order($order)->paginate();
		// 分页数据
		$page = $data_list->render();
		// 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
        	->setPageTitle('APP启动广告列表') // 设置页面标题
        	->setPageTips('删除可能会导致其他的相关数据失效，请谨慎操作') // 设置页面提示信息
        	->addOrder('id,start_time,end_time,weight,click') // 添加排序
            ->setSearch(['id' => 'ID','name' => '标题']) // 设置搜索参数
        	->addColumns([
        			['id', 'ID'],
        			['name', '标题', 'link', url('link', ['id' => '__id__'])],
        			['start_time', '开始时间','datetime'],
        			['end_time', '结束时间','datetime'],
        			['pic', '图片','pic'],
        			['click', '点击次数'],
        			['is_show', '状态','','status',['隐藏','显示']],
        			['mid', '商户ID'],
        			['agent_id', '代理ID'],
        			['right_button', '操作', 'btn'],
        		]) //添加多列数据
        	->addRightButtons(['edit','delete'=>['table'=>'apploader_pic']]) // 批量添加右侧按钮
    		->addTopButtons(['add','delete'=>['table'=>'apploader_pic']]) // 批量添加顶部按钮
        	->setRowList($data_list) // 设置表格数据
        	->fetch();
	}
	public function link(){
		$id=input('id');
		$apploader_pic = db('apploader_pic')->where('id',$id)->field('href')->find();
		if($apploader_pic['href']!='') $str='<script>location.href="'.$apploader_pic['href'].'";</script>';
        else $str="<script>alert('无广告地址');javascript:window.opener=null;window.open('','_self');window.close();</script>";
		echo $str;
	}
	public function add()
	{
		//判断是否为post请求
		if (Request::instance()->isPost()) {
			//获取请求的post数据
			$data=input('post.');
			//数据输入验证
			$validate = new Validate([
			    'name|APP启动广告标题'  => 'require|length:1,100',
			    'pic|APP启动广告图片' => 'require',
			    'href|APP启动广告链接' => 'require',
			    'start_time|APP启动广告开始时间' => 'require',
			    'end_time|APP启动广告结束时间' => 'require',
			]);
			if (!$validate->check($data)) {
			    return $this->error($validate->getError());
			}
			$data['start_time']=strtotime($data['start_time'].':00');
			$data['end_time']=strtotime($data['end_time'].':59');
			if($data['end_time']<=$data['start_time']){
				return $this->error('借宿日期必须大于开始日期');
			}
			if(!preg_match("/^\d+$/",$data['click'])){
				return $this->error('点击量必须为正整数');
			}
			if(!preg_match("/^\d+$/",$data['weight'])){
				return $this->error('权重必须为正整数');
			}
			//数据处理
			$insert=array();
			$insert['name']=$data['name'];
			$insert['start_time']=$data['start_time'];
			$insert['end_time']=$data['end_time'];
			$insert['pic']=$data['pic'];
			$insert['href']=$data['href'];
			$insert['click']=$data['click'];
			$insert['is_show']=$data['is_show'];
			$insert['weight']=$data['weight'];
			//数据入库
			$apploader_pic_id=Db::name("apploader_pic")->insert($insert);
			//跳转
			if($apploader_pic_id>0){
				return $this->success('添加APP启动广告成功','index','',1);
	        } else {
	            return $this->error('添加APP启动广告失败');
	        }
		}
		// 使用ZBuilder快速创建表单
		return ZBuilder::make('form')
			->setPageTitle('添加APP启动广告') // 设置页面标题
			->setPageTips('请认真填写相关信息') // 设置页面提示信息
			//->setUrl('add') // 设置表单提交地址
			//->hideBtn(['back']) //隐藏默认按钮
			->setBtnTitle('submit', '确定') //修改默认按钮标题
			->addText('name', 'APP启动广告标题','请最好不要超过30个汉字')
			->addDatetime('start_time', 'APP启动广告开始时间','')
			->addDatetime('end_time', 'APP启动广告结束时间','')
			->addImage('pic', 'APP启动广告图片','')
			->addText('href', 'APP启动广告连接地址','例如：http://www.baidu.com')
			->addText('click', 'APP启动广告点击量','必须为整数','0')
			->addRadio('is_show', '状态','',['0'=>'隐藏','1'=>'显示'],'1')
			->addText('weight', '权重','必须为整数','0')
			//->isAjax(false) //默认为ajax的post提交
			->fetch();
	}
	//修改APP启动广告
	public function edit($id='')
	{
		//判断是否为post请求
		if (Request::instance()->isPost()) {
			//获取请求的post数据
			$data=input('post.');
			//数据输入验证
			$validate = new Validate([
			    'name|APP启动广告标题'  => 'require|length:1,100',
			    'pic|APP启动广告图片' => 'require',
			    'href|APP启动广告链接' => 'require',
			    'start_time|APP启动广告开始时间' => 'require',
			    'end_time|APP启动广告结束时间' => 'require',
			]);
			if (!$validate->check($data)) {
			    return $this->error($validate->getError());
			}
			$data['start_time']=strtotime($data['start_time'].':00');
			$data['end_time']=strtotime($data['end_time'].':59');
			if($data['end_time']<=$data['start_time']){
				return $this->error('借宿日期必须大于开始日期');
			}
			if(!preg_match("/^\d+$/",$data['click'])){
				return $this->error('点击量必须为正整数');
			}
			if(!preg_match("/^\d+$/",$data['weight'])){
				return $this->error('权重必须为正整数');
			}
			//数据处理
			$update=array();
			$update['id']=$data['id'];
			$update['name']=$data['name'];
			$update['start_time']=$data['start_time'];
			$update['end_time']=$data['end_time'];
			$update['pic']=$data['pic'];
			$update['href']=$data['href'];
			$update['click']=$data['click'];
			$update['is_show']=$data['is_show'];
			$update['weight']=$data['weight'];
			//数据入库
			$rt=Db::name("apploader_pic")->update($update);
			//跳转
			if($rt!==false){
				return $this->success('修改APP启动广告成功','index','',1);
	        } else {
	            return $this->error('修改APP启动广告失败');
	        }
		}
		if($id>0){
			//选择分类下拉框数据
			$apploader_pic=db('apploader_pic')->find($id);
			// 使用ZBuilder快速创建表单
			return ZBuilder::make('form')
				->setPageTitle('添加APP启动广告') // 设置页面标题
				->setPageTips('请认真填写相关信息') // 设置页面提示信息
				//->setUrl('add') // 设置表单提交地址
				//->hideBtn(['back']) //隐藏默认按钮
				->setBtnTitle('submit', '确定') //修改默认按钮标题
				->addBtn('<button type="reset" class="btn btn-default">重置</button>') //添加额外按钮
				->addText('name', 'APP启动广告标题','请最好不要超过30个汉字',$apploader_pic['name'])
				->addDatetime('start_time', 'APP启动广告开始时间','',date('Y-m-d H:i',$apploader_pic['start_time']))
				->addDatetime('end_time', 'APP启动广告结束时间','',date('Y-m-d H:i',$apploader_pic['end_time']))
				->addImage('pic', 'APP启动广告图片','',$apploader_pic['pic'])
				->addText('href', 'APP启动广告连接地址','例如：http://www.baidu.com',$apploader_pic['href'])
				->addText('click', 'APP启动广告点击量','必须为整数',$apploader_pic['click'])
				->addRadio('is_show', '状态','',['0'=>'隐藏','1'=>'显示'],$apploader_pic['is_show'])
				->addText('weight', '权重','必须为整数',$apploader_pic['weight'])
				->addHidden('id',$apploader_pic['id'])
				//->isAjax(false) //默认为ajax的post提交
				->fetch();
		}
	}
}
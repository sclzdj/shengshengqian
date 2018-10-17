<?php
namespace app\taoke\admin;

use app\admin\controller\Admin;
use think\Db;
use think\Request;
use think\Validate;
use app\common\builder\ZBuilder; // 引入ZBuilder

/**
 * Fanlishop 后台模块
 */
class Fanlishop extends Admin
{
	//银行管理
	public function index()
	{
		// 获取排序
        $order = $this->getOrder();
        if($order===''){
            $order='a.weight asc,a.created desc,a.id desc';
        }
        // 获取筛选
        $map = $this->getMap();
        if(isset($map['a.created'])){
            $map['a.created'][1][0]=strtotime($map['a.created'][1][0]);
            $map['a.created'][1][1]=strtotime($map['a.created'][1][1]);
        }
		// 读取银行数据
		$data_list = Db::name('fanli_shop a')->join('fanli_class b','a.class_id=b.id','LEFT')->field('a.*,b.name as class_name')->where($map)->order($order)->paginate();
		// 分页数据
		$page = $data_list->render();
		// 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
        	->setPageTitle('返利商品列表') // 设置页面标题
        	->setPageTips('删除可能会导致其他的相关数据失效，请谨慎操作') // 设置页面提示信息
        	->addOrder('a.id,a.created') // 添加排序
            ->addTimeFilter('a.created') // 添加时间段筛选
            ->setSearch(['a.id' => 'ID','a.name'=>'名称']) // 设置搜索参数
        	->addColumns([
        			['id', 'ID'],
        			['name', '商品名称', 'link', url('link', ['id' => '__id__'])],
        			['ico','图标','picture'],
        			['class_name','分类名称'],
        			['label','描述'],
        			['is_recommend', '是否推荐', 'status', '', ['否', '是']],
        			['weight', '权重'],
        			['is_show', '是否显示', 'status', '', ['否', '是']],
        			['created', '添加时间','datetime','未知'],
        			['right_button', '操作', 'btn'],
        		]) //添加多列数据
        	->addRightButtons(['edit','delete'=>['table'=>'fanli_shop']]) // 批量添加右侧按钮
    		->addTopButtons(['add','delete'=>['table'=>'fanli_shop']]) // 批量添加顶部按钮
        	->setRowList($data_list) // 设置表格数据
        	->setPages($page) // 设置分页数据
        	->fetch();
	}
	public function link(){
		$id=input('id');
		$fanli_shop = Db::name('fanli_shop')->where('id',$id)->find();
		if($fanli_shop['url']!='') $str='<script>location.href="'.$fanli_shop['url'].'";</script>';
        else $str="<script>alert('无商品地址');javascript:window.opener=null;window.open('','_self');window.close();</script>";
		echo $str;
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
			$insert['name']=$data['name'];
			$insert['class_id']=$data['class_id'];
			$insert['ico']=$data['ico'];
			$insert['label']=$data['label'];
			$insert['is_recommend']=$data['is_recommend'];
			$insert['weight']=$data['weight'];
			$insert['is_show']=$data['is_show'];
			$insert['created']=time();
			$insert['agent_id']='1';
			//数据入库
			$fanli_shop_id=Db::name("fanli_shop")->insert($insert);
			//跳转
			if($fanli_shop_id>0){
				return $this->success('添加淘客商品成功','index','',1);
	        } else {
	            return $this->error('添加淘客商品失败');
	        }
		}
		//选择分类下拉框数据
		$fanli_class=db('fanli_class')->field('id,name')->order('id asc')->select();
		$select_class=array();
		foreach ($fanli_class as $k => $v) {
			$select_class[$v['id']]=$v['name'];
		}
		// 使用ZBuilder快速创建表单
		return ZBuilder::make('form')
			->setPageTitle('添加返利商品') // 设置页面标题
			->setPageTips('请认真填写相关信息') // 设置页面提示信息
			//->setUrl('add') // 设置表单提交地址
			//->hideBtn(['back']) //隐藏默认按钮
			->setBtnTitle('submit', '确定') //修改默认按钮标题
			->addBtn('<button type="reset" class="btn btn-default">重置</button>') //添加额外按钮
			->addText('name', '返利商品名称','请最好不要超过20个汉字')
			->addSelect('class_id', '返利商品分类', '', $select_class)
			->addImage('ico', '返利商品图标','')
			->addTextarea('label', '返利商品描述','请最好不要超过100个汉字')
			->addRadio('is_recommend', '是否推荐','', ['0' => '不推荐', '1' => '推荐'],'0')
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
			$update['name']=$data['name'];
			$update['class_id']=$data['class_id'];
			$update['ico']=$data['ico'];
			$update['label']=$data['label'];
			$update['is_recommend']=$data['is_recommend'];
			$update['weight']=$data['weight'];
			$update['is_show']=$data['is_show'];
			$update['created']=time();
			//数据入库
			$rt=Db::name("fanli_shop")->update($update);
			//跳转
			if($rt!==false){
				return $this->success('修改返利商品成功','index','',1);
	        } else {
	            return $this->error('修改返利商品失败');
	        }
		}
		if($id>0){
			//选择分类下拉框数据
			$fanli_class=db('fanli_class')->field('id,name')->order('id asc')->select();
			$select_class=array();
			foreach ($fanli_class as $k => $v) {
				$select_class[$v['id']]=$v['name'];
			}
			$fanli_shop=db('fanli_shop')->find($id);
			// 使用ZBuilder快速创建表单
			return ZBuilder::make('form')
				->setPageTitle('添加返利商品') // 设置页面标题
				->setPageTips('请认真填写相关信息') // 设置页面提示信息
				//->setUrl('add') // 设置表单提交地址
				//->hideBtn(['back']) //隐藏默认按钮
				->setBtnTitle('submit', '确定') //修改默认按钮标题
				->addBtn('<button type="reset" class="btn btn-default">重置</button>') //添加额外按钮
				->addText('name', '返利商品名称','请最好不要超过20个汉字',$fanli_shop['name'])
				->addSelect('class_id', '返利商品分类', '', $select_class,$fanli_shop['class_id'])
				->addImage('ico', '返利商品图标','',$fanli_shop['ico'])
				->addTextarea('label', '返利商品描述','请最好不要超过100个汉字',$fanli_shop['label'])
				->addRadio('is_recommend', '是否推荐','', ['0' => '不推荐', '1' => '推荐'],$fanli_shop['is_recommend'])
				->addRadio('is_show', '是否显示','', ['0' => '不显示', '1' => '显示'],$fanli_shop['is_show'])
				->addText('weight', '权重','必须为非负整数',$fanli_shop['weight'])
				->addHidden('id',$fanli_shop['id'])
				//->isAjax(false) //默认为ajax的post提交
				->fetch();
		}
	}
}
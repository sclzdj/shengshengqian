<?php
namespace app\taoke\admin;

use app\admin\controller\Admin;
use think\Db;
use think\Request;
use think\Validate;
use app\common\builder\ZBuilder; // 引入ZBuilder

/**
 * Picad 后台模块
 */
class Picad extends Admin
{
	//页面广告管理
	public function index()
	{
		// 获取排序
        $order = $this->getOrder();
        if($order===''){
            $order='created desc,id desc';
        }
        // 获取筛选
        $map = $this->getMap();
        if(isset($map['created'])){
            $map['created'][1][0]=strtotime($map['created'][1][0]);
            $map['created'][1][1]=strtotime($map['created'][1][1]);
        }
		// 读取用户数据
		$data_list = Db::name('pic_ad')->where($map)->order($order)->paginate();
		// 分页数据
		$page = $data_list->render();
		// 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
        	->setPageTitle('页面广告列表') // 设置页面标题
        	->setPageTips('删除可能会导致其他的相关数据失效，请谨慎操作') // 设置页面提示信息
        	->addOrder('id,created') // 添加排序
            ->addTimeFilter('created') // 添加时间段筛选
            ->setSearch(['id' => 'ID','name' => '标题']) // 设置搜索参数
        	->addColumns([
        			['id', 'ID'],
        			['name', '标题', 'link', url('link', ['id' => '__id__'])],
        			['ad_key', 'ad_key'],
        			['ad_key2', 'ad_key2'],
        			['method', '打开方式'],
        			['created', '创建时间','datetime','未知'],
        			['right_button', '操作', 'btn'],
        		]) //添加多列数据
        	->addRightButtons(['edit','delete'=>['table'=>'pic_ad']]) // 批量添加右侧按钮
    		->addTopButtons(['add','delete'=>['table'=>'pic_ad']]) // 批量添加顶部按钮
        	->setRowList($data_list) // 设置表格数据
        	->setPages($page) // 设置分页数据
        	->fetch();
	}
	public function link(){
		$id=input('id');
		$pic_ad = db('pic_ad')->where('id',$id)->find();
		if($pic_ad['target']!=''){
			if($pic_ad['target_param']!=''){
				if(strpos($pic_ad['target'],'?')===false)
					$href=$pic_ad['target'].'?'.http_build_query(json_decode($pic_ad['target_param'],true));
				else
					$href=$pic_ad['target'].'&'.http_build_query(json_decode($pic_ad['target_param'],true));
			}else{
				$href=$pic_ad['target'];
			}
			$str='<script>location.href="'.$href.'";</script>';
		}
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
			    'name|页面广告标题'  => 'require|length:1,100',
			    'pic|页面广告图片' => 'require',
			    'target|页面广告链接' => 'require',
			    'method|页面广告打开方式' => 'require',
			]);
			if (!$validate->check($data)) {
			    return $this->error($validate->getError());
			}
			//数据处理
			$insert=array();
			$insert['name']=$data['name'];
			$insert['ad_key']=$data['ad_key'];
			$insert['ad_key2']=$data['ad_key2'];
			$insert['method']=$data['method'];
			$insert['target']=$data['target'];
			$insert['target_param']=$data['target_param'];
			$insert['pic']=$data['pic'];
			$insert['created']=time();
			//数据入库
			$pic_ad_id=Db::name("pic_ad")->insert($insert);
			//跳转
			if($pic_ad_id>0){
				return $this->success('添加页面广告成功','index','',1);
	        } else {
	            return $this->error('添加页面广告失败');
	        }
		}
		// 使用ZBuilder快速创建表单
		return ZBuilder::make('form')
			->setPageTitle('添加页面广告') // 设置页面标题
			->setPageTips('请认真填写相关信息') // 设置页面提示信息
			//->setUrl('add') // 设置表单提交地址
			//->hideBtn(['back']) //隐藏默认按钮
			->setBtnTitle('submit', '确定') //修改默认按钮标题
			->addText('name', '页面广告标题','请最好不要超过30个汉字')
			->addImage('pic', '页面广告图片','')
			->addText('ad_key', 'ad_key','')
			->addText('ad_key2', 'ad_key2','')
			->addSelect('method', '页面广告打开方式','',['app.webview'=>'app.webview','app.view'=>'app.view'])
			->addText('target', '页面广告连接地址','例如：http://www.baidu.com')
			->addText('target_param', '链接参数','json数据，如：{"id":2,"name":"省道"}','')
			//->isAjax(false) //默认为ajax的post提交
			->fetch();
	}
	//修改页面广告
	public function edit($id='')
	{
		//判断是否为post请求
		if (Request::instance()->isPost()) {
			//获取请求的post数据
			$data=input('post.');
			//数据输入验证
			$validate = new Validate([
			    'name|页面广告标题'  => 'require|length:1,100',
			    'pic|页面广告图片' => 'require',
			    'target|页面广告链接' => 'require',
			    'method|页面广告打开方式' => 'require',
			]);
			if (!$validate->check($data)) {
			    return $this->error($validate->getError());
			}
			//数据处理
			$update=array();
			$update['id']=$data['id'];
			$update['name']=$data['name'];
			$update['ad_key']=$data['ad_key'];
			$update['ad_key2']=$data['ad_key2'];
			$update['method']=$data['method'];
			$update['target']=$data['target'];
			$update['target_param']=$data['target_param'];
			$update['pic']=$data['pic'];
			//数据入库
			$rt=Db::name("pic_ad")->update($update);
			//跳转
			if($rt!==false){
				return $this->success('修改页面广告成功','index','',1);
	        } else {
	            return $this->error('修改页面广告失败');
	        }
		}
		if($id>0){
			//选择分类下拉框数据
			$pic_ad=db('pic_ad')->find($id);
			// 使用ZBuilder快速创建表单
			return ZBuilder::make('form')
				->setPageTitle('添加页面广告') // 设置页面标题
				->setPageTips('请认真填写相关信息') // 设置页面提示信息
				//->setUrl('add') // 设置表单提交地址
				//->hideBtn(['back']) //隐藏默认按钮
				->setBtnTitle('submit', '确定') //修改默认按钮标题
				->addBtn('<button type="reset" class="btn btn-default">重置</button>') //添加额外按钮
				->addText('name', '页面广告标题','请最好不要超过30个汉字',$pic_ad['name'])
				->addImage('pic', '页面广告图片','',$pic_ad['pic'])
				->addText('ad_key', 'ad_key','',$pic_ad['ad_key'])
				->addText('ad_key2', 'ad_key2','',$pic_ad['ad_key2'])
				->addSelect('method', '页面广告打开方式','',['app.webview'=>'app.webview','app.view'=>'app.view'],$pic_ad['method'])
				->addText('target', '页面广告连接地址','例如：http://www.baidu.com',$pic_ad['target'])
				->addText('target_param', '链接参数','json数据，如：{"id":2,"name":"省道"}',htmlspecialchars($pic_ad['target_param']))
				->addHidden('id',$pic_ad['id'])
				//->isAjax(false) //默认为ajax的post提交
				->fetch();
		}
	}
}
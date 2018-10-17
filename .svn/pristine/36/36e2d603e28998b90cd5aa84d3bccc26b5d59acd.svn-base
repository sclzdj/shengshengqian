<?php
namespace app\taoke\admin;

use app\admin\controller\Admin;
use think\Db;
use think\Request;
use think\Validate;
use app\common\builder\ZBuilder; // 引入ZBuilder

/**
 * Taobaolmrule 后台模块
 */
class Taobaolmrule extends Admin
{
	public function index()
	{
		$data_list = Db::name('taobaolm_rule')->order('id asc')->select();
		// 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
        	->setPageTitle('淘宝采集器规则列表') // 设置页面标题
        	->setPageTips('删除可能会导致其他的相关数据失效，请谨慎操作') // 设置页面提示信息
        	->addColumns([
        			['id', 'ID'],
        			['title', '规则名称'],
        			['keywords', '采集关键词'],
        			['class_name', '采集后所属分类'],
        			['num', '采集数量'],
        			['right_button', '操作', 'btn'],
        		]) //添加多列数据
        	->addRightButtons(['delete'=>['table'=>'taobaolm_rule']]) // 批量添加右侧按钮
        	->addRightButton('custom',['title'=>'查看','icon'=>'fa fa-fw fa-eye','href'=>url('look',['id'=>'__ID__'])])
    		->addTopButtons(['add','delete'=>['table'=>'taobaolm_rule']]) // 批量添加顶部按钮
        	->setRowList($data_list) // 设置表格数据
        	->fetch();
	}
	public function look(){
		$id=input('id');
		$taobaolm_rule=db('taobaolm_rule')->find($id);
		$this->redirect('taoke/taobaolm/index',['rule_title'=>$taobaolm_rule['title'],'q'=>$taobaolm_rule['keywords'],'rule_class_name'=>$taobaolm_rule['class_name'],'rule_num'=>$taobaolm_rule['num']]);
	}
	public function add()
	{
		//判断是否为post请求
		if (Request::instance()->isPost()) {
			//获取请求的post数据
			$data=input('post.');
			//数据输入验证
			$validate = new Validate([
			    'title|规则名称'  => 'require',
			    'keywords|采集关键词'  => 'require',
			    'class_name|采集后所属分类'  => 'require',
			    'num|采集数量'  => 'require',
			]);
			if (!$validate->check($data)) {
				if (Request::instance()->isAjax()) {
					die(json_encode(['code'=>201,'error'=>$validate->getError()]));
				}
			    return $this->error($validate->getError());
			}
			if(!in_array($data['num'],['100','200','300','400','500','600','700','800','900','1000'])){
				if (Request::instance()->isAjax()) {
					die(json_encode(['code'=>201,'error'=>"采集数量必须为'100','200','300','400','500','600','700','800','900','1000'"]));
				}
				return $this->error("采集数量必须为'100','200','300','400','500','600','700','800','900','1000'");
			}
			//数据处理
			$insert=array();
			$insert['title']=$data['title'];
			$insert['keywords']=$data['keywords'];
			$insert['class_name']=$data['class_name'];
			$insert['num']=$data['num'];
			//数据入库
			$taobaolm_rule_id=Db::name("taobaolm_rule")->insertGetId($insert);
			//跳转
			if($taobaolm_rule_id>0){
				if (Request::instance()->isAjax()) {
					die(json_encode(['code'=>200,'error'=>"添加淘宝采集器规则成功"]));
				}
				return $this->success('添加淘宝采集器规则成功','index','',1);
	        } else {
	        	if (Request::instance()->isAjax()) {
					die(json_encode(['code'=>202,'error'=>"添加淘宝采集器规则失败"]));
				}
	            return $this->error('添加淘宝采集器规则失败');
	        }
		}
		$taobao_itemclass=db('taobao_itemclass')->field('id,name,parent_id')->order('weight asc')->select();
        $taobao_itemclass=myRuleCategory($taobao_itemclass);
        $select_class=[];
        foreach ($taobao_itemclass as $k => $v) {
            $select_class[$v['name']]=str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $v['level']-1).$v['name'];
        }
		// 使用ZBuilder快速创建表单
		return ZBuilder::make('form')
			->setPageTitle('添加淘宝采集器规则') // 设置页面标题
			->setPageTips('请认真填写相关信息') // 设置页面提示信息
			//->setUrl('add') // 设置表单提交地址
			//->hideBtn(['back']) //隐藏默认按钮
			->setBtnTitle('submit', '确定') //修改默认按钮标题
			->addBtn('<button type="reset" class="btn btn-default">重置</button>') //添加额外按钮
			->addText('title', '规则名称','请最好不要超过20个汉字')
			->addText('keywords', '采集关键词','请最好不要超过20个汉字')
            ->addSelect('class_name', '采集后所属分类 ','采集后自动归为此分类',$select_class)
			->addText('num', '采集数量',"采集数量必须为'100','200','300','400','500','600','700','800','900','1000'")
			//->isAjax(false) //默认为ajax的post提交
			->fetch();
	}
}
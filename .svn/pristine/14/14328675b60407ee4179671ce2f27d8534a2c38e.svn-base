<?php
namespace app\taoke\admin;

use app\admin\controller\Admin;
use think\Db;
use think\Request;
use think\Validate;
use app\common\builder\ZBuilder; // 引入ZBuilder

/**
 * Iconurl 后台模块
 */
class Usertopmodule extends Admin
{

	/*
     *  个人中心顶部模块列表
     *  @return Result
     */
	public function index()
	{
		$where = [
			'agent_id' => UID,
			'classify' => 1,
		];
		$data['list'] = Db::name('icon_url')->where($where)->order('weight asc,id asc')->select();
		foreach ($data['list'] as $key => $value) {
			$img = Db::name('admin_attachment')->where('id',$value['icon'])->field('path')->find();
			$data['list'][$key]['path'] = $img['path'];
		}
		$html = $this->getFetch('index',$data);
		return ZBuilder::make('table')
			->css(['swiper.min','find','jquery.gridly'])
			->setExtraHtml($html)
			->fetch();
	}
	/*
     *  添加个人中心顶部模块
     *  @param int $id 添加ID
     *  @param array $data 添加数据队列
     *  @return Result
     */
	public function add()
	{
		if (Request::instance()->isPost()) {
			$data=input('post.');
			$insert=array();
			$insert['title']=$data['title'];
			$insert['icon']=$data['icon'];
			$insert['type']=$data['type'];
			$insert['url']=$data['url'];
			$insert['view']=$data['view'];
			$insert['agent_id']=UID;
			$insert['classify']=1;
			$icon_url_id=Db::name("icon_url")->insertGetId($insert);
			if($icon_url_id>0){
				return $this->success('添加个人中心顶部模块成功');
	        } else {
	            return $this->error('添加个人中心顶部模块失败');
	        }
		}
		return ZBuilder::make('form')
			->setPageTitle('添加个人中心顶部模块') // 设置页面标题
			->setBtnTitle('submit', '确定') //修改默认按钮标题
			->addBtn('<button type="reset" class="btn btn-default">重置</button>') //添加额外按钮
			->addText('title', '标题','')
			->addImage('icon', '图标','')
			->addSelect('type', '分类', '', ['0' => '跳转链接', '1' => '跳转视图'])
			->addText('url', '跳转链接','')
			->addSelect('view', '跳转视图', '', [
					'view_coupon' => '优惠券', 
					'view_9k9' => '九块九',
					'view_highback' => '超高返',
					'view_back' => '返利商城',
					'view_conv' => '兑换商城',
					'view_flashsale' => '限时抢购',
					'view_price20' => '20元优选',
					'view_todaygoods' => '今日上新',
				])
            ->fetch('',[],[],[],false,'layoutClear');
	}
	/*
     *  修改个人中心顶部模块
     *  @param int $id 修改ID
     *  @param array $data 修改数据队列
     *  @return Result
     */
	public function edit($id = null)
	{
		if ($id === null) return $this->error('缺少参数');
        if ($this->request->isPost()) {
            $data = $this->request->post();
            if (Db::name("icon_url")->where('id',$id)->update($data)) {
                return $this->success('修改个人中心顶部模块成功');
            } else {
                return $this->error('修改个人中心顶部模块失败');
            }
        }
		$icon_url=db('icon_url')->find($id);
		return ZBuilder::make('form')
			->setPageTitle('添加个人中心顶部模块') // 设置页面标题
			->setBtnTitle('submit', '确定') //修改默认按钮标题
			->addBtn('<button type="reset" class="btn btn-default">重置</button>') //添加额外按钮
			->addText('title', '标题','',$icon_url['title'])
			->addImage('icon', '图标','',$icon_url['icon'])
			->addSelect('type', '分类', '', ['0' => '跳转链接', '1' => '跳转视图'],$icon_url['type'])
			->addText('url', '链接地址','',$icon_url['url'])
			->addSelect('view', '跳转视图', '', [
					'view_coupon' => '优惠券', 
					'view_9k9' => '九块九',
					'view_highback' => '超高返',
					'view_back' => '返利商城',
					'view_conv' => '兑换商城',
					'view_flashsale' => '限时抢购',
					'view_price20' => '20元优选',
					'view_todaygoods' => '今日上新',
				],$icon_url['view'])
        	->fetch('',[],[],[],false,'layoutClear');
	}
	/*
     *  删除个人中心顶部模块
     *  @param int $id 删除ID
     *  @return Result
     */
    public function del($id = null){
        if ($id === null) return $this->error('缺少参数');
        if(Db::name('icon_url')->delete($id)){
            exit(json_encode(['200','删除成功']));
        }else{
            exit(json_encode(['201','删除失败']));
        }
    }
    /*
     *  修改排序
     *  @param array $data ID排序队列
     *  @return Result
     */
    public function editWeight(){
        if(!Request::instance()->has('data','post')) exit(json_encode(['201','缺少参数:data']));
        $param = input('post.')['data'];
        $sql = "UPDATE tk_icon_url SET weight = CASE id";
        foreach ($param as $k => $v) {
            $sql .= " WHEN $v THEN $k";
        }
        $sql .= ' END WHERE id IN ('.implode(',',$param).')';
        Db::query($sql);
        exit(json_encode(['200','操作成功']));
    }
}
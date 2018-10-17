<?php
namespace app\taoke\admin;

use app\admin\controller\Admin;
use think\Db;
use think\Request;
use think\Validate;
use app\common\builder\ZBuilder; // 引入ZBuilder

/**
 * User 后台模块
 */
class User extends Admin
{
	//用户管理
	public function index()
	{
		// 获取排序
        $order = $this->getOrder();
        if($order===''){
            $order='reg_time desc,id desc';
        }
        // 获取筛选
        $map = $this->getMap();
		$data_list = Db::name('users')->where($map)->order($order)->paginate();
		// 分页数据
		$page = $data_list->render();
		$param = [
		    'id'=>['t'=>'ID','s'=>'='],//等于比较 也可以不写s
		    'username'=>['t'=>'账号','s'=>'like'],
		    'nickname'=>['t'=>'昵称','s'=>'like'],
		    'mobile'=>['t'=>'手机号','s'=>'like'],
		    'score_begin'=>['t'=>'>积分','s'=>'>'],//大于
		    'score_end'=>['t'=>'<积分','s'=>'<'],
		    'reg_time_timestart'=>['t'=>'注册时间','s'=>'>'],
		    'reg_time_timeend'=>['t'=>'注册时间','s'=>'>'],
// 		    'agent_id'=>['t'=>'选中代理','class'=>'select','value'=>1,'items'=>[1,2,3,4]]
		];
		//_score.s=<
		// 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
        	->setPageTitle('用户列表') // 设置页面标题
        	->setPageTips('修改和删除可能会导致其他的相关数据失效，请谨慎操作') // 设置页面提示信息
        	->setTableName('users') // 指定数据表名
        	->addOrder('id,reg_time,score,red_balance') // 添加排序
//             ->addTimeFilter('reg_time') // 添加时间段筛选
            ->setSearch($param)
//             ->setSearch(['id' => 'ID','username'=>'账号','nickname'=>'昵称','mobile'=>'手机号']) // 设置搜索参数
            ->addFilter('status', ['0'=>'已锁定', '1'=>'未锁定']) // 添加标题字段筛选
        	->addColumns([
        			['id', 'ID'],
        			['username', '账号'],
        			['nickname', '昵称'],
        			['mobile', '手机号'],
        			['score', '用户积分'],
        			['red_balance', '余额','callback', 'str_linked', '元'],
        			['auth_mobile', '手机号认证', 'status', '', ['未认证', '已认证']],
        			['parent_uid', '上级ID','callback',function($parent_uid){
        				return $parent_uid>0?"<a href='".url("index?search_field=id&keyword={$parent_uid}")."' target='_bank' title='点击查看上级'>{$parent_uid}</a>":"";
        			}],
        			['status', '是否锁定', 'status', '', ['是', '否']],
        			['is_salesman', '是否业务员', 'status', '', ['否', '是']],
        			['salesman_id', '业务员ID'],
        			['mid', '商户ID'],
        			['ad_mid', '广告商ID'],
        			['reg_time', '注册时间', 'datetime','未知'],
        			['right_button', '操作', 'btn'],
        		]) //添加多列数据
        	->addRightButtons(['edit','delete'=>['table'=>'users']]) // 批量添加右侧按钮
        	->addRightButton('custom',['title'=>'推送消息','icon'=>'fa fa-fw fa-commenting-o','href'=>url('push',['id'=>'__ID__'])])
        	->addRightButton('custom',['title'=>'绑定信息','icon'=>'fa fa-fw fa-lock','href'=>url('bind',['id'=>'__ID__'])])
        	->addRightButton('custom',['title'=>'数据','icon'=>'fa fa-fw fa-cogs','href'=>url('group',['id'=>'__ID__'])])
    		->addTopButtons(['add','delete'=>['table'=>'users'],'custom'=>['title'=>'无筛选','href'=>url('index')]]) // 批量添加顶部按钮
    		->addTopButton('custom',['title'=>'广播','href'=>url('pushAll')])
    		->addTopButton('custom',['title'=>'组播','href'=>url('pushTags')])
    		->addTopButton('custom',['title'=>'群播','href'=>url('pushUsers'),'class' => 'btn btn-primary js-get'])
        	->setRowList($data_list) // 设置表格数据
        	->setPages($page) // 设置分页数据
        	->fetch();
	}
	//添加用户
	public function add()
	{
		//判断是否为post请求
		if (Request::instance()->isPost()) {
			//获取请求的post数据
			$data=input('post.');
			$now=time();
			//数据输入验证
			if(!preg_match("/^\w{3,10}$/",$data['username'])){
				return $this->error('用户名不合法');
			}
			$old_user=Db::name('users')->where('username',$data['username'])->find();
			if($old_user){
				return $this->error('该用户名已被注册');
			}
			if(!preg_match("/^\d{11}$/",$data['mobile'])){
				return $this->error('注册手机号必须为11位数字');
			}
			$old_user=Db::name('users')->where('mobile',$data['mobile'])->find();
			if($old_user){
				return $this->error('该手机号已被注册');
			}
			if($data['parent_uid']>0){
				$parent=Db::name('users')->find($data['recommend_uid']);
				if(!$parent){
					return $this->error('输入的上级不存在');
				}
			}else{
				$data['parent_uid']=0;
			}
			
			//数据处理
			$insert=array();
			$insert['username']=$data['username'];
			$insert['nickname']=$data['nickname'];
			$insert['mobile']=$data['mobile'];
			$insert['parent_uid']=$data['parent_uid'];
			$insert['reg_time']=$now;
			//数据更新
			$id=Db::name("users")->insertGetId($insert);
			//跳转
			if($id>0){
				return $this->success('添加用户成功','index','',1);
	        } else {
	            return $this->error('添加用户失败');
	        }
		}
		
		// 使用ZBuilder快速创建表单
		return ZBuilder::make('form')
			->setPageTitle('添加用户') // 设置页面标题
			->setPageTips('该操作可能会导致其他的相关数据失效，请勿随意修改信息') // 设置页面提示信息
			//->setUrl('edit') // 设置表单提交地址
			//->hideBtn(['back']) //隐藏默认按钮
			->setBtnTitle('submit', '确定') //修改默认按钮标题
			->addBtn('<button type="reset" class="btn btn-default">重置</button>') //添加额外按钮
			->addText('username', '账号','不要超过十个汉字')
			->addText('mobile', '注册手机号','11位手机号码')
			->addText('nickname', '昵称','请最好不要超过八个汉字')
			->addText('parent_uid', '上级ID','你的上级会根据你的交易获得相应奖励，填0或不填代表没有推荐人')
			//->isAjax(false) //默认为ajax的post提交
			->fetch();
	}
	//修改用户
	public function edit($id='')
	{
		//判断是否为post请求
		if (Request::instance()->isPost()) {
			//获取请求的post数据
			$data=input('post.');
			$now=time();
			//数据输入验证
			if(!preg_match("/^\w{3,10}$/",$data['username'])){
				return $this->error('用户名不合法');
			}
			$old_user=Db::name('users')->where(['username'=>$data['username'],'id'=>['neq',$data['id']]])->find();
			if($old_user){
				return $this->error('该用户名已被注册');
			}
			if(!preg_match("/^\d{11}$/",$data['mobile'])){
				return $this->error('注册手机号必须为11位数字');
			}
			$old_user=Db::name('users')->where(['mobile'=>$data['mobile'],'id'=>['neq',$data['id']]])->find();
			if($old_user){
				return $this->error('该手机号已被注册');
			}
			if(!preg_match("/^\d+?$/",$data['score'])){
				return $this->error('积分数量格式错误');
			}
			if(!preg_match("/^\d+(\.\d+)?$/",$data['red_balance'])){
				return $this->error('余额格式错误');
			}
			if($data['parent_uid']>0){
				$parent=Db::name('users')->find($data['parent_uid']);
				if(!$parent){
					return $this->error('输入的上级不存在');
				}
				if($data['parent_uid']>=$data['id']){
					return $this->error('输入的上级ID必须小于自己的ID');
				}
			}else{
				$data['parent_uid']=0;
			}
			
			//数据处理
			$update=array();
			$update['id']=$data['id'];
			$update['username']=$data['username'];
			$update['nickname']=$data['nickname'];
			$update['mobile']=$data['mobile'];
			$update['score']=$data['score'];
			$update['red_balance']=$data['red_balance'];
			$update['parent_uid']=$data['parent_uid'];
			$update['status']=$data['status'];
			$update['is_salesman']=$data['is_salesman'];
			$update['auth_mobile']=$data['auth_mobile'];
			//数据更新
			$rt=Db::name("users")->update($update);
			//跳转
			if($rt!==false){
				return $this->success('修改用户成功','index','',1);
	        } else {
	            return $this->error('修改用户失败');
	        }
		}
		// 接收id
		if ($id>0) {
			// 查处数据
			$users=Db::name("users")->where('id',$id)->find();
			// 使用ZBuilder快速创建表单
			return ZBuilder::make('form')
				->setPageTitle('修改用户') // 设置页面标题
				->setPageTips('该操作可能会导致其他的相关数据失效，请勿随意修改信息') // 设置页面提示信息
				//->setUrl('edit') // 设置表单提交地址
				//->hideBtn(['back']) //隐藏默认按钮
				->setBtnTitle('submit', '确定') //修改默认按钮标题
				->addBtn('<button type="reset" class="btn btn-default">重置</button>') //添加额外按钮
				->addText('username', '账号','不要超过十个汉字',$users['username'])
				->addText('mobile', '注册手机号','11位手机号码',$users['mobile'])
				->addText('nickname', '昵称','请最好不要超过八个汉字',$users['nickname'])
				->addText('score', '积分','请尽量不要在此处修改',$users['score'])
				->addText('red_balance', '余额','请尽量不要在此处修改，精确到分',$users['red_balance'],['', '元'])
				->addText('parent_uid', '上级ID','你的上级会根据你的交易获得相应奖励，填0或不填代表没有推荐人',$users['parent_uid']>0?$users['parent_uid']:'')
				->addRadio('auth_mobile', '手机号是否认证', '', ['0' => '未认证', '1' => '已认证'],$users['auth_mobile'])
				->addRadio('status', '锁定状态', '', ['0' => '锁定', '1' => '不锁定'],$users['status'])
				->addRadio('is_salesman', '是否为业务员', '', ['0' => '不是', '1' => '是'],$users['is_salesman'])
				->addHidden('id',$users['id'])
				//->isAjax(false) //默认为ajax的post提交
				->fetch();
		}
	}
	public function bind($bind = 'wx'){
		 $user_id=input('id');
	    $user=Db::name('users')->find($user_id);
	    $list_tab = [
	    	'wx' => ['title' => '微信', 'url' => url('bind', ['bind' => 'wx','id'=>$user_id])],
	    	'taobao_auth' => ['title' => '淘宝', 'url' => url('bind', ['bind' => 'taobao_auth','id'=>$user_id])],
	    	'baidu_push_bind' => ['title' => '百度推送', 'url' => url('bind', ['bind' => 'baidu_push_bind','id'=>$user_id])],
	    ];
	   switch ($bind) {
	    	case 'wx':
			$user_wx = Db::name('user_wx')->where('user_id',$user_id)->find();
			if ($user_wx) {
				return ZBuilder::make('form')
	                ->setTabNav($list_tab,  $bind)
	                ->setPageTitle('用户('.$user['username'].')绑定微信') // 设置页面标题
		        	->setPageTips('数据不能修改') // 设置页面提示信息
		        	->hideBtn(['back','submit']) //隐藏默认按钮
		            ->addStatic('app_openid', 'APP登录的openid','',$user_wx['app_openid'])
		            ->addStatic('app_unionid', 'APP登录的unionid','',$user_wx['app_unionid'])
		            ->addStatic('gh_openid', '公众号的openid','',$user_wx['gh_openid'])
		            ->addStatic('gh_unionid', '公众号的unionid','',$user_wx['gh_unionid'])
		            ->addStatic('web_openid', 'web的openid','',$user_wx['web_openid'])
		            ->addStatic('web_unionid', 'web的unionid','',$user_wx['web_unionid'])
		            ->addStatic('created', '绑定时间','',$user_wx['created']?date('Y-m-d H:i',$user_wx['created']):'未知')
	                ->fetch();
			}else{
				return ZBuilder::make('form')
	                ->setTabNav($list_tab,  $bind)
	                ->setPageTitle('用户('.$user['username'].')绑定微信') // 设置页面标题
		        	->setPageTips('数据不能修改') // 设置页面提示信息
		        	->hideBtn(['back','submit']) //隐藏默认按钮
		            ->fetch();
			}  
            break;
            case 'taobao_auth':
			$users_taobao_auth = Db::name('users_taobao_auth')->where('user_id',$user_id)->find();
			if ($users_taobao_auth) {
				return ZBuilder::make('form')
	                ->setTabNav($list_tab,  $bind)
	                ->setPageTitle('用户('.$user['username'].')绑定淘宝') // 设置页面标题
		        	->setPageTips('数据不能修改') // 设置页面提示信息
		        	->hideBtn(['back','submit']) //隐藏默认按钮
		            ->addStatic('nickname', '昵称','',$users_taobao_auth['nickname'])
		            ->addStatic('avatar_url', '头像地址','','<img src="'.$users_taobao_auth['avatar_url'].' width="500">')
		            ->addStatic('openid', 'openid','',$users_taobao_auth['openid'])
		            ->addStatic('opensid', 'opensid','',$users_taobao_auth['opensid'])
		            ->addStatic('top_accesstoken', 'top_accesstoken','',$users_taobao_auth['top_accesstoken'])
		            ->addStatic('created', '绑定时间','',$users_taobao_auth['created']?date('Y-m-d H:i',$users_taobao_auth['created']):'未知')
	                ->fetch();
			}else{
				return ZBuilder::make('form')
	                ->setTabNav($list_tab,  $bind)
	                ->setPageTitle('用户('.$user['username'].')绑定淘宝') // 设置页面标题
		        	->setPageTips('数据不能修改') // 设置页面提示信息
		        	->hideBtn(['back','submit']) //隐藏默认按钮
		            ->fetch();
			}  
            break;
            case 'baidu_push_bind':
			$baidu_push_bind = Db::name('baidu_push_bind')->where('user_id',$user_id)->find();
			if ($baidu_push_bind) {
				return ZBuilder::make('form')
	                ->setTabNav($list_tab,  $bind)
	                ->setPageTitle('用户('.$user['username'].')绑定百度推送') // 设置页面标题
		        	->setPageTips('数据不能修改') // 设置页面提示信息
		        	->hideBtn(['back','submit']) //隐藏默认按钮
		            ->addStatic('apikey', 'apikey','',$baidu_push_bind['apikey'])
		            ->addStatic('channel_id', 'channel_id','',$baidu_push_bind['channel_id'])
		            ->addStatic('sdk_userid', 'sdk_userid','',$baidu_push_bind['sdk_userid'])
		            ->addStatic('os', '操作系统','',$baidu_push_bind['os'])
		            ->addStatic('tag', 'tag','',$baidu_push_bind['tag'])
		            ->addStatic('bind_time', '绑定时间','',$baidu_push_bind['bind_time'])
	                ->fetch();
			}else{
				return ZBuilder::make('form')
	                ->setTabNav($list_tab,  $bind)
	                ->setPageTitle('用户('.$user['username'].')绑定百度推送') // 设置页面标题
		        	->setPageTips('数据不能修改') // 设置页面提示信息
		        	->hideBtn(['back','submit']) //隐藏默认按钮
		            ->fetch();
			}  
            break;
        }
	}
	public function group($group = 'orders'){
	    $user_id=input('id');
	    $user=Db::name('users')->find($user_id);
	    $list_tab = [
	        'orders' => ['title' => '用户订单', 'url' => url('group', ['group' => 'orders','id'=>$user_id])],
	        'redpackage' => ['title' => '红包仓库', 'url' => url('group', ['group' => 'redpackage','id'=>$user_id])],
	        'redpackage_log' => ['title' => '领取红包', 'url' => url('group', ['group' => 'redpackage_log','id'=>$user_id])],
	        'collection' => ['title' => '收藏记录', 'url' => url('group', ['group' => 'collection','id'=>$user_id])],
	        'browse_log' => ['title' => '浏览记录', 'url' => url('group', ['group' => 'browse_log','id'=>$user_id])],
	        'message' => ['title' => '消息记录', 'url' => url('group', ['group' => 'message','id'=>$user_id])],
	        'withdrawals_card' => ['title' => '提现卡', 'url' => url('group', ['group' => 'withdrawals_card','id'=>$user_id])],
	        'sign' => ['title' => '签到', 'url' => url('group', ['group' => 'sign','id'=>$user_id])],
	        'score_log' => ['title' => '积分记录', 'url' => url('group', ['group' => 'score_log','id'=>$user_id])],
	        'redbalance_log' => ['title' => '红包账户记录', 'url' => url('group', ['group' => 'redbalance_log','id'=>$user_id])],
	        'withdraw_type' => ['title' => '提现方式', 'url' => url('group', ['group' => 'withdraw_type','id'=>$user_id])],
	    ];
	    if($user['is_salesman']!='0'){
	    	 $list_tab['salesman_bind']=['title' => '业务员', 'url' => url('group', ['group' => 'salesman_bind','id'=>$user_id])];
	    }
	    switch ($group) {
            case 'orders':
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
			$data_list = Db::name('user_orders')->where($map)->where('user_id',$user_id)->order($order)->paginate();
			// 分页数据
			$page = $data_list->render();
            return ZBuilder::make('table')
                ->setTabNav($list_tab,  $group)
                ->setPageTitle('用户('.$user['username'].')订单列表') // 设置页面标题
	        	->setPageTips('删除可能会导致其他的相关数据失效，请谨慎操作') // 设置页面提示信息
	        	->setTableName('user_orders') // 指定数据表名
	        	->addOrder('id,created,order_id,user_get_balance,total_price,get_time,income_estimate') // 添加排序
	            ->addTimeFilter('created') // 添加时间段筛选
	            ->setSearch(['id' => 'ID','order_id'=>'订单ID']) // 设置搜索参数
	            ->addFilter('status', ['0'=>'未查到订单', '1'=>'已付款', '2'=>'已返现', '3'=>'失败']) // 添加标题字段筛选
	        	->addColumns([
	        			['id', 'ID'],
	        			['order_id','订单ID'],
	        			['total_price','订单金额','callback','str_linked','元'],
	        			['user_get_balance','返利金额','callback','str_linked','元'],
	        			['get_time','返利到账时间','datetime','未知'],
	        			['income_estimate','预估收入','callback','str_linked','元'],
	        			['status', '状态', 'callback', 'array_v', array('未查到订单','已付款','已返现','失败')],
	        			['created', '订单时间', 'datetime','未知'],
	        			['right_button', '操作', 'btn'],
	        		]) //添加多列数据
	        	->addRightButtons(['delete'=>['table'=>'user_orders']]) // 批量添加右侧按钮
	    		->addTopButtons(['delete'=>['table'=>'user_orders'],'custom'=>['title'=>'无筛选','href'=>url('group',['group'=>'orders','id'=>$user_id])]]) // 批量添加顶部按钮
	        	->setRowList($data_list) // 设置表格数据
	        	->setPages($page) // 设置分页数据
                ->fetch();
            break;
            case 'redpackage':
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
			$data_list = Db::name('user_redpackage')->where($map)->where('user_id',$user_id)->order($order)->paginate();
			// 分页数据
			$page = $data_list->render();
            return ZBuilder::make('table')
                ->setTabNav($list_tab,  $group)
                ->setPageTitle('用户('.$user['username'].')红包仓库列表') // 设置页面标题
	        	->setPageTips('删除可能会导致其他的相关数据失效，请谨慎操作') // 设置页面提示信息
	        	->setTableName('user_redpackage') // 指定数据表名
	        	->addOrder('id,created,red_balance,get_time,batch_number') // 添加排序
	            ->addTimeFilter('created') // 添加时间段筛选
	            ->setSearch(['id' => 'ID','red_title'=>'订单ID','batch_number'=>'批次号']) 
	        	->addColumns([
	        			['id', 'ID'],
	        			['red_title', '标题'],
	        			['red_balance','金额','callback','str_linked','元'],
	        			['red_remark', '说明'],
	        			['pic', '图片','picture'],
	        			['batch_number','批次号'],
	        			['red_class','分类'],
	        			['red_class_param','分类属性'],
	        			['app_pop','弹出','status', '', ['否', '是']],
	        			['created', '添加时间', 'datetime','未知'],
	        			['get_status','领取','status', '', ['否', '是']],
	        			['get_time', '领取时间', 'datetime','未知'],
	        			['right_button', '操作', 'btn'],
	        		]) //添加多列数据
	        	->addRightButtons(['delete'=>['table'=>'user_redpackage_log']]) // 批量添加右侧按钮
	    		->addTopButtons(['delete'=>['table'=>'user_redpackage_log'],'custom'=>['title'=>'无筛选','href'=>url('group',['group'=>'redpackage','id'=>$user_id])]]) // 批量添加顶部按钮
	        	->setRowList($data_list) // 设置表格数据
	        	->setPages($page) // 设置分页数据
                ->fetch();
            break;
            case 'redpackage_log':
        	// 获取排序
	        $order = $this->getOrder();
	        if($order===''){
	            $order='get_time desc,id desc';
	        }
	        // 获取筛选
	        $map = $this->getMap();
	        if(isset($map['get_time'])){
	            $map['get_time'][1][0]=strtotime($map['get_time'][1][0]);
	            $map['get_time'][1][1]=strtotime($map['get_time'][1][1]);
	        }
			// 读取用户数据
			$data_list = Db::name('user_redpackage_log')->where($map)->where('user_id',$user_id)->order($order)->paginate();
			// 分页数据
			$page = $data_list->render();
            return ZBuilder::make('table')
                ->setTabNav($list_tab,  $group)
                ->setPageTitle('用户('.$user['username'].')领取红包列表') // 设置页面标题
	        	->setPageTips('删除可能会导致其他的相关数据失效，请谨慎操作') // 设置页面提示信息
	        	->setTableName('user_redpackage_log') // 指定数据表名
	        	->addOrder('id,get_time,get_balance') // 添加排序
	            ->addTimeFilter('get_time') // 添加时间段筛选
	            ->setSearch(['id' => 'ID']) 
	        	->addColumns([
	        			['id', 'ID'],
	        			['get_balance','领取金额','callback','str_linked','元'],
	        			['get_time', '领取时间', 'datetime','未知'],
	        			['remark','说明'],
	        			['right_button', '操作', 'btn'],
	        		]) //添加多列数据
	        	->addRightButtons(['delete'=>['table'=>'user_redpackage_log']]) // 批量添加右侧按钮
	    		->addTopButtons(['delete'=>['table'=>'user_redpackage_log'],'custom'=>['title'=>'无筛选','href'=>url('group',['group'=>'redpackage_log','id'=>$user_id])]]) // 批量添加顶部按钮
	        	->setRowList($data_list) // 设置表格数据
	        	->setPages($page) // 设置分页数据
                ->fetch();
            break;
            case 'collection':
        	// 获取排序
	        $order = $this->getOrder();
	        if($order===''){
	            $order='a.created desc,id desc';
	        }
	        // 获取筛选
	        $map = $this->getMap();
	        if(isset($map['a.created'])){
	            $map['a.created'][1][0]=strtotime($map['a.created'][1][0]);
	            $map['a.created'][1][1]=strtotime($map['a.created'][1][1]);
	        }
			// 读取用户数据
			$data_list = Db::name('user_collection a')->join('taobao_items b','a.item_id=b.id','LEFT')->where($map)->where('a.user_id',$user_id)->order($order)->field('a.*,b.id as i_id,b.title')->paginate();
			// 分页数据
			$page = $data_list->render();
            return ZBuilder::make('table')
                ->setTabNav($list_tab,  $group)
                ->setPageTitle('用户('.$user['username'].')收藏记录列表') // 设置页面标题
	        	->setPageTips('删除可能会导致其他的相关数据失效，请谨慎操作') // 设置页面提示信息
	        	->setTableName('user_collection') // 指定数据表名
	        	->addOrder('a.id,a.created') // 添加排序
	            ->addTimeFilter('a.created') // 添加时间段筛选
	            ->setSearch(['a.id' => 'ID','b.title'=>'商品标题']) 
	        	->addColumns([
	        			['id', 'ID'],
	        			['title', '商品名称', 'link', url('taoke/taobaoitems/link', ['id' => '__i_id__'])],
	        			['created', '收藏时间', 'datetime','未知'],
	        			['right_button', '操作', 'btn'],
	        		]) //添加多列数据
	        	->addRightButtons(['delete'=>['table'=>'user_collection']]) // 批量添加右侧按钮
	    		->addTopButtons(['delete'=>['table'=>'user_collection'],'custom'=>['title'=>'无筛选','href'=>url('group',['group'=>'collection','id'=>$user_id])]]) // 批量添加顶部按钮
	        	->setRowList($data_list) // 设置表格数据
	        	->setPages($page) // 设置分页数据
                ->fetch();
            break;
            case 'browse_log':
        	// 获取排序
	        $order = $this->getOrder();
	        if($order===''){
	            $order='a.created desc,id desc';
	        }
	        // 获取筛选
	        $map = $this->getMap();
	        if(isset($map['a.created'])){
	            $map['a.created'][1][0]=strtotime($map['a.created'][1][0]);
	            $map['a.created'][1][1]=strtotime($map['a.created'][1][1]);
	        }
			// 读取用户数据
			$data_list = Db::name('browse_log a')->join('taobao_items b','a.item_id=b.id','LEFT')->where($map)->where('a.user_id',$user_id)->order($order)->field('a.*,b.id as i_id,b.title')->paginate();
			// 分页数据
			$page = $data_list->render();
            return ZBuilder::make('table')
                ->setTabNav($list_tab,  $group)
                ->setPageTitle('用户('.$user['username'].')浏览记录列表') // 设置页面标题
	        	->setPageTips('删除可能会导致其他的相关数据失效，请谨慎操作') // 设置页面提示信息
	        	->setTableName('browse_log') // 指定数据表名
	        	->addOrder('a.id,a.created') // 添加排序
	            ->addTimeFilter('a.created') // 添加时间段筛选
	            ->setSearch(['a.id' => 'ID','b.title'=>'商品标题']) 
	        	->addColumns([
	        			['id', 'ID'],
	        			['title', '商品名称', 'link', url('taoke/taobaoitems/link', ['id' => '__i_id__'])],
	        			['created', '收藏时间', 'datetime','未知'],
	        			['right_button', '操作', 'btn'],
	        		]) //添加多列数据
	        	->addRightButtons(['delete'=>['table'=>'browse_log']]) // 批量添加右侧按钮
	    		->addTopButtons(['delete'=>['table'=>'browse_log'],'custom'=>['title'=>'无筛选','href'=>url('group',['group'=>'browse_log','id'=>$user_id])]]) // 批量添加顶部按钮
	        	->setRowList($data_list) // 设置表格数据
	        	->setPages($page) // 设置分页数据
                ->fetch();
            break;
            case 'message':
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
			$data_list = Db::name('user_message')->where($map)->where('user_id',$user_id)->order($order)->paginate();
			// 分页数据
			$page = $data_list->render();
            return ZBuilder::make('table')
                ->setTabNav($list_tab,  $group)
                ->setPageTitle('用户('.$user['username'].')消息列表') // 设置页面标题
	        	->setPageTips('删除可能会导致其他的相关数据失效，请谨慎操作') // 设置页面提示信息
	        	->setTableName('user_message') // 指定数据表名
	        	->addOrder('id,created') // 添加排序
	            ->addTimeFilter('created') // 添加时间段筛选
	            ->setSearch(['id' => 'ID']) 
	        	->addColumns([
	        			['id', 'ID'],
	        			['sender_name','发送者'],
	        			['title', '标题', 'link', url('message_link', ['id' => '__id__'])],
	        			['pic', '图片','picture'],
	        			['is_read', '是否已读', 'status', '', ['否', '是']],
	        			['created', '时间', 'datetime','未知'],
	        			['right_button', '操作', 'btn'],
	        		]) //添加多列数据
	        	->addRightButton('custom',['title'=>'查看消息内容','href'=>url('message_look',['id'=>'__ID__']),'icon'=>'fa fa-fw fa-eye'],true)
	        	->addRightButtons(['delete'=>['table'=>'user_message']]) // 批量添加右侧按钮
	    		->addTopButtons(['delete'=>['table'=>'user_message'],'custom'=>['title'=>'无筛选','href'=>url('group',['group'=>'message','id'=>$user_id])]]) // 批量添加顶部按钮
	        	->setRowList($data_list) // 设置表格数据
	        	->setPages($page) // 设置分页数据
                ->fetch();
            break;
            case 'withdrawals_card':
            // 获取排序
	        $order = $this->getOrder();
	        if($order===''){
	            $order='a.created desc,a.id desc';
	        }
	        // 获取筛选
	        $map = $this->getMap();
	        if(isset($map['created'])){
	            $map['a.created'][1][0]=strtotime($map['a.created'][1][0]);
	            $map['a.created'][1][1]=strtotime($map['a.created'][1][1]);
	        }
			// 读取用户数据
			// 读取用户数据
			$data_list = Db::name('user_withdrawals_card a')->join('withdrawals_card b','a.card_id=b.id','LEFT')->where('a.user_id',$user_id)->field('a.*,b.base_price,b.remark')->order($order)->paginate();
			// 分页数据
			$page = $data_list->render();
			return ZBuilder::make('table')
	            ->setTabNav($list_tab,  $group)
                ->setPageTitle('用户('.$user['username'].')红包卡列表') // 设置页面标题
	        	->setPageTips('删除可能会导致其他的相关数据失效，请谨慎操作') // 设置页面提示信息
	        	->setTableName('user_withdrawals_card') // 指定数据表名
	        	->addOrder('a.id,a.created') // 添加排序
	            ->addTimeFilter('a.created') // 添加时间段筛选
	            ->setSearch(['a.id' => 'ID']) 
	        	->addColumns([
	        			['id', 'ID'],
	        			['card_base_price','红包卡余额','callback','str_linked','元'],
	        			['base_price','提现卡额度','callback','str_linked','元'],
	        			['remark', '提现卡说明'],
	        			['is_active', '是否激活', 'status', '', ['否', '是']],
	        			['used', '是否使用', 'status', '', ['否', '是']],
	        			['created', '时间', 'datetime','未知'],
	        			['right_button', '操作', 'btn'],
	        		]) //添加多列数据
	        	->addRightButtons(['delete'=>['table'=>'user_withdrawals_card']]) // 批量添加右侧按钮
	    		->addTopButtons(['delete'=>['table'=>'user_withdrawals_card'],'custom'=>['title'=>'无筛选','href'=>url('group',['group'=>'withdrawals_card','id'=>$user_id])]]) // 批量添加顶部按钮
	        	->setRowList($data_list) // 设置表格数据
	        	->setPages($page) // 设置分页数据
                ->fetch();
            break;
            case 'sign':
        	// 获取排序
	        $order = $this->getOrder();
	        if($order===''){
	            $order='id desc';
	        }
	        // 获取筛选
	        $map = $this->getMap();
			// 读取用户数据
			$data_list = Db::name('user_sign')->where($map)->where('user_id',$user_id)->order($order)->paginate();
			// 分页数据
			$page = $data_list->render();
            return ZBuilder::make('table')
                ->setTabNav($list_tab,  $group)
                ->setPageTitle('用户('.$user['username'].')签到列表') // 设置页面标题
	        	->setPageTips('删除可能会导致其他的相关数据失效，请谨慎操作') // 设置页面提示信息
	        	->setTableName('user_sign') // 指定数据表名
	        	->addOrder('id,continuity_day') // 添加排序
	            ->addTimeFilter('sign_day') // 添加时间段筛选
	            ->setSearch(['id' => 'ID']) 
	        	->addColumns([
	        			['id', 'ID'],
	        			['sign_day', '签到日期'],
	        			['continuity_day','连续签到天数'],
	        			['right_button', '操作', 'btn'],
	        		]) //添加多列数据
	        	->addRightButtons(['delete'=>['table'=>'user_sign']]) // 批量添加右侧按钮
	    		->addTopButtons(['delete'=>['table'=>'user_sign'],'custom'=>['title'=>'无筛选','href'=>url('group',['group'=>'sign','id'=>$user_id])]]) // 批量添加顶部按钮
	        	->setRowList($data_list) // 设置表格数据
	        	->setPages($page) // 设置分页数据
                ->fetch();
            break;
            case 'salesman_bind':
			$salesman_bind = Db::name('salesman_bind')->where('user_id',$user_id)->find();
			if ($salesman_bind) {
				return ZBuilder::make('form')
	                ->setTabNav($list_tab,  $group)
	                ->setPageTitle('用户('.$user['username'].')业务员数据') // 设置页面标题
		        	->setPageTips('数据不能修改') // 设置页面提示信息
		        	->hideBtn(['back','submit']) //隐藏默认按钮
		            ->addStatic('balance', '金额','',$salesman_bind['balance'].'元')
		            ->addStatic('reg_time', '成为业务员时间','',$salesman_bind['reg_time']?$salesman_bind['reg_time']:'未知')
	                ->fetch();
			}else{
				return ZBuilder::make('form')
	                ->setTabNav($list_tab,  $group)
	                ->setPageTitle('用户('.$user['username'].')业务员数据') // 设置页面标题
		        	->setPageTips('数据不能修改') // 设置页面提示信息
		        	->hideBtn(['back','submit']) //隐藏默认按钮
		            ->fetch();
			}  
            break;
            case 'score_log':
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
			$data_list = Db::name('user_score_log')->where('user_id',$user_id)->order($order)->paginate();
			// 分页数据
			$page = $data_list->render();
			return ZBuilder::make('table')
	            ->setTabNav($list_tab,  $group)
                ->setPageTitle('用户('.$user['username'].')积分记录列表') // 设置页面标题
	        	->setPageTips('删除可能会导致其他的相关数据失效，请谨慎操作') // 设置页面提示信息
	        	->setTableName('user_score_log') // 指定数据表名
	        	->addOrder('id,created') // 添加排序
	            ->addTimeFilter('created') // 添加时间段筛选
	            ->setSearch(['id' => 'ID']) 
	        	->addColumns([
	        			['id', 'ID'],
	        			['op_score','积分'],
	        			['op_remark','积分来源'],
	        			['op_class', '积分来源分类'],
	        			['before_score', '操作前积分'],
	        			['created', '时间', 'datetime','未知'],
	        			['right_button', '操作', 'btn'],
	        		]) //添加多列数据
	        	->addRightButtons(['delete'=>['table'=>'user_score_log']]) // 批量添加右侧按钮
	    		->addTopButtons(['delete'=>['table'=>'user_score_log'],'custom'=>['title'=>'无筛选','href'=>url('group',['group'=>'score_log','id'=>$user_id])]]) // 批量添加顶部按钮
	        	->setRowList($data_list) // 设置表格数据
	        	->setPages($page) // 设置分页数据
                ->fetch();
            break;
            case 'redbalance_log':
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
			$data_list = Db::name('user_redbalance_log')->where('user_id',$user_id)->order($order)->paginate();
			// 分页数据
			$page = $data_list->render();
			return ZBuilder::make('table')
	            ->setTabNav($list_tab,  $group)
                ->setPageTitle('用户('.$user['username'].')红包账户记录列表') // 设置页面标题
	        	->setPageTips('删除可能会导致其他的相关数据失效，请谨慎操作') // 设置页面提示信息
	        	->setTableName('user_redbalance_log') // 指定数据表名
	        	->addOrder('id,created') // 添加排序
	            ->addTimeFilter('created') // 添加时间段筛选
	            ->setSearch(['id' => 'ID']) 
	        	->addColumns([
	        			['id', 'ID'],
	        			['op_redbalance','红包金额'],
	        			['op_remark','红包来源'],
	        			['op_class', '红包来源分类'],
	        			['before_score', '操作前红包余额','callback','str_linked','元'],
	        			['created', '时间', 'datetime','未知'],
	        			['right_button', '操作', 'btn'],
	        		]) //添加多列数据
	        	->addRightButtons(['delete'=>['table'=>'user_redbalance_log']]) // 批量添加右侧按钮
	    		->addTopButtons(['delete'=>['table'=>'user_redbalance_log'],'custom'=>['title'=>'无筛选','href'=>url('group',['group'=>'redbalance_log','id'=>$user_id])]]) // 批量添加顶部按钮
	        	->setRowList($data_list) // 设置表格数据
	        	->setPages($page) // 设置分页数据
                ->fetch();
            break;
            case 'withdraw_type':
			// 获取排序
	        $order = $this->getOrder();
	        if($order===''){
	            $order='a.id desc';
	        }
	        // 获取筛选
	        $map = $this->getMap();
			// 读取用户数据
			$data_list = Db::name('withdraw_type a')->join('bank b','a.bank_id=b.id','LEFT')->where('a.user_id',$user_id)->field('a.*,b.name bank_name')->order($order)->paginate();
			// 分页数据
			$page = $data_list->render();
			return ZBuilder::make('table')
	            ->setTabNav($list_tab,  $group)
                ->setPageTitle('用户('.$user['username'].')提现方式列表') // 设置页面标题
	        	->setPageTips('删除可能会导致其他的相关数据失效，请谨慎操作') // 设置页面提示信息
	        	->setTableName('withdraw_type') // 指定数据表名
	        	->addOrder('id')
	            ->setSearch(['id' => 'ID']) 
	        	->addColumns([
	        			['id', 'ID'],
	        			['type','方式','callback','array_v',['银行卡','支付宝','微信']],
	        			['bank_name','信息','callback',function($bank_name,$data){
	        				if($data['type']=='银行卡'){
	        					return "开户银行：{$data['bank_name']}<br>开户人姓名：{$data['card_name']}<br>银行卡号：{$data['card_num']}<br>开户行地址:{$data['bank_address']}";
	        				}elseif($data['type']=='支付宝'){
	        					return "支付宝姓名：{$data['alipay_name']}<br>支付宝账号：{$data['alipay']}";
	        				}elseif($data['type']=='微信'){
	        					return "微信账号：{$data['wxpay']}<br>微信openid：{$data['wxpay_openid']}";
	        				}
	        			},'__data__'],
	        			['right_button', '操作', 'btn'],
	        		]) //添加多列数据
	        	->addRightButtons(['delete'=>['table'=>'withdraw_type']]) // 批量添加右侧按钮
	    		->addTopButtons(['delete'=>['table'=>'withdraw_type'],'custom'=>['title'=>'无筛选','href'=>url('group',['group'=>'withdraw_type','id'=>$user_id])]]) // 批量添加顶部按钮
	        	->setRowList($data_list) // 设置表格数据
	        	->setPages($page) // 设置分页数据
                ->fetch();
            break;
    	}
	}
	public function message_link(){
		$id=input('id');
		$user_message = Db::name('user_message')->where('id',$id)->find();
		if($user_message['url']!='') $str='<script>location.href="'.$user_message['url'].'";</script>';
        else $str="<script>alert('无消息地址');javascript:window.opener=null;window.open('','_self');window.close();</script>";
		echo $str;
	}
	public function message_look($id=''){
        $user_message=Db::name('user_message')->find($id);
        if(!$user_message){
            return $this->error('商品不存在！');
        }
        // 使用ZBuilder快速创建表单
        return ZBuilder::make('form')
            ->setPageTitle('消息内容') // 设置页面标题
            //->setUrl('') // 设置表单提交地址
            ->hideBtn(['back','submit']) //隐藏默认按钮
            ->addCkeditor('content', '内容','',$user_message['content'])
            ->fetch();
    }
    public function push(){
    	//判断是否为post请求
        if (Request::instance()->isPost()) {
            //获取请求的post数据
            $data=input('post.');
        	$baidu_push_bind=Db::name('baidu_push_bind')->where('user_id',$data['id'])->find();
	    	if(!$baidu_push_bind){
	    		return $this->error('该用户未绑定设备');
	    	}
	    	$user=Db::name('users')->find($data['id']);
	    	$push_config=Db::name('admin_push_config')->where('os',$baidu_push_bind['os'])->find();
	    	if(!$push_config){
	    		return $this->error('未配置百度推送参数');
	    	}
            //数据输入验证
            if($baidu_push_bind['os']=='ios'){
            	 $validate = new Validate([
	                'content|消息内容'  => 'require',
	            ]);
            }else{
            	$validate = new Validate([
	                'title|消息标题'  => 'require',
	                'content|消息内容'  => 'require',
	            ]);
            } 
            if (!$validate->check($data)) {
                return $this->error($validate->getError());
            }
            //单推
        	require_once '../lib/baidu_push/sdk.php';
			// 创建SDK对象.
			$sdk = new \PushSDK($push_config['apikey'],$push_config['secretkey']);
			$channelId = $baidu_push_bind['channel_id'];
			// message content.
			if($baidu_push_bind['os']=='ios'){
				// message content.
				$message = array (
				    'aps' => array (
				        // 消息内容
				        'alert' => $data['content'], 
				    ), 
				);
				// 设置消息类型为 通知类型.
				$opts = array (
				    'msg_type' => 1,        // iOS不支持透传, 只能设置 msg_type:1, 即通知消息.
				    'deploy_status' => 1,   // iOS应用的部署状态:  1：开发状态；2：生产状态； 若不指定，则默认设置为生产状态。
				);
			}else{
				$message = array (
				    // 消息的标题.
				    'title' => $data['title'],
				    // 消息内容 
				    'description' => $data['content'] 
				);
				// 设置消息类型为 通知类型.
				$opts = array (
				    'msg_type' => 1 
				);
			}
			// 向目标设备发送一条消息
			$rs = $sdk -> pushMsgToSingleDevice($channelId, $message, $opts);
			// 判断返回值,当发送失败时, $rs的结果为false, 可以通过getError来获得错误信息.
			if($rs === false){
			   dump($sdk->getLastErrorCode()); 
			   dump($sdk->getLastErrorMsg()); 
			}else{
			    // 将打印出消息的id,发送时间等相关信息.
			    dump($rs);
			}
			die;
        }

        $id=input('id');
    	$baidu_push_bind=Db::name('baidu_push_bind')->where('user_id',$id)->find();
    	if(!$baidu_push_bind){
    		return $this->error('该用户未绑定设备');
    	}
    	$user=Db::name('users')->find($id);
    	$push_config=Db::name('admin_push_config')->where('os',$baidu_push_bind['os'])->find();
    	if(!$push_config){
    		return $this->error('未配置百度推送参数');
    	}
        return ZBuilder::make('form')
            ->setPageTitle('推送消息') // 设置页面标题
            ->setPageTips('请认真填写') // 设置页面提示信息
            //->setUrl('add') // 设置表单提交地址
            //->hideBtn(['back']) //隐藏默认按钮
            ->setBtnTitle('submit', '确定') //修改默认按钮标题
            ->addBtn('<button type="reset" class="btn btn-default">重置</button>') //添加额外按钮
            ->addStatic('username', '接收者','',$user['username'])
            ->addText('title', '标题','苹果无效')
            ->addTextarea('content', '内容','')
                ->addHidden('id',$id)
            ->isAjax(false) //默认为ajax的post提交
            ->fetch();
    }
    public function pushAll(){
    	//判断是否为post请求
        if (Request::instance()->isPost()) {
            //获取请求的post数据
            $data=input('post.');
        	$push_config_ios=Db::name('admin_push_config')->where('os','ios')->find();
	    	$push_config_android=Db::name('admin_push_config')->where('os','android')->find();
	    	if(!$push_config_ios && !$push_config_android){
	    		return $this->error('未配置百度推送参数');
	    	}
            //数据输入验证
        	$validate = new Validate([
                'title|消息标题'  => 'require',
                'content|消息内容'  => 'require',
            ]);
            if (!$validate->check($data)) {
                return $this->error($validate->getError());
            }
            //单推
        	require_once '../lib/baidu_push/sdk.php';
			// 创建SDK对象.
			if($push_config_ios){
				$sdk = new \PushSDK($push_config_ios['apikey'],$push_config_ios['secretkey']);
				// message content.
				$message = array (
				    'aps' => array (
				        // 消息内容
				        'alert' => $data['content'], 
				    ), 
				);
				// 设置消息类型为 通知类型.
				$opts = array (
				    'msg_type' => 1,        // iOS不支持透传, 只能设置 msg_type:1, 即通知消息.
				    'deploy_status' => 1,   // iOS应用的部署状态:  1：开发状态；2：生产状态； 若不指定，则默认设置为生产状态。
				);
				// 向目标设备发送一条消息
				$rs_ios = $sdk -> pushMsgToAll($message, $opts);
				// 判断返回值,当发送失败时, $rs的结果为false, 可以通过getError来获得错误信息.
				if($rs_ios === false){
				   dump($sdk->getLastErrorCode()); 
				   dump($sdk->getLastErrorMsg()); 
				}else{
					dump($rs_ios);
				}
			}
			if($push_config_android){	
				// 创建SDK对象.
				$sdk = new \PushSDK($push_config_android['apikey'],$push_config_android['secretkey']);
				// message content.
				$message = array (
				    // 消息的标题.
				    'title' => $data['title'],
				    // 消息内容 
				    'description' => $data['content'] 
				);
				// 设置消息类型为 通知类型.
				$opts = array (
				    'msg_type' => 1 
				);
				// 向目标设备发送一条消息
				$rs_android = $sdk -> pushMsgToAll($message, $opts);
				// 判断返回值,当发送失败时, $rs的结果为false, 可以通过getError来获得错误信息.
				if($rs_android === false){
				   dump($sdk->getLastErrorCode()); 
				   dump($sdk->getLastErrorMsg()); 
				}else{
					dump($rs_android);
				}
			}
			die;
        }
    	$push_config_ios=Db::name('admin_push_config')->where('os','ios')->find();
    	$push_config_android=Db::name('admin_push_config')->where('os','android')->find();
    	if(!$push_config_ios && !$push_config_android){
    		return $this->error('未配置百度推送参数');
    	}
        return ZBuilder::make('form')
            ->setPageTitle('推送消息') // 设置页面标题
            ->setPageTips('请认真填写') // 设置页面提示信息
            //->setUrl('add') // 设置表单提交地址
            //->hideBtn(['back']) //隐藏默认按钮
            ->setBtnTitle('submit', '确定') //修改默认按钮标题
            ->addBtn('<button type="reset" class="btn btn-default">重置</button>') //添加额外按钮
            ->addStatic('username', '接收者','','所有用户')
            ->addText('title', '标题','苹果无效')
            ->addTextarea('content', '内容','')
            ->isAjax(false) //默认为ajax的post提交
            ->fetch();
    }
    public function pushTags(){
        require_once '../lib/baidu_push/sdk.php';
        $push_config_ios=Db::name('admin_push_config')->where('os','ios')->find();
    	$push_config_android=Db::name('admin_push_config')->where('os','android')->find();
    	if(!$push_config_ios && !$push_config_android){
    		return $this->error('未配置百度推送参数');
    	}
    	if($push_config_android){	
			// 创建SDK对象.
			$sdk = new \PushSDK($push_config_android['apikey'],$push_config_android['secretkey']);
			$rts=$sdk -> queryTags();
			$tags_android=[];
			foreach ($rts['result'] as $k => $v) {
				$tags_android[$v['tag']]=$v['tag'];
			}
		}
		if($push_config_ios){	
			// 创建SDK对象.
			$sdk = new \PushSDK($push_config_ios['apikey'],$push_config_ios['secretkey']);
			$rts=$sdk -> queryTags();
			$tags_ios=[];
			foreach ($rts['result'] as $k => $v) {
				$tags_ios[$v['tag']]=$v['tag'];
			}
		}
    	//判断是否为post请求
        if (Request::instance()->isPost()) {
            //获取请求的post数据
            $data=input('post.');
            //数据输入验证
        	$validate = new Validate([
        		'tag|接收组'=>'require',
                'title|消息标题'  => 'require',
                'content|消息内容'  => 'require',
            ]);
            if (!$validate->check($data)) {
                return $this->error($validate->getError());
            }
			// 创建SDK对象.
			if($push_config_ios && in_array($data['tag'],$tags_ios)){
				$sdk = new \PushSDK($push_config_ios['apikey'],$push_config_ios['secretkey']);
				// message content.
				$message = array (
				    'aps' => array (
				        // 消息内容
				        'alert' => $data['content'], 
				    ), 
				);
				// 设置消息类型为 通知类型.
				$opts = array (
				    'msg_type' => 1,        // iOS不支持透传, 只能设置 msg_type:1, 即通知消息.
				    'deploy_status' => 1,   // iOS应用的部署状态:  1：开发状态；2：生产状态； 若不指定，则默认设置为生产状态。
				);
				// 向目标设备发送一条消息
				$rs_ios = $sdk -> pushMsgToTag($data['tag'],$message, $opts);
				// 判断返回值,当发送失败时, $rs的结果为false, 可以通过getError来获得错误信息.
				if($rs_ios === false){
				   dump($sdk->getLastErrorCode()); 
				   dump($sdk->getLastErrorMsg()); 
				}else{
					dump($rs_ios);
				}
			}
			if($push_config_android && in_array($data['tag'],$tags_android)){	
				// 创建SDK对象.
				$sdk = new \PushSDK($push_config_android['apikey'],$push_config_android['secretkey']);
				// message content.
				$message = array (
				    // 消息的标题.
				    'title' => $data['title'],
				    // 消息内容 
				    'description' => $data['content'] 
				);
				// 设置消息类型为 通知类型.
				$opts = array (
				    'msg_type' => 1 
				);
				// 向目标设备发送一条消息
				$rs_android = $sdk -> pushMsgToTag($data['tag'],$message, $opts);
				// 判断返回值,当发送失败时, $rs的结果为false, 可以通过getError来获得错误信息.
				if($rs_android === false){
				   dump($sdk->getLastErrorCode()); 
				   dump($sdk->getLastErrorMsg()); 
				}else{
					dump($rs_android);
				}
			}
			die;
        }
        $tags_select=array_merge($tags_ios,$tags_android);
        return ZBuilder::make('form')
            ->setPageTitle('推送消息') // 设置页面标题
            ->setPageTips('请认真填写') // 设置页面提示信息
            //->setUrl('add') // 设置表单提交地址
            //->hideBtn(['back']) //隐藏默认按钮
            ->setBtnTitle('submit', '确定') //修改默认按钮标题
            ->addBtn('<button type="reset" class="btn btn-default">重置</button>') //添加额外按钮
            ->addSelect('tag', '接收组','',$tags_select)
            ->addText('title', '标题','苹果无效')
            ->addTextarea('content', '内容','')
            ->isAjax(false) //默认为ajax的post提交
            ->fetch();
    }
    public function pushUsers(){
    	$push_config_ios=Db::name('admin_push_config')->where('os','ios')->find();
    	$push_config_android=Db::name('admin_push_config')->where('os','android')->find();
    	if(!$push_config_ios && !$push_config_android){
    		return $this->error('未配置百度推送参数');
    	}
    	//判断是否为post请求
        if (Request::instance()->isPost()) {
            //获取请求的post数据
            $data=input('post.');
            //数据输入验证
        	$validate = new Validate([
                'title|消息标题'  => 'require',
                'content|消息内容'  => 'require',
            ]);
            if (!$validate->check($data)) {
                return $this->error($validate->getError());
            }
            $baidu_push_binds=Db::name('baidu_push_bind')->where('user_id','in',$data['ids'])->select();
            $channel_id_ios=[];
            $channel_id_android=[];
            foreach ($baidu_push_binds as $k => $v) {
            	if($v['os']=='ios'){
            		$channel_id_ios[]=$v['channel_id'];
            	}else{
            		$channel_id_android[]=$v['channel_id'];
            	}
            }
        	require_once '../lib/baidu_push/sdk.php';
			// 创建SDK对象.
			if($push_config_ios && $channel_id_ios){
				$sdk = new \PushSDK($push_config_ios['apikey'],$push_config_ios['secretkey']);
				// message content.
				$message = array (
				    'aps' => array (
				        // 消息内容
				        'alert' => $data['content'], 
				    ), 
				);
				// 设置消息类型为 通知类型.
				$opts = array (
				    'msg_type' => 1,        // iOS不支持透传, 只能设置 msg_type:1, 即通知消息.
				    'deploy_status' => 1,   // iOS应用的部署状态:  1：开发状态；2：生产状态； 若不指定，则默认设置为生产状态。
				);
				// 向目标设备发送一条消息
				$rs_ios = $sdk -> pushBatchUniMsg($channel_id_ios,$message, $opts);
				// 判断返回值,当发送失败时, $rs的结果为false, 可以通过getError来获得错误信息.
				if($rs_ios === false){
				   dump($sdk->getLastErrorCode()); 
				   dump($sdk->getLastErrorMsg()); 
				}else{
					dump($rs_ios);
				}
			}
			if($push_config_android && $channel_id_android){	
				// 创建SDK对象.
				$sdk = new \PushSDK($push_config_android['apikey'],$push_config_android['secretkey']);
				// message content.
				$message = array (
				    // 消息的标题.
				    'title' => $data['title'],
				    // 消息内容 
				    'description' => $data['content'] 
				);
				// 设置消息类型为 通知类型.
				$opts = array (
				    'msg_type' => 1 
				);
				// 向目标设备发送一条消息
				$rs_android = $sdk -> pushBatchUniMsg($channel_id_android,$message, $opts);
				// 判断返回值,当发送失败时, $rs的结果为false, 可以通过getError来获得错误信息.
				if($rs_android === false){
				   dump($sdk->getLastErrorCode()); 
				   dump($sdk->getLastErrorMsg()); 
				}else{
					dump($rs_android);
				}
			}
			die;
        }
        $ids=input('ids');
        return ZBuilder::make('form')
            ->setPageTitle('推送消息') // 设置页面标题
            ->setPageTips('请认真填写') // 设置页面提示信息
            //->setUrl('add') // 设置表单提交地址
            //->hideBtn(['back']) //隐藏默认按钮
            ->setBtnTitle('submit', '确定') //修改默认按钮标题
            ->addBtn('<button type="reset" class="btn btn-default">重置</button>') //添加额外按钮
            ->addStatic('username', '接收者','','选定的用户')
            ->addText('title', '标题','苹果无效')
            ->addTextarea('content', '内容','')
            ->addHidden('ids',$ids)
            ->isAjax(false) //默认为ajax的post提交
            ->fetch();
    }
}
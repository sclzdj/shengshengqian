<?php
namespace app\taoke\admin;

use app\admin\controller\Admin;
use think\Db;
use think\Request;
use think\Validate;
use app\common\builder\ZBuilder; // 引入ZBuilder

/**
 * Withdraw 后台模块
 */
class Withdraw extends Admin
{
	public function index()
	{
		// 获取排序
        $order = $this->getOrder();
        if($order===''){
            $order='a.addtime desc,a.id desc';
        }
        // 获取筛选
        $map = $this->getMap();
		$data_list = Db::name('user_withdrawals_log a')->join('users b','a.user_id=b.id','LEFT')->join('withdraw_type c','a.type_id=c.id','LEFT')->join('user_withdrawals_card d','a.card_id=d.id','LEFT')->field('a.*,b.username,c.type,c.card_name,c.card_num,c.bank_id,c.bank_address,c.alipay,c.alipay_name,c.wxpay,c.wxpay_openid,d.card_base_price')->where($map)->order($order)->paginate();
        // 分页数据
        $page = $data_list->render();
		// 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
        	->setPageTitle('提现列表') // 设置页面标题
        	->setPageTips('删除可能会导致其他的相关数据失效，请谨慎操作') // 设置页面提示信息
        	->setTableName('user_withdrawals_log') // 指定数据表名
            ->addOrder('a.id,a.addtime,d.card_base_price') // 添加排序
            ->addTimeFilter('a.addtime') // 添加时间段筛选
            ->setSearch(['a.id' => 'ID','b.username'=>'用户名']) // 设置搜索参数
        	->addColumns([
        			['id', 'ID'],
        			['username', '用户'],
        			['card_base_price', '提现额度','callback','str_linked','元'],
        			['type','方式','callback','array_v',['银行卡','支付宝','微信']],
        			['bank_id','信息','callback',function($bank_id,$data){
        				if($data['type']=='银行卡'){
        					return "开户银行：".db('bank')->where('id',$data['bank_id'])->value('name')."<br>开户人姓名：{$data['card_name']}<br>银行卡号：{$data['card_num']}<br>开户行地址:{$data['bank_address']}";
        				}elseif($data['type']=='支付宝'){
        					return "支付宝姓名：{$data['alipay_name']}<br>支付宝账号：{$data['alipay']}";
        				}elseif($data['type']=='微信'){
        					return "微信账号：{$data['wxpay']}<br>微信openid：{$data['wxpay_openid']}";
        				}
        			},'__data__'],
        			['addtime','申请时间','datetime'],
        			['status','审核状态','callback','array_v',['未审核','通过','不通过']],
        			['right_button', '操作', 'btn'],
        		]) //添加多列数据
        	->addRightButtons(['edit'=>['title'=>'审核'],'delete'=>['table'=>'withdrawals_card']]) // 批量添加右侧按钮
    		->addTopButtons(['delete'=>['table'=>'withdrawals_card']]) // 批量添加顶部按钮
        	->setRowList($data_list) // 设置表格数据
	        ->setPages($page) // 设置分页数据
        	->fetch();
	}
	
	//审核
	public function edit($id='')
	{
		// 查处数据
        $withdraw=Db::name('user_withdrawals_log a')->join('users b','a.user_id=b.id','LEFT')->join('withdraw_type c','a.type_id=c.id','LEFT')->join('user_withdrawals_card d','a.card_id=d.id','LEFT')->field('a.*,b.username,c.type,c.card_name,c.card_num,c.bank_id,c.bank_address,c.alipay,c.alipay_name,c.wxpay,c.wxpay_openid,d.card_base_price')->where('a.id',$id)->find();
        if($withdraw && $withdraw['status']>0){
            return $this->error('该提现已经审核过了，不能重新审核');
        }
        //判断是否为post请求
        if (Request::instance()->isPost()) {
            //获取请求的post数据
            $data=input('post.');
            //数据输入验证
            
            //数据处理
            $update=array();
            $update['id']=$data['id'];
            $update['status']=$data['status'];
            $update['auditmsg']=$data['auditmsg'];
            $withdraw=Db::name('user_withdrawals_log a')->join('users b','a.user_id=b.id','LEFT')->join('withdraw_type c','a.type_id=c.id','LEFT')->join('user_withdrawals_card d','a.card_id=d.id','LEFT')->field('a.*,b.username,c.type,c.card_name,c.card_num,c.bank_id,c.bank_address,c.alipay,c.alipay_name,c.wxpay,c.wxpay_openid,d.card_base_price')->where('a.id',$id)->find();
            if($update['status']>0){
                $update['audittime']=time();
                if($update['status']=='2'){//退回
                    $user=Db::name('users')->find($withdraw['user_id']);
                    Db::name('users')->update(['id'=>$user['id'],'red_balance'=>$user['red_balance']+$withdraw['card_base_price']]);
                }
                if($update['status']=='1'){
                	if($withdraw['type']==1){//支付宝

                	}
                	if($withdraw['type']==2){//微信
                		
                	}
                }
            }else{
                $update['audittime']=0;
            }
            //数据更新
            $rt=Db::name("user_withdrawals_log")->update($update);
            //跳转
            if($rt===false){
                return $this->error('提现审核失败');
            }else{
                return $this->success('提现审核成功','index','',1);
            }
        }
        // 接收id
        if ($id>0) {
            // 使用ZBuilder快速创建表单
            return ZBuilder::make('form')
                ->setPageTitle('提现审核') // 设置页面标题
                ->setPageTips('审核会记录下当前管理员，请勿随意操作') // 设置页面提示信息
                //->setUrl('edit') // 设置表单提交地址
                //->hideBtn(['back']) //隐藏默认按钮
                ->setBtnTitle('submit', '确定') //修改默认按钮标题
                ->addBtn('<button type="reset" class="btn btn-default">重置</button>') //添加额外按钮
                ->addStatic('type','提现方式','', $withdraw['type']==0?'银行卡':$withdraw['type']==1?'支付宝':'微信')
                ->addStatic('card_base_price','提现金额','', $withdraw['card_base_price'])
                ->addRadio('status', '审核状态', '', ['0' => '未审核', '1' => '通过','2'=>'不通过'],$withdraw['status'])
                ->addTextarea('auditmsg', '审核意见','', $withdraw['auditmsg'])
                ->addHidden('id',$withdraw['id'])
                //->isAjax(false) //默认为ajax的post提交
                ->fetch();
        }
	}
}
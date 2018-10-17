<?php
namespace app\taoke\admin;

use app\admin\controller\Admin;
use think\Db;
use think\Request;
use think\Validate;
use app\common\builder\ZBuilder; // 引入ZBuilder

/**
 * Quangoods 后台模块
 */
class Quangoods extends Admin
{
	public function index()
	{
		// 获取排序
        $order = $this->getOrder();
        if($order===''){
            $order='id desc';
        }
        // 获取筛选
        $map = $this->getMap();
		// 读取用户数据
		$data_list = Db::name('quan_goods')->where($map)->order($order)->paginate();
		// 分页数据
		$page = $data_list->render();
		// 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
        	->setPageTitle('优惠券商品列表') // 设置页面标题
        	->setPageTips('修改和删除可能会导致其他的相关数据失效，请谨慎操作') // 设置页面提示信息
        	->addOrder('') // 添加排序
            ->setSearch(['item_id' => '编号','title'=>'名称']) // 设置搜索参数
        	->addColumns([
        			['id', 'ID'],
        			['right_button', '操作', 'btn'],
        		]) //添加多列数据
            ->addRightButton('custom',['title'=>'查看详情','href'=>url('look',['id'=>'__ID__']),'icon'=>'fa fa-fw fa-eye'],true)
        	//->addRightButtons(['edit','delete'=>['table'=>'quan_goods']]) // 批量添加右侧按钮
    		->addTopButtons(['custom'=>['title'=>'无筛选','href'=>url('index')]]) // 批量添加顶部按钮
            ->addTopButton('custom',['title'=>'采集','href'=>url('gather')])
        	->setRowList($data_list) // 设置表格数据
        	->setPages($page) // 设置分页数据
        	->fetch();
	}
    public function gather(){

        //判断是否为post请求
        if (Request::instance()->isPost()) {
            //获取请求的post数据
            $data=input('post.');
            //数据输入验证
            $validate = new Validate([
                'num|采集数量'  => 'require',
                'key|大淘客Key'  => 'require',
            ]);
            if (!$validate->check($data)) {
                return $this->error($validate->getError());
            }
            $for=$data['num']/200;
            for ($i=0; $i < $for; $i++) { 
                //初始化
                $curl = curl_init();
                //设置抓取的url
                curl_setopt($curl, CURLOPT_URL, 'http://api.dataoke.com/index.php?r=goodsLink/www&type=www_quan&appkey='.$data['key'].'&v=2&page='.($i+1));
                //设置头文件的信息作为数据流输出
                curl_setopt($curl, CURLOPT_HEADER, 0);
                //设置获取的信息以文件流的形式返回，而不是直接输出。
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                //执行命令
                $data = curl_exec($curl);
                if($data==false){
                    return $this->error(curl_error($curl));
                }
                //关闭URL请求
                curl_close($curl);
                //显示获得的数据
                $data=json_decode($data,true);
                if($data===null){
                    break;
                }
                if($i==0){
                    if(!$data['data']['result']){
                        return $this->error('未采集到数据');
                    }
                    Db::name('quan_goods')->where('id','neq','0')->delete();
                }
                if($data['data']['result']){
                    Db::name('quan_goods')->insertAll($data['data']['result']);
                }else{
                    break;
                }
            }
            Db::name('quan_goods')->where('id','neq','0')->update(['created'=>time()]);
            return $this->success('采集成功','index','',1);
        }
        $taobao_itemclass=db('taobao_itemclass')->field('id,name,parent_id')->order('weight asc')->select();
        $taobao_itemclass=myRuleCategory($taobao_itemclass);
        $select_class=[];
        foreach ($taobao_itemclass as $k => $v) {
            $select_class[$v['id']]=str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $v['level']-1).$v['name'];
        }
        // 使用ZBuilder快速创建表单
        return ZBuilder::make('form')
            ->setPageTitle('优惠券商品采集') // 设置页面标题
            ->setPageTips('请认真填写相关信息') // 设置页面提示信息
            //->setUrl('add') // 设置表单提交地址
            //->hideBtn(['back']) //隐藏默认按钮
            ->setBtnTitle('submit', '确定') //修改默认按钮标题
            ->addText('key', '大淘客Key','','ajn2erp8eq')
            ->addSelect('num', '采集数量','',['200'=>'200','400'=>'400','600'=>'600','800'=>'800','1000'=>'1000',])
            ->isAjax(false) //默认为ajax的post提交
            ->fetch();
    }
    public function look($id=''){
        $taobao_item=Db::name('quan_goods')->find($id);
        if(!$taobao_item){
            return $this->error('商品不存在！');
        }
        // 使用ZBuilder快速创建表单
        return ZBuilder::make('form')
            ->setPageTitle('添加用户') // 设置页面标题
            ->setPageTips('该操作可能会导致其他的相关数据失效，请勿随意修改信息') // 设置页面提示信息
            //->setUrl('') // 设置表单提交地址
            ->hideBtn(['back','submit']) //隐藏默认按钮
            ->addStatic('id', '商品ID','',$taobao_item['id'])
            ->addStatic('item_id', '商品编号','',$taobao_item['item_id'])
            ->addStatic('title', '商品名称','','<a href="'.url('link', ['id' => $taobao_item['id']]).'" target="_bank">'.$taobao_item['title'].'</a>')
            ->addStatic('pic', '商品图片','',"<a href='{$taobao_item['pic']}' target='_bank'><img src='{$taobao_item['pic']}' width='200' title='{$taobao_item['title']}'></a>")
            ->addStatic('class_name', '商品分类','',htmlspecialchars($taobao_item['class_name']))
            ->addStatic('price', '商品价格','',$taobao_item['price'].'元')
            ->addStatic('month_sales', '月销量','',$taobao_item['month_sales'])
            ->addStatic('commission_rate', '返利率','',$taobao_item['commission_rate'].'%')
            ->addStatic('commission_price', '返利额','',$taobao_item['commission_price'].'元')
            ->addStatic('seller_id', '卖家编号','',$taobao_item['seller_id'])
            ->addStatic('seller_nick', '卖家昵称','',htmlspecialchars($taobao_item['seller_nick']))
            ->addStatic('seller_shopname', '卖家店铺','',htmlspecialchars($taobao_item['seller_shopname']))
            ->addStatic('seller_type', '店铺所属','',$taobao_item['seller_type'])
            ->addStatic('share_red_have', '分享红包','',$taobao_item['share_red_have']>0?'有':'无')
            ->addStatic('share_red_price', '分享红包金额','',$taobao_item['share_red_price'].'元')
            ->addStatic('coupon_have', '优惠券','',$taobao_item['coupon_have']>0?'有':'无')
            ->addStatic('coupon_id', '优惠券编号','',"<a href='{$taobao_item['coupon_url']}' target='_bank' title='点击查看'>".$taobao_item['coupon_id']."</a>")
            ->addStatic('coupon_total', '优惠券总额','',$taobao_item['coupon_total'].'元')
            ->addStatic('coupon_rest', '优惠券余额','',$taobao_item['coupon_rest'].'元')
            ->addStatic('coupon_tips', '优惠券描述','',htmlspecialchars($taobao_item['coupon_tips']))
            ->addStatic('coupon_begin', '优惠券开始时间','',$taobao_item['coupon_begin'])
            ->addStatic('coupon_end', '优惠券结束时间','',$taobao_item['coupon_end'])
            ->addStatic('last_price', '到手价','',$taobao_item['last_price'].'元')
            ->addStatic('read_number', '浏览量','',$taobao_item['read_number'])
            ->addStatic('is_show', '是否显示','',$taobao_item['is_show']>0?'是':'否')
            ->addStatic('created', '时间','',$taobao_item['created']>0?date('Y-m-d H:i',$taobao_item['created']):'未知')
            ->fetch();
    }
    
}
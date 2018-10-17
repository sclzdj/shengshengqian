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
class Taobaoitems extends Admin
{
	//用户管理
	public function index()
	{
		// 获取排序
        $order = $this->getOrder();
        if($order===''){
            $order='id desc';
        }
        // 获取筛选
        $map = $this->getMap();
        if(isset($map['created'])){
            $map['created'][1][0]=strtotime($map['created'][1][0]);
            $map['created'][1][1]=strtotime($map['created'][1][1]);
        }
		// 读取用户数据
		$data_list = Db::name('taobao_items')->where($map)->order($order)->paginate();
		// 分页数据
		$page = $data_list->render();
		// 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
        	->setPageTitle('淘客产品列表') // 设置页面标题
        	->setPageTips('修改和删除可能会导致其他的相关数据失效，请谨慎操作') // 设置页面提示信息
        	->addOrder('id,price,commission_rate,commission_price,last_price,month_sales,read_number') // 添加排序
            ->addTimeFilter('created') // 添加时间段筛选
            ->setSearch(['id' => 'ID','item_id' => '编号','title'=>'名称']) // 设置搜索参数
        	->addColumns([
        			['id', 'ID'],
                    ['item_id', '编号','callback',function($item_id){
                        $taobao_item=Db::name('taobao_items a')->join('taobao_url b','a.item_id=b.itemid','LEFT')->where('a.item_id',$item_id)->field('a.*,b.tk_url')->find();
                        $str=$item_id.'<br>';
                        //加两个按钮
                       
                        return $str;
                    }],
        			['title', '商品名称', 'link', url('link', ['id' => '__id__'])],
                    ['price', '价格','callback','str_linked','元'],
        			['commission_rate', '返利率','callback','str_linked','%'],
                    ['commission_price', '返利额','callback','str_linked','元'],
                    ['last_price', '到手价','callback','str_linked','元'],
                    ['month_sales','月销量'],
                    ['read_number','浏览量'],
                    ['share_red_have', '分享红包', 'status', '', ['无', '有']],
                    ['coupon_have', '优惠券', 'status', '', ['无', '有']],
                    ['created', '时间', 'date', '未知'],
                    ['is_show', '显示', 'status', '', ['否', '是']],
        			['right_button', '操作', 'btn'],
        		]) //添加多列数据
            ->addRightButton('custom',['title'=>'查看详情','href'=>url('look',['id'=>'__ID__']),'icon'=>'fa fa-fw fa-eye'],true)
        	->addRightButtons(['edit','delete'=>['table'=>'taobao_items']]) // 批量添加右侧按钮
    		->addTopButtons(['add','delete'=>['table'=>'taobao_items'],'custom'=>['title'=>'无筛选','href'=>url('index')]]) // 批量添加顶部按钮
            ->addTopButton('custom',['title'=>'导入excel文件','href'=>url('excel')])
            ->addTopButton('custom',['title'=>'大淘客采集','href'=>url('gather_1')])
        	->setRowList($data_list) // 设置表格数据
        	->setPages($page) // 设置分页数据
        	->fetch();
	}
	public function link(){
		$id=input('id');
		$taobao_items = Db::name('taobao_items a')->join('taobao_url b','a.item_id=b.itemid','LEFT')->where('a.id',$id)->field('b.tk_url')->find();
		if($taobao_items['tk_url']!='') $str='<script>location.href="'.$taobao_items['tk_url'].'";</script>';
        else $str="<script>alert('无商品地址');javascript:window.opener=null;window.open('','_self');window.close();</script>";
		echo $str;
	}
    public function look($id=''){
        $taobao_item=Db::name('taobao_items')->find($id);
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
    //大淘客采集
    public function gather_1(){
        //判断是否为post请求
        if (Request::instance()->isPost()) {
            $now=time();
            //获取请求的post数据
            $post=input('post.');
            //数据输入验证
            $validate = new Validate([
                'num|采集数量'  => 'require',
                'key|大淘客key'  => 'require',
                'cid_1|服装,分类转换'  => 'require',
                'cid_2|母婴,分类转换'  => 'require',
                'cid_3|化妆品,分类转换'  => 'require',
                'cid_4|居家、日用,分类转换'  => 'require',
                'cid_5|鞋包、配饰,分类转换'  => 'require',
                'cid_6|美食,分类转换'  => 'require',
                'cid_7|文体、车品,分类转换'  => 'require',
                'cid_8|数码、家电,分类转换'  => 'require',
            ]);
            if (!$validate->check($post)) {
                return $this->error($validate->getError());
            }
            $for=$post['num']/200;
            for ($i=0; $i < $for; $i++) { 
                //初始化
                $curl = curl_init();
                //设置抓取的url
                curl_setopt($curl, CURLOPT_URL, 'http://api.dataoke.com/index.php?r=Port/index&type=total&appkey='.$post['key'].'&v=2&page='.($i+1));
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
                if($i==0 && !$data['result']){
                    return $this->error('未采集到数据');
                }
                if($data['result']){
                    foreach ($data['result'] as $k => $v) {
                        Db::name('taobao_items')->where('item_id',$v['GoodsID'])->delete();
                        $insert=[];
                        $insert['item_id']=$v['GoodsID'];
                        $insert['title']=$v['Title'];
                        $insert['pic']=$v['Pic'];
                        $insert['class_name']=isset($post['cid_'.$v['Cid']])?$post['cid_'.$v['Cid']]:'其它';
                        $insert['price']=$v['Org_Price'];
                        $insert['seller_id']=$v['SellerID'];
                        $insert['seller_type']=$v['IsTmall']=='1'?'天猫':'淘宝';
                        $insert['coupon_id']=$v['Quan_id'];
                        $insert['coupon_total']=$v['Quan_surplus']+$v['Quan_receive'];
                        $insert['coupon_rest']=$v['Quan_surplus'];
                        $insert['coupon_tips']=$v['Quan_condition'];
                        $insert['coupon_price']=$v['Quan_price'];
                        $insert['coupon_end']=$v['Quan_time'];
                        $insert['coupon_url']=$v['Quan_link'];
                        $insert['created']=$now;
                        $insert['last_price']=$v['Price'];
                        $insert['month_sales']=$v['Sales_num'];
                        $insert['agent_id']=session('user_auth.uid');
                        $get_id=Db::name('taobao_items')->insertGetId($insert);
                        
                    }
                }else{
                    break;
                }
            }
           
            return $this->success('采集成功','index','',1);
        }
        $taobao_itemclass=db('taobao_itemclass')->field('id,name,parent_id')->order('weight asc')->select();
        $taobao_itemclass=myRuleCategory($taobao_itemclass);
        $select_class=[];
        foreach ($taobao_itemclass as $k => $v) {
            $select_class[$v['name']]=str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $v['level']-1).$v['name'];
        }
        // 使用ZBuilder快速创建表单
        return ZBuilder::make('form')
            ->setPageTitle('优惠券商品采集') // 设置页面标题
            ->setPageTips('请认真填写相关信息') // 设置页面提示信息
            //->setUrl('add') // 设置表单提交地址
            //->hideBtn(['back']) //隐藏默认按钮
            ->setBtnTitle('submit', '确定') //修改默认按钮标题
            ->addText('key', '大淘客Key','','ajn2erp8eq')
            ->addSelect('num', '采集数量','',['200'=>'200','400'=>'400','600'=>'600','800'=>'800','1000'=>'1000'],'200')
            ->addSelect('cid_1', '服装 ','',$select_class,'其它')
            ->addSelect('cid_2', '母婴 ','',$select_class,'其它')
            ->addSelect('cid_3', '化妆品 ','',$select_class,'其它')
            ->addSelect('cid_4', '居家、日用 ','',$select_class,'其它')
            ->addSelect('cid_5', '鞋包、配饰 ','',$select_class,'其它')
            ->addSelect('cid_6', '美食 ','',$select_class,'其它')
            ->addSelect('cid_7', '文体、车品 ','',$select_class,'其它')
            ->addSelect('cid_8', '数码、家电 ','',$select_class,'其它')
            ->isAjax(false) //默认为ajax的post提交
            ->fetch();
    }
    //导入excel
    public function excel(){
        //判断是否为post请求
        if (Request::instance()->isPost()) {
            //获取请求的post数据
            $data=input('post.');
            $attachment=Db::name('admin_attachment')->find($data['excel']);
            $file = $attachment['path'];
            set_time_limit(0);
            include_once ROOT_PATH.'lib/excel/excel_reader2.php';
            $data = new \Spreadsheet_Excel_Reader();
            $data->setUTFEncoder('mb');
            $data->setOutputEncoding('utf-8');
            $data->read($file);
            $sql = "INSERT ignore INTO `%s`.`%s`(item_id,title,pic,class_name,price,month_sales,commission_rate,commission_price,seller_nick,seller_id,seller_shopname,seller_type,coupon_id,coupon_total,coupon_rest,coupon_tips,coupon_begin,coupon_end,coupon_url) VALUES";
            $sql = sprintf($sql,config('database.database'),config('database.prefix').'taobao_items');
            
            $urlSql = "INSERT ignore INTO `%s`.`%s`(itemid,agent_id,tk_url,coupon_url) VALUES";
            $urlSql = sprintf($urlSql,config('database.database'),config('database.prefix').'taobao_url');
            $items = [];
            $fileds = "('%s','%s','%s','%s',%.2f,%d,%.2f,%.2f,'%s','%s','%s','%s','%s',%d,%d,'%s','%s','%s','%s')";
            
            $urlItems = [];
            $urlFields = "('%s',%d,'%s','%s')";
            foreach ($data->sheets[0]["cells"] as $k=>$v)
            {
                if($k > 1)
                {
                    $v[2] = addcslashes($v[2],"'");
                    $v[11] = addcslashes($v[11],"'");
                    $v[13] = addcslashes($v[13],"'");
                    
                    $items[] = sprintf($fileds,$v[1],$v[2],$v[3],$v[5],$v[7],$v[8],$v[9],$v[10],$v[11],$v[12],$v[13],$v[14],$v[15],$v[16],$v[17],$v[18],$v[19],$v[20],$v[21]);
                    $urlItems[] = sprintf($urlFields,$v[1],1,$v[6],$v[22]);
                    if( ($k % 1000) == 0)
                    {
                        //每1000行执行一次
                        $s = implode(',', $items);
                        Db::execute($sql.$s);
                        $items = [];
                        
                        $ss = implode(',', $urlItems);
                        Db::execute($urlSql.$ss);
                        $urlItems = [];
                    }
                }
            }
            if(count($items))
            {
                $s = implode(',', $items);
                Db::execute($sql.$s);
                $items = [];
            }
            if(count($urlItems))
            {
                $ss = implode(',', $urlItems);
                Db::execute($urlSql.$ss);
                $urlItems = [];
            }

        }
        // 使用ZBuilder快速创建表单
        return ZBuilder::make('form')
            ->setPageTitle('导入excel') // 设置页面标题
            ->setPageTips('导入后系统会自动更新淘客产品数据') // 设置页面提示信息
            //->setUrl('') // 设置表单提交地址
            //->hideBtn(['back']) //隐藏默认按钮
            ->setBtnTitle('submit', '确定') //修改默认按钮标题
            ->addBtn('<button type="reset" class="btn btn-default">重置</button>') //添加额外按钮
            ->addFile('excel', '请选择excel文件', '只能选择后缀为xls的文件', '', '2048', 'xls')
            ->isAjax(false) //默认为ajax的post提交
            ->fetch();
    }
    //修改商品
    public function edit($id=""){
        //判断是否为post请求
        if (Request::instance()->isPost()) {
            //获取请求的post数据
            $data=input('post.');
            //数据输入验证
            if(!preg_match("/^\d+$/",$data['read_number'])){
                return $this->error('浏览量必须为整数');
            }
            if(!preg_match("/^\d+(\.\d+)?$/",$data['share_red_price'])){
                return $this->error('分享红包格式错误');
            }
            //数据处理
            $update=array();
            $update['id']=$data['id'];
            $update['read_number']=$data['read_number'];
            $update['share_red_price']=$data['share_red_price'];
            //数据更新
            $rt=Db::name("taobao_items")->update($update);
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
            $taobao_items=Db::name("taobao_items")->where('id',$id)->find();
            // 使用ZBuilder快速创建表单
            return ZBuilder::make('form')
                ->setPageTitle('修改淘客商品') // 设置页面标题
                ->setPageTips('该操作可能会导致其他的相关数据失效，请勿随意修改信息') // 设置页面提示信息
                //->setUrl('edit') // 设置表单提交地址
                //->hideBtn(['back']) //隐藏默认按钮
                ->setBtnTitle('submit', '确定') //修改默认按钮标题
                ->addBtn('<button type="reset" class="btn btn-default">重置</button>') //添加额外按钮
                ->addText('share_red_price', '分享红包金额','请尽量不要在此处修改，精确到分',$taobao_items['share_red_price'],['', '元'])
                ->addText('read_number', '浏览量','请输入整数',$taobao_items['read_number'])
                ->addHidden('id',$taobao_items['id'])
                //->isAjax(false) //默认为ajax的post提交
                ->fetch();
        }
    }
}
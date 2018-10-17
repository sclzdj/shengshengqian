<?php
namespace app\taoke\admin;

use app\admin\controller\Admin;
use think\Db;
use think\Request;
use think\Validate;
use app\common\builder\ZBuilder; // 引入ZBuilder

/**
 * Taobaolm 后台模块
 */
class Taobaolm extends Admin
{
	public function index()
	{
		$q=input('q','');
		$channel=input('channel','');
		$rule_title=input('rule_title','');
		$rule_class_name=input('rule_class_name','其它');
		$rule_num=input('rule_num','100');
		$b2c=input('b2c','');
		$startTkRate=input('startTkRate','');
		$endTkRate=input('endTkRate','');
		$start_price=input('start_price','');
		$end_price=input('end_price','');
		$jhs=input('jhs','');
		$dpyhq=input('dpyhq','');
		$dxjh=input('dxjh','');
		$npxType=input('npxType','');
		$picQuality=input('picQuality','');
		$typeTag=input('typeTag','');
		$jpmj=input('jpmj','');
		$xfzbz=input('xfzbz','');
		$hGoodRate=input('hGoodRate','');
		$sortType=input('sortType','');
		$toPage=input('toPage','1');
		$parts=[
			'q'=>$q,
			'channel'=>$channel,
			'b2c'=>$b2c,
			'startTkRate'=>$startTkRate,
			'endTkRate'=>$endTkRate,
			'start_price'=>$start_price,
			'end_price'=>$end_price,
			'jhs'=>$jhs,
			'dpyhq'=>$dpyhq,
			'dxjh'=>$dxjh,
			'npxType'=>$npxType,
			'picQuality'=>$picQuality,
			'typeTag'=>$typeTag,
			'jpmj'=>$jpmj,
			'xfzbz'=>$xfzbz,
			'hGoodRate'=>$hGoodRate,
			'sortType'=>$sortType,
			't'=>time(),
			'perPageSize'=>100,
			'toPage'=>$toPage,
		];
		switch ($channel) {
			case 'qqhd':
				$url='http://pub.alimama.com/items/channel/qqhd.json';
				break;
			case '9k9':
				$url='http://pub.alimama.com/items/channel/9k9.json';
				break;
			default:
				$url='http://pub.alimama.com/items/search.json';
				break;
		}
		$url=$url.'?'.http_build_query($parts);
		//初始化
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);
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
        	return $this->error('未采集到正确数据');
        }
		if(!$data['data']['pageList']){
			return $this->error('未采集到数据');
		}
		$this->assign('data',$data['data']['pageList']);

		$taobao_itemclass=db('taobao_itemclass')->field('id,name,parent_id')->order('weight asc')->select();
        $taobao_itemclass=myRuleCategory($taobao_itemclass);
        $select_class=[];
        foreach ($taobao_itemclass as $k => $v) {
            $select_class[$v['name']]=str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $v['level']-1).$v['name'];
        }
        $this->assign([
        	'self_url'=>'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'],
        	'select_class'=>$select_class,
        	'q'=>$q,
        	'channel'=>$channel,
        	'rule_title'=>$rule_title,
        	'rule_class_name'=>$rule_class_name,
        	'rule_num'=>$rule_num,
        	'b2c'=>$b2c,
        	'startTkRate'=>$startTkRate,
        	'endTkRate'=>$endTkRate,
        	'start_price'=>$start_price,
        	'end_price'=>$end_price,
        	'jhs'=>$jhs,
        	'dpyhq'=>$dpyhq,
        	'dxjh'=>$dxjh,
        	'npxType'=>$npxType,
        	'picQuality'=>$picQuality,
        	'typeTag'=>$typeTag,
        	'jpmj'=>$jpmj,
        	'xfzbz'=>$xfzbz,
        	'hGoodRate'=>$hGoodRate,
        	'sortType'=>$sortType,
        	'toPage'=>$toPage,
        ]);
		return $this->fetch();
    }
}
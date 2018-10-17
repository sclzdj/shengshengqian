<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
// use think\Db;
class Taotest extends Controller
{
    //mm_29646581_25236378_101920735
    
    public function s()
    {
        include_once ROOT_PATH . '/lib/alimm-sdk-web/TopSdk.php';
        $c = new \TopClient();
        $c->appkey = '23876112';
        $c->secretKey = 'ba30f01f89324ab6a3b574062103e91d';
        
        $req = new \TbkItemGetRequest;
        $req->setFields("num_iid,title,pict_url,small_images,reserve_price,zk_final_price,user_type,provcity,item_url,seller_id,volume,nick");
        $req->setQ("女装");
        $resp = $c->execute($req);
        dump($resp);
    }
    public function webapi()
    {
        header("Content-type: text/html; charset=utf-8");
        set_time_limit(0);
        $sid = 0;
        include_once ROOT_PATH . '/lib/alimm-sdk-web/TopSdk.php';
        $c = new \TopClient();
        $c->appkey = '23876112';
        $c->secretKey = 'ba30f01f89324ab6a3b574062103e91d';
        
        
        $req = new \TbkUatmFavoritesGetRequest;
       
        $req->setPageNo("1");
        $req->setPageSize("100");
        $req->setFields("favorites_title,favorites_id,type");
//         $req->setType("1");
        $resp = $c->execute($req);
        dump($resp);
        
        
        $req = new \TbkUatmFavoritesItemGetRequest;
        $req->setPlatform("1");
        $req->setPageSize("20");
        $req->setAdzoneId("47306715");
        $req->setUnid("3456");
        $req->setFavoritesId("32152");
        $req->setPageNo("1");
        $req->setFields("num_iid,title,pict_url,small_images,reserve_price,zk_final_price,user_type,provcity,item_url,seller_id,volume,nick,shop_title,zk_final_price_wap,event_start_time,event_end_time,tk_rate,status,type");
        
//         $req->setPageSize("100");
//         $req->setAdzoneId("47306715");
//         $req->setUnid("12324354354");
//         $req->setFavoritesId("32152");
// //         $req->setPageNo("2");
//         $req->setFields("num_iid,title,pict_url,small_images,reserve_price,zk_final_price,user_type,provcity,item_url,seller_id,volume,nick,shop_title,zk_final_price_wap,event_start_time,event_end_time,tk_rate,status,type");
        $resp = $c->execute($req);
        
//         $req = new \TbkItemGetRequest;
//         $req->setFields("num_iid,title,pict_url,small_images,reserve_price,zk_final_price,user_type,provcity,item_url,seller_id,volume,nick");
//         $req->setQ("小禾电子灭蚊灯驱蚊器灭蚊神器");
// //         $req->setCat("16,18");
// //         $req->setItemloc("杭州");
// //         $req->setSort("tk_rate_des");
// //         $req->setIsTmall("false");
// //         $req->setIsOverseas("false");
// //         $req->setStartPrice("10");
// //         $req->setEndPrice("10");
// //         $req->setStartTkRate("123");
// //         $req->setEndTkRate("123");
// //         $req->setPlatform("1");
// //         $req->setPageNo("123");
// //         $req->setPageSize("20");
//         $resp = $c->execute($req);
        dump($resp);
    }
    /**
     * 应该支持小数
     * @param unknown $str
     * @return mixed
     */
    function number($str)
    {
//         $new = '';
//         for ($i=0;$i<=strlen($str);$i++)
//         {
//             $k = substr($str, $i,1);
//             if(is_numeric($k) || $k=='.')
//             {
//                 $new.=$k;
//             }
//         }
        return preg_replace('/\D/s', '', $str);
    }
    public function import()
    {
        set_time_limit(0);
        $file = '2017-05-17.xls';
        include_once ROOT_PATH.'lib/excel/excel_reader2.php';
        $data = new \Spreadsheet_Excel_Reader();
        $data->setUTFEncoder('mb');
        $data->setOutputEncoding('utf-8');
        $data->read($file);
        //INSERT ignore 
        $sql = "REPLACE INTO `%s`.`%s`(item_id,title,pic,class_name,price,month_sales,commission_rate,commission_price,seller_nick,seller_id,seller_shopname,seller_type,coupon_id,coupon_total,coupon_rest,coupon_tips,coupon_begin,coupon_end,coupon_url,last_price,coupon_price,coupon_have) VALUES";
        $sql = sprintf($sql,config('database.database'),config('database.prefix').'taobao_items');
        
        $urlSql = "REPLACE  INTO `%s`.`%s`(itemid,agent_id,tk_url,coupon_url) VALUES";
        $urlSql = sprintf($urlSql,config('database.database'),config('database.prefix').'taobao_url');
        $items = [];
        $fileds = "('%s','%s','%s','%s',%.2f,%d,%.2f,%.2f,'%s','%s','%s','%s','%s',%d,%d,'%s','%s','%s','%s',%.2f,%.2f,%d)";
        
        $urlItems = [];
        $urlFields = "('%s',%d,'%s','%s')";
        foreach ($data->sheets[0]["cells"] as $k=>$v)
        {
            if($k > 1)
            {
                $v[2] = addcslashes($v[2],"'");
                $v[11] = addcslashes($v[11],"'");
                $v[13] = addcslashes($v[13],"'");
                
                $coupon_price = explode('元', $v[18]);
                $coupon_price = $coupon_price[count($coupon_price)-2];
                $coupon_price = $this->number($coupon_price);
                $last_price = $v[7] - $coupon_price;
                $items[] = sprintf($fileds,$v[1],$v[2],$v[3],$v[5],$v[7],$v[8],$v[9],$v[10],$v[11],$v[12],$v[13],$v[14],$v[15],$v[16],$v[17],$v[18],$v[19],$v[20],$v[21],$last_price,$coupon_price,1);
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
    public function t()
    {
        //mm_29646581_12352603_47306715
        header("Content-type: text/html; charset=utf-8");
        set_time_limit(0);
        $sid = 0;
        include_once ROOT_PATH . '/lib/taobao/TopSdk.php';
        $c = new \TopClient();
        $c->appkey = '12434162';
        $c->secretKey = '87ece66de04f8665f0aa3bc91e07a6ea';
        
        $req = new \ItemcatsGetRequest;
//         $req->setCids("18957,19562");
        $req->setDatetime("2000-01-01 00:00:00");
        $req->setFields("cid,parent_cid,name,is_parent");
//         $req->setParentCid("50011999");
        $resp = $c->execute($req);
        
//         $req = new \TbkItemCouponGetRequest;
        
//         $req->setPid("mm_123_123_123");
//         $resp = $c->execute($req);
        
//         $req = new \TbkItemGetRequest;
//         $req->setFields("num_iid,title,pict_url,small_images,reserve_price,zk_final_price,user_type,provcity,item_url,seller_id,volume,nick");
//         $req->setQ("女装");
// //         $req->setCat("16,18");
// //         $req->setItemloc("杭州");
//         $req->setSort("tk_rate_des");
//         $req->setIsTmall("false");
//         $req->setIsOverseas("false");
// //         $req->setStartPrice("10");
// //         $req->setEndPrice("10");
// //         $req->setStartTkRate("123");
// //         $req->setEndTkRate("123");
// //         $req->setPlatform("1");
//         $req->setPageNo("1");
//         $req->setPageSize("20");
//         $resp = $c->execute($req);
        dump($resp);
    }
}
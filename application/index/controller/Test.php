<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
// use think\Db;
class Test extends Controller
{

    function test()
    {
        header("Content-type: text/html; charset=gb2312");
        $url = "https://shop111714585.taobao.com/i/asynSearch.htm?_ksTS=1493804487237_181&callback=jsonp182&mid=w-10795111360-0&wid=10795111360&path=/search.htm&search=y&viewType=gird&pageNo=2";
        $html = file_get_contents($url);
        $regex = "/<dl.*?>.*?<\/dl>/ism";
        preg_match_all($regex, $html, $dl);
        $arr = array();
        $i = 0;
        foreach ($dl[0] as $key => $value) {
            $regex = '/<img.*?>/';
            preg_match_all($regex, $value, $img);
            $regex = '/<a.*?>.*?<\/a>/';
            preg_match_all($regex, $value, $a);
            $regex = '/<span class=.*?>.*?<\/span>/';
            preg_match_all($regex, $value, $span);
            $regex = '/data-id=.*?>/';
            preg_match_all($regex, $value, $data_id);
            $regex = '/<span>.*?<\/span>/';
            preg_match_all($regex, $value, $commentspan);
            preg_match_all('/alt=\\\\\".*?\\\\\"/ism', $img[0][0], $titleArr);
            $title = str_replace('alt=\"', '', $titleArr[0][0]);
            $title = str_replace('\"', '', $title);
            preg_match_all('/src=\\\\\".*?\\\\\"/ism', $img[0][0], $srcArr);
            $src = str_replace('src=\"', '', $srcArr[0][0]);
            $src = str_replace('\"', '', $src);
            preg_match_all('/href=\\\\\".*?\\\\\"/ism', $a[0][0], $urlArr);
            $url = str_replace('href=\"', '', $urlArr[0][0]);
            $url = str_replace('\"', '', $url);
            if (isset($span[0][1])) {
                $c_price = str_replace('<span class=\"c-price\">', '', $span[0][1]);
                $c_price = str_replace('</span>', '', $c_price);
                $arr[$i]['c_price'] = $c_price;
            } else {
                $arr[$i]['c_price'] = 0;
            }
            if (isset($span[0][3])) {
                $r_price = str_replace('<span class=\"s-price\">', '', $span[0][3]);
                $r_price = str_replace('</span>', '', $r_price);
                $arr[$i]['r_price'] = $r_price;
            } else {
                $arr[$i]['r_price'] = 0;
            }
            $itemid = str_replace('data-id=\"', '', $data_id[0][0]);
            $itemid = str_replace('\">', '', $itemid);
            $comment_num = str_replace('<span>', '', $commentspan[0][0]);
            $comment_num = str_replace('</span>', '', $comment_num);
            // $c_price
            $arr[$i]['title'] = $title;
            $arr[$i]['src'] = $src;
            $arr[$i]['url'] = $url;
            $arr[$i]['itemid'] = $itemid;
            $arr[$i]['comment_num'] = $comment_num;
            $i ++;
        }
        print_r($arr);
    }

    public function tb2()
    {
        header("Content-type: text/html; charset=utf-8");
        $url = "https://shop111714585.taobao.com/i/asynSearch.htm?_ksTS=1493806806016_180&callback=jsonp181&mid=w-10795111360-0&wid=10795111360&path=/search.htm&search=y&spm=a1z10.3-c.w4002-10795111360.24.A1k4Zf&pageNo=3&viewType=grid";
        $html = file_get_contents($url);
        // $result = file_get_contents('1.log');
        
        // print_r($arr);
        // preg_match('/<\s*img\s+[^>]*?src\s*=\s*(\'|\")(.*?)\\1[^>]*?\/?\s*>/i',$result,$data);
        // $result = str_replace('</div>', "\r\n</div>", $result);
        dump($arr);
        die();
    }

    public function tb1()
    {
        set_time_limit(0);
        header("Content-type: text/html; charset=utf-8");
        $sid = 111193277;
        include_once ROOT_PATH . '/lib/taobao/tbitemcollection.php';
        $r = new \tbitemcollection();
        $r->setSid($sid);
        $s = $r->step1();
        if ($s) {
            $s1 = $r->step2($s);dump($s1);die;
            if (count($s1)) {
                Db::name("product_items")->where("sid", $sid)->delete();
                $sql = "INSERT INTO " . config('database.prefix') . "product_items(itemid,sid,title,comment_num,c_price,r_price,pic,update_time,sale_num) VALUES";
                $i = [];
                $time = time();
                foreach ($s1 as $k => $v) {
                    $i[] = sprintf("('%s',%d,'%s',%d,%.2f,%.2f,'%s',%d,%d)", $v['itemid'], $sid, $v['title'], $v['comment_num'], $v['c_price'], $v['r_price'], $v['src'], $time, $v['sale_num']);
                }
                $values = implode(',', $i);
                $values = trim($values, ',');
                $sql .= $values;
                Db::execute($sql);
            }
        }
    }

    public function tb()
    {
        header("Content-type: text/html; charset=utf-8");
        set_time_limit(0);
        $sid = 0;
        $nick = '好奇猫女鞋旗舰店';
        include_once ROOT_PATH . '/lib/taobao/TopSdk.php';
        $c = new \TopClient();
        $c->appkey = '12434162';
        $c->secretKey = '87ece66de04f8665f0aa3bc91e07a6ea';
        
        // 获取店铺资料
        $req = new \ShopGetRequest();
        $req->setFields("sid,cid,title,nick,desc,bulletin,pic_path,created,modified,shop_score");
        $req->setNick($nick);
        $resp = $c->execute($req);
        
        if (! $resp->code) {
            $resp = $resp->shop;
            $resp = (array) $resp;
            $resp['shop_score'] = (array) $resp['shop_score'];
            $row = Db::name("admin_shop")->where("sid = ?", [
                $resp['sid']
            ])->find();
            $data = [
                'bulletin' => $resp['bulletin'],
                'cid' => $resp['cid'],
                'created' => $resp['created'],
                'description' => $resp['desc'],
                'modified' => $resp['modified'],
                'nick' => $resp['nick'],
                'pic_path' => $resp['pic_path'],
                'title' => $resp['title'],
                'pic_path' => $resp['pic_path'],
                'delivery_score' => $resp['shop_score']['delivery_score'],
                'item_score' => $resp['shop_score']['item_score'],
                'service_score' => $resp['shop_score']['service_score'],
                'update_time' => time()
            ];
            $sid = $resp['sid'];
            if (! $row) {
                $data['sid'] = $resp['sid'];
                Db::name("admin_shop")->insert($data);
            } else {
                Db::name("admin_shop")->where("sid", $resp['sid'])->update($data);
            }
            
            // 获取店铺产品分类
            
            $req = new \SellercatsListGetRequest();
            $req->setNick($nick);
            $req->setFields("cid,name");
            $resp = $c->execute($req);
            if (! $resp->code) {
                $resp = (array) $resp->seller_cats;
                $resp = $resp['seller_cat'];
                // 1.清空sid分类数据
                Db::name("shop_category")->where("sid", $sid)->delete();
            
                if (count($resp)) {
                    $sql = "INSERT INTO " . config('database.prefix') . "shop_category(sid,cid,name,parent_cid,pic_url,sort_order,type,update_time) VALUES";
                    $i = [];
                    $time = time();
                    foreach ($resp as $v) {
                        $v = (array) $v;
                        $i[] = sprintf("(%d,%d,'%s',%d,'%s',%d,'%s',%d)", $sid, $v['cid'], $v['name'], $v['parent_cid'], $v['pic_url'], $v['sort_order'], $v['type'], $time);
                    }
                    $values = implode(',', $i);
                    $values = trim($values, ',');
                    $sql .= $values;
                    Db::execute($sql);
                }
            }
            
            include_once ROOT_PATH . '/lib/taobao/tbitemcollection.php';
            $r = new \tbitemcollection();
            $r->setSid($sid);
            $s = $r->step1();
            if ($s) {
                $s1 = $r->step2($s);
                if (count($s1)) {
                    Db::name("product_items")->where("sid", $sid)->delete();
                    $sql = "INSERT INTO " . config('database.prefix') . "product_items(itemid,sid,title,comment_num,c_price,r_price,pic,update_time,sale_num) VALUES";
                    $i = [];
                    $time = time();
                    foreach ($s1 as $k => $v) {
                        $i[] = sprintf("('%s',%d,'%s',%d,%.2f,%.2f,'%s',%d,%d)", $v['itemid'], $sid, $v['title'], $v['comment_num'], $v['c_price'], $v['r_price'], $v['src'], $time, $v['sale_num']);
                    }
                    $values = implode(',', $i);
                    $values = trim($values, ',');
                    $sql .= $values;
                    Db::execute($sql);
                }
            }
        }
        
    }
    // public function test()
    // {
    // require_once ROOT_PATH."lib/wxapi/tp.wx.mppay.php";
    
    // $helper = new \tpwxmppay();
    // $helper->setAppid('wxfc90a34d2124b5ea');
    // $helper->setAppsecret('b9d810675009551d60a0745465903f16');
    // $helper->setMchid('1281426601');
    // $helper->setWxkey('c722ba6d57022c26bcef682877b093c9');
    // // $helper->
    // // $res = $helper->queryOrderByOutTradeNo('170205687867');
    // // dump($res);
    // }
    public function tb3()
    {
        include_once ROOT_PATH . '/lib/taobao/TopSdk.php';
        $c = new \TopClient();
        $c->appkey = '12434162';
        $c->secretKey = '87ece66de04f8665f0aa3bc91e07a6ea';
        $req = new \ItemDetailGetRequest;
//         $req->setParams("areaId");
        $req->setItemId("549860752708");
        $req->setFields("item,price,delivery,skuBase,skuCore,trade,feature,props,debug");
        $resp = $c->execute($req);
        dump($resp);
        die;
        $req = new \TbkItemGetRequest();
        $req->setFields("num_iid,title,pict_url,small_images,reserve_price,zk_final_price,user_type,provcity,item_url,seller_id,volume,nick");
        $req->setQ("女装");
        $req->setCat("16,18");
        $req->setItemloc("杭州");
        $req->setSort("tk_rate_des");
        $req->setIsTmall("false");
        $req->setIsOverseas("false");
        $req->setStartPrice("10");
        $req->setEndPrice("10");
        $req->setStartTkRate("123");
        $req->setEndTkRate("123");
        $req->setPlatform("1");
        $req->setPageNo("123");
        $req->setPageSize("20");
        $resp = $c->execute($req);
        dump($resp);
    }

    public function refund()
    {
        // 报名退款
        // $joinid = 110;
        // $s = activity_refund($joinid,1*100);
        // dump($s);
    }
}
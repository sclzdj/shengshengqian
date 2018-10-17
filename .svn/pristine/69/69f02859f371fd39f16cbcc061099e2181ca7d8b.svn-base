<?php

class tbitemcollection
{

    private $_shopHost = '';

    private $_sid = 0;

    private $_page = 1;
    /**
     *
     * @return the $_sid
     */
    public function getSid()
    {
        return $this->_sid;
    }

    /**
     *
     * @param number $_sid            
     */
    public function setSid($_sid)
    {
        $this->_sid = $_sid;
    }

    /**
     *
     * @return the $_shopHost
     */
    public function getShopHost()
    {
        return 'shop'.$this->getSid().'.taobao.com';
    }

    /**
     *
     * @param string $_shopHost            
     */
    public function setShopHost($_shopHost)
    {
        $this->_shopHost = $_shopHost;
    }
    /**
     * 应该支持小数
     * @param unknown $str
     * @return mixed
     */
    function number($str)
    {
        $new = '';
        for ($i=0;$i<=strlen($str);$i++)
        {
            $k = substr($str, $i,1);
            if(is_numeric($k) || $k=='.')
            {
                $new.=$k;
            }
        }
        return $new;
//         return preg_replace('/\D/s', '', $str);
    }
    /**
     * 
     * 获取 产品列表前的mid wid
     * */
    function step1()
    {
        // GET /search.htm?search=y&viewType=list HTTP/1.1
        // User-Agent: Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/21.0.1180.89 Safari/537.1
        // Referer: http://www.taobao.com
        // Host: shop111714585.taobao.com
        $headers = [
            'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
            'User-Agent: Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/21.0.1180.89 Safari/537.1',
            'Referer: http://www.taobao.com'
        ];
        $url = 'https://'.$this->getShopHost().'/search.htm?search=y&viewType=list';
        $result = $this->cget($url, $headers);
        $result = $this->get_split('wid=', '&path=', $result);
        return $result;
    }
    
    function step2($wid)
    {
        $data = [];
        
        $headers = [
            'accept: text/javascript, application/javascript, application/ecmascript, application/x-ecmascript, */*; q=0.01',
            'User-Agent: Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/21.0.1180.89 Safari/537.1',
            'Referer: https://shop'.$this->getSid().'.taobao.com'
        ];
        for($i=1;$i++;$i<=10)
        {
            usleep(1000);
            $url = 'https://shop'.$this->getSid().'.taobao.com/i/asynSearch.htm?_ksTS=149%d_181&callback=jsonp182&mid=w-%s-0&wid=%s&path=/search.htm&search=y&viewType=gird&pageNo=%d';
            $url = sprintf($url,time(),$wid,$wid,$this->_page);
            
//             dump("url=".$url);die;
            $this->_page++;
            $result = $this->cget($url, $headers);
            
            $result = mb_convert_encoding($result, 'UTF-8','GBK');
            $page = $this->get_split('<span class=\"page-info\">', '</span>', $result);
            if(strlen($page) > 50)
            {
                $page = $this->get_split('<b class=\"ui-page-s-len\">', '</b>', $result);
            }
            
//             dump("返回".$result);die;
//             dump($page);
            if(strpos($result,'很抱歉'))
            {
                break ;
            }
            $regex = "/<dl.*?>.*?<\/dl>/ism";
            preg_match_all($regex,$result,$dl);
            $arr = array();
            $i = 0;
            foreach ($dl[0] as $key => $value) {
                $regex = '/<img.*?>/';
                preg_match_all($regex,$value,$img);
                $regex = '/<a.*?>.*?<\/a>/';
                preg_match_all($regex,$value,$a);
                $regex = '/<span class=.*?>.*?<\/span>/';
                preg_match_all($regex,$value,$span);
                $regex = '/data-id=.*?>/';
                preg_match_all($regex,$value,$data_id);
                $regex = '/<span>.*?<\/span>/';
                preg_match_all($regex,$value,$commentspan);
                preg_match_all('/alt=\\\\\".*?\\\\\"/ism',$img[0][0],$titleArr);
                $title = str_replace('alt=\"','',$titleArr[0][0]);
                $title =  str_replace('\"','',$title);
                preg_match_all('/src=\\\\\".*?\\\\\"/ism',$img[0][0],$srcArr);
                $src = str_replace('src=\"','',$srcArr[0][0]);
                $src =  str_replace('\"','',$src);
                preg_match_all('/data-ks-lazyload=\\\\\".*?\\\\\"/ism',$img[0][0],$srcbackArr);
                if(isset($srcbackArr[0][0]))
                {
                    $srcback = str_replace('src=\"','',$srcbackArr[0][0]);
                    $srcback =  str_replace('\"','',$srcback);
                    $srcback =  str_replace('data-ks-lazyload=','',$srcback);
                }
                preg_match_all('/href=\\\\\".*?\\\\\"/ism',$a[0][0],$urlArr);
                $url1 = str_replace('href=\"','',$urlArr[0][0]);
                $url1 =  str_replace('\"','',$url1);
                if(strpos($src, '.gif'))
                {
                    $src = $srcback;
                }
            if(isset($span[0][1])){
                $c_price = str_replace('<span class=\"c-price\">','',$span[0][1]);
                $c_price =  str_replace('</span>','',$c_price);
                $c_price = $this->number($c_price);
//                 $arr[$i]['c_price'] = $c_price;
            }else{
                $c_price = 0;
//                 $arr[$i]['c_price'] = 0;
            }
            if(isset($span[0][3])){
                $r_price = str_replace('<span class=\"s-price\">','',$span[0][3]);
                $r_price =  str_replace('</span>','',$r_price);
                $r_price = $this->number($r_price);
            }else{
                $r_price = 0;
            }
            if(isset($span[0][4])){
                $sale_num = str_replace('<span class=\"sale-num\">','',$span[0][4]);
                $sale_num =  str_replace('</span>','',$sale_num);
                $sale_num = $this->number($sale_num);
//                 $arr[$i]['sale_num'] = $sale_num;
            }else{
                $sale_num = 0;
//                 $arr[$i]['sale_num'] = 0;
            }
                $itemid = str_replace('data-id=\"','',$data_id[0][0]);
                $itemid =  str_replace('\">','',$itemid);
                if(isset($commentspan[0][0]))
                {
                    $comment_num = str_replace('<span>','',$commentspan[0][0]);
                    $comment_num =  str_replace('</span>','',$comment_num);
                    $comment_num = $this->number($comment_num);
                }else{
                    $comment_num = 0;
                }
                
                // $c_price
                $data[$itemid]['title'] = $title;
                $data[$itemid]['src'] = $src;
                $data[$itemid]['url'] = $url1;
                $data[$itemid]['c_price'] = $c_price;
                $data[$itemid]['r_price'] = $r_price;
                $data[$itemid]['itemid'] = $itemid;
                $data[$itemid]['sale_num'] = $sale_num;
                $data[$itemid]['comment_num'] = $comment_num;
                $i++;
//                 dump($data);die;
            }
            if($page)
            {
                $page = explode('/', $page);
                if($page[0] == $page[1])
                {
                    break;
                }
            }
        }
        return $data;
    }
    
    
    function cget($url, $headers)
    {
        $cu = curl_init();
        curl_setopt($cu, CURLOPT_URL, $url);
        curl_setopt($cu, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($cu, CURLOPT_FOLLOWLOCATION,1); //是否抓取跳转后的页面
        curl_setopt($cu, CURLOPT_SSL_VERIFYPEER, false);//这个是重点。
        curl_setopt($cu, CURLOPT_HTTPHEADER, $headers);
        $ret = curl_exec($cu);
        curl_close($cu);
//         $ret = mb_convert_encoding($ret, 'UTF-8');
        return $ret;
    }
    /**
     *
     *2011-12-30-下午05:30:52 by 460932465
     * 返回格式化后的字符串
     * @param 左边字符 $leftchar
     * @param 右边字符 $rightchar
     * @param 完整字符串 $string
     */
     function get_split($leftchar,$rightchar,$string)
    {
        $a =  strpos($string,$leftchar) ;
        $b = strpos($string,$rightchar,$a);
        if($a === false && $b === false)
        {
            return false;
        }else
        {
            $c = substr($string,$a + strlen($leftchar),$b - $a - strlen($leftchar));
            $c = str_replace("\n","",$c);
            $c = str_replace("\r","",$c);
            $c = str_replace("\r\n","",$c);
            return $c;
        }
    }
}
?>
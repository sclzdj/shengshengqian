<?php
function actiontest(){
	$url = "https://shop111714585.taobao.com/i/asynSearch.htm?_ksTS=1493806806016_180&callback=jsonp181&mid=w-10795111360-0&wid=10795111360&path=/search.htm&search=y&spm=a1z10.3-c.w4002-10795111360.24.A1k4Zf&pageNo=3&viewType=grid";
	$html = file_get_contents($url);
	$regex = "/<dl.*?>.*?<\/dl>/ism";
	preg_match_all($regex,$html,$dl);
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
		preg_match_all('/href=\\\\\".*?\\\\\"/ism',$a[0][0],$urlArr);
		$url = str_replace('href=\"','',$urlArr[0][0]);
		$url =  str_replace('\"','',$url);
		$c_price = str_replace('<span class=\"c-price\">','',$span[0][1]);
		$c_price =  str_replace('</span>','',$c_price);
		$r_price = str_replace('<span class=\"s-price\">','',$span[0][3]);
		$r_price =  str_replace('</span>','',$r_price);
		$itemid = str_replace('data-id=\"','',$data_id[0][0]);
		$itemid =  str_replace('\">','',$itemid);
		$comment_num = str_replace('<span>','',$commentspan[0][0]);
		$comment_num =  str_replace('</span>','',$comment_num);
		// $c_price
		$arr[$i]['title'] = $title;
		$arr[$i]['src'] = $src;
		$arr[$i]['url'] = $url;
		$arr[$i]['c_price'] = $c_price;
		$arr[$i]['r_price'] = $r_price;
		$arr[$i]['itemid'] = $itemid;
		$arr[$i]['comment_num'] = $comment_num;
		$i++;
	}
	print_r($arr);
}
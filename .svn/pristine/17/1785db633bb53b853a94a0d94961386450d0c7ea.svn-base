<?php 
header("Content-type: text/html; charset=utf-8");
$result = file_get_contents('1.log');
$rule  = '/<div class=\\"sprice-area\\">(.*)<\/div>/is';
//         preg_match('/<\s*img\s+[^>]*?src\s*=\s*(\'|\")(.*?)\\1[^>]*?\/?\s*>/i',$result,$data);
//         $result = str_replace('</div>', "\r\n</div>", $result);
// dump($result);
preg_match_all($rule,$result,$data);
var_dump($data);die;
?>
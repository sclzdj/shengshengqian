<?php 
header("Content-type: text/html; charset=utf-8");
include "TopSdk.php";

$c = new TopClient;
$c->appkey = '12434162';
$c->secretKey = '87ece66de04f8665f0aa3bc91e07a6ea';

$req = new SellercatsListGetRequest;
// $req->
$req->setNick("卡多丽旗舰店");
$req->setFields("cid,name");
$resp = $c->execute($req);
var_dump($resp);
?>
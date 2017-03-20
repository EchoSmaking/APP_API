<?php
header('content-type:text/html;charset=utf-8');
require_once './response.php';
require_once './db.php';
session_start();
if(!isset($_SESSION['userid'])){
	return Response::show('004','用户尚未登录');
}
$product_id = $_REQUEST['goodsid'];
$userid = $_SESSION['userid'];
try {
    $connect = Db::getInstance()->connect();
} catch (Exception $e) {
    return Response::show('403', '数据库链接失败');
}
$sql = SELECT * FROM cms_cat WHERE product_id = '$product_id' AND user_id = '$userid';
$ret = mysql_query($sql, $connect);
$sing = mysql_fetch_assoc($ret);
if($sing){
	return Response::show('006', '该商品已经存在购物车当中');
}

$sqll = INSERT INTO cms_cat (user_id ,product_id) VALUES('$userid' ,'$product_id');
$rets = mysql_query($sqll, $connect);
if($rets){
	return Response::show('666', '加入购物车成功');
}
<?php
header('content-type:text/html;charset=utf-8');
require_once './db.php';
require_once './response.php';

  if(!isset($_SESSION['userid'])){
	return Response::show('004','用户尚未登录');
}
$ordergoodsid = $_REQUEST['ordergoodsid'];
$userid = $_SESSION['userid'];
$strid = implode(',',$ordergoodsid);

if(!isset($ordergoodsid)){
	return Response::show('001', '没有得到订单中的产品');
}
try {
    $connect = Db::getInstance()->connect();
} catch (Exception $e) {
    return Response::show('403', '数据库链接失败');
}

foreach($ordergoodsid as $product_id){
	$sql = "SELECT issale FROM `cms_product` where id = $product_id;";
	$ret = mysql_query($sql, $connect);
	$sing = mysql_fetch_assoc($ret);
	$row[] = $sing['issale'];
}
$allsale = 0;
for ($i = 0; $i < count($ordergoodsid); $i++) {
    $allsale+=$row[$i];
}
if($allsale > 0){
	return Response::show('005', '系统错误');
}


foreach($ordergoodsid as $product_id){
	$sql = "SELECT price FROM `cms_product` where id = $product_id;";
	$ret = mysql_query($sql, $connect);
	$sing = mysql_fetch_assoc($ret);
	$row[] = $sing['price'];
}
$all = 0;
for ($i = 0; $i < count($ordergoodsid); $i++) {
    $all+=$row[$i];
}
$time = time();
$sql = "insert into cms_order(user_id,product_id,orderprice,ordertime)values('$userid','$strid','$all','$time')";
$ret = mysql_query($sql, $connect);
if($ret){
	foreach($ordergoodsid as $product_id){
		$sqll = "UPDATE cms_cat SET cat_statu = 1 WHERE product_id = $product_id";
		$ret = mysql_query($sql, $connect);
	}
	return Response::show('666', '订单生成成功');
}
//接下来就是需要调用支付宝接口了！
//



<?php
header('content-type:text/html;charset=utf-8');
require_once './response.php';
require_once './db.php';
session_start();
if(!isset($_SESSION['userid'])){
	return Response::show('004','用户尚未登录');
}
//免登录测试
/* if(!isset($_REQUEST['userid'])){
	return Response::show('001','没有传送用户id');
} 
$userid = $_REQUEST['userid'];*/
$userid = $_SESSION['userid'];
try {
    $connect = Db::getInstance()->connect();
} catch (Exception $e) {
    return Response::show('403', '数据库链接失败');
}

switch ($get = $_GET['myaddcat'])
{
case 'list' :

$sql = "SELECT * FROM `cms_cat` left join `cms_product` ON `cms_cat`.product_id = `cms_product`.id WHERE `user_id`= $userid AND issale = '0'";
$ret = mysql_query($sql, $connect);
while($sing = mysql_fetch_assoc($ret)){
	$data[] = array(
	'goodid' =>$sing['id'],
	'title'=>$sing['title'],
	'thumb' =>$sing['thumb'],
	'price' =>$sing['price'],
	);
}
if(!isset($data)){
	return Response::show('002','该用户购物车为空');
}
return Response::show('666','获取购物车列表成功',$data);

  break;
  
case 'dell':
	$product_id = $_REQUEST['goodsid'];
	if (! trim($product_id)) {
    return Response::show('001', '删除的产品id不能为空', null);
}
	$sql = "delete from cms_cat where product_id = '$product_id' AND user_id = '$userid'";
	$ret = mysql_query($sql, $connect); 
if($ret){
	return Response::show('666','删除购物车产品成功');
}

  break;
  
default:
  echo '123';
}







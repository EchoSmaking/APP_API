<?php
header('content-type:text/html;charset=utf-8');
require_once './db.php';
require_once './response.php';
session_start();
  if(!isset($_SESSION['userid'])){
	return Response::show('004','用户尚未登录');
}
$userid = $_SESSION['userid'];
//$userid = 1;

try {
    $connect = Db::getInstance()->connect();
} catch (Exception $e) {
    return Response::show('403', '数据库链接失败');
}

switch ($get = $_GET['upload'])
{
	
case 'list':
  $sql = "SELECT * FROM cms_product where userid ='$userid'";
  $ret = mysql_query($sql, $connect);
  while($sing = mysql_fetch_assoc($ret)){
	$data[] = array(
	'goodid' =>$sing['id'],
	'title'=>$sing['title'],
    'description' =>$sing['description'],
	'thumb' =>$sing['thumb'],
	'price' =>$sing['price'],
	);
	}
	if(!$data){
		return Response::show('400','没有发布的产品');
	}
  return Response::show('666','获取地址成功',$data);
  break;
case 'dell':
	$addressid = $_REQUEST['addressid'];
	if (! trim($addressid)) {
    return Response::show('001', '产品id不能为空', null);
}
	$sql = "delete from cms_product where id='$addressid'";
	$ret = mysql_query($sql, $connect); 
if($ret){
	return Response::show('666','发布删除成功');
}
  
default:
  echo '123';
}


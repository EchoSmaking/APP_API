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

switch ($get = $_GET['address'])
{
case 'add' :
  $userfriend = $_REQUEST['userfriend'];
  $friendphone = $_REQUEST['friendphone'];
  $newaddress = $_REQUEST['newaddress'];
  $impaddress = $_REQUEST['impaddress'];
  
  if (! trim($userfriend)) {
    return Response::show('001', '收货人不能为空', null);
}
  if (! trim($friendphone)) {
    return Response::show('001', '手机号码不能为空', null);
}
  if (! trim($newaddress)) {
    return Response::show('001', '填写的地址不能为空', null);
}
  
  if($impaddress === '1'){
	  $sql = "UPDATE cms_myaddress SET impaddress = '0' WHERE impaddress in ('1')";
	  $ret = mysql_query($sql, $connect);  
	   $sql2 = "INSERT INTO cms_myaddress (admin_id ,address,userfriend,friendphone,impaddress)
  VALUES('$userid','$newaddress','$userfriend','$friendphone','1')";
  $ret2 = mysql_query($sql2, $connect);
  if($ret2){return Response::show('666','添加默认地址成功');}
  }
  if($impaddress === '0'){
  $sql = "INSERT INTO cms_myaddress (admin_id ,address,userfriend,friendphone,impaddress)
  VALUES('$userid','$newaddress','$userfriend','$friendphone','0')";
  $ret = mysql_query($sql, $connect);
  if($ret){return Response::show('666','添加地址成功');}}
  break;
  
case 'list':
  $sql = "SELECT * FROM cms_myaddress where admin_id ='$userid'";
  $ret = mysql_query($sql, $connect);
  while($sing = mysql_fetch_assoc($ret)){
	$data[] = array(
	'addressid' =>$sing['id'],
	'address'=>$sing['address'],
	'userfriend' =>$sing['userfriend'],
	'friendphone' =>$sing['friendphone'],
	'impaddress' =>$sing['impaddress'],
	);
	}
	if(!$data){
		return Response::show('404','没有收货地址');	
	}
  return Response::show('666','获取地址成功',$data);
  break;
case 'defa':
    $addressid = $_REQUEST['addressid'];
	$sql = "UPDATE cms_myaddress SET impaddress = '0' WHERE impaddress in ('1')";
	$ret = mysql_query($sql, $connect); 
	$sqll = "UPDATE cms_myaddress SET impaddress = '1' WHERE id = '$addressid'";
	$rets = mysql_query($sqll, $connect); 
	//$singss = mysql_affected_rows($rets);
/* 	if(!$singss){
		return Response::show('777','不要重复设置默认地址');
	} */
  return Response::show('666','默认地址设置成功');
  break;
case 'dell':
	$addressid = $_REQUEST['addressid'];
	if (! trim($addressid)) {
    return Response::show('001', '地址id不能为空', null);
}
	$sql = "delete from cms_myaddress where id='$addressid'";
	$ret = mysql_query($sql, $connect); 
if($ret){
	return Response::show('666','删除地址成功');
}
  
default:
  echo '123';
}


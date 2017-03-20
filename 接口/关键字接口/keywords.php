<?php
require_once './response.php';
require_once './db.php';

$keywords = $_REQUEST['keywords'];

$page = isset($_GET['page']) ? $_GET['page'] : 1;
$pageSize = isset($_GET['pagesize']) ? $_GET['pagesize'] : 8;
$offset = ($page - 1) * $pageSize;

try {
    $connect = Db::getInstance()->connect();
} catch (Exception $e) {
    return Response::show('403', '数据库链接失败');
}
if (! trim($keywords)) {
    return Response::show('001', '请填写关键字', null);
}
switch ($get = $_GET['rand'])
{
case 'product' : 

$sql = "SELECT * FROM cms_product where keywords like '%$keywords%' limit ". $offset ." , ".$pageSize;
$ret = mysql_query($sql, $connect);
while($sing = mysql_fetch_assoc($ret)){

$userid = $sing['userid'];

$sqls = "SELECT mobilenum FROM cms_admin where admin_id ='$userid'";
$rets = mysql_query($sqls, $connect);
$sings = mysql_fetch_assoc($rets);
$num = $sings['mobilenum'];
$data[] = array(
 'goodid'=>$sing['id'],
 'title' =>$sing['title'],
 'thumb' =>$sing['thumb'],
 'price' =>$sing['price'],
 'descriptio' =>$sing['description'],
);
} 
if(!isset($data)){
	return Response::show('002','无该关键字产品');
}
return Response::show('666','关键字列表获取成功',$data);

break;

case 'price_desc': 

$sql = "SELECT * FROM cms_product where keywords like '%$keywords%' ORDER BY price DESC limit ". $offset ." , ".$pageSize;
$ret = mysql_query($sql, $connect);
while($sing = mysql_fetch_assoc($ret)){

$userid = $sing['userid'];

$sqls = "SELECT mobilenum FROM cms_admin where admin_id ='$userid'";
$rets = mysql_query($sqls, $connect);
$sings = mysql_fetch_assoc($rets);
$num = $sings['mobilenum'];
$data[] = array(
 'goodid'=>$sing['id'],
 'title' =>$sing['title'],
 'thumb' =>$sing['thumb'],
 'price' =>$sing['price'],
 'descriptio' =>$sing['description'],
);
} 
if(!isset($data)){
	return Response::show('002','无该关键字产品');
}
return Response::show('666','关键字列表获取成功',$data);

break;

case 'price_asc': 

$sql = "SELECT * FROM cms_product where keywords like '%$keywords%' ORDER BY price ASC limit ". $offset ." , ".$pageSize;
$ret = mysql_query($sql, $connect);
while($sing = mysql_fetch_assoc($ret)){

$userid = $sing['userid'];

$sqls = "SELECT mobilenum FROM cms_admin where admin_id ='$userid'";
$rets = mysql_query($sqls, $connect);
$sings = mysql_fetch_assoc($rets);
$num = $sings['mobilenum'];
$data[] = array(
 'goodid'=>$sing['id'],
 'title' =>$sing['title'],
 'thumb' =>$sing['thumb'],
 'price' =>$sing['price'],
 'descriptio' =>$sing['description'],
);
} 
if(!isset($data)){
	return Response::show('002','无该关键字产品');
}
return Response::show('666','关键字列表获取成功',$data);

break;


case 'time_desc': 

$sql = "SELECT * FROM cms_product where keywords like '%$keywords%' ORDER BY create_time DESC limit ". $offset ." , ".$pageSize;
$ret = mysql_query($sql, $connect);
while($sing = mysql_fetch_assoc($ret)){

$userid = $sing['userid'];

$sqls = "SELECT mobilenum FROM cms_admin where admin_id ='$userid'";
$rets = mysql_query($sqls, $connect);
$sings = mysql_fetch_assoc($rets);
$num = $sings['mobilenum'];
$data[] = array(
 'goodid'=>$sing['id'],
 'title' =>$sing['title'],
 'thumb' =>$sing['thumb'],
 'price' =>$sing['price'],
 'descriptio' =>$sing['description'],
);
} 
if(!isset($data)){
	return Response::show('002','无该关键字产品');
}
return Response::show('666','关键字列表获取成功',$data);

break;


case 'time_asc': 

$sql = "SELECT * FROM cms_product where keywords like '%$keywords%' ORDER BY create_time ASC limit ". $offset ." , ".$pageSize;
$ret = mysql_query($sql, $connect);
while($sing = mysql_fetch_assoc($ret)){

$userid = $sing['userid'];

$sqls = "SELECT mobilenum FROM cms_admin where admin_id ='$userid'";
$rets = mysql_query($sqls, $connect);
$sings = mysql_fetch_assoc($rets);
$num = $sings['mobilenum'];
$data[] = array(
 'goodid'=>$sing['id'],
 'title' =>$sing['title'],
 'thumb' =>$sing['thumb'],
 'price' =>$sing['price'],
 'descriptio' =>$sing['description'],
);
} 
if(!isset($data)){
	return Response::show('002','无该关键字产品');
}
return Response::show('666','关键字列表获取成功',$data);

break;



default:
  echo '123';
}




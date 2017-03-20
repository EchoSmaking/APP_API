<?php
require_once './response.php';
require_once './db.php';
echo '123';exit;
try {
    $connect = Db::getInstance()->connect();
} catch (Exception $e) {
    return Response::show('403', '数据库链接失败');
}

$page = isset($_GET['page']) ? $_GET['page'] : 1;
$pageSize = isset($_GET['pagesize']) ? $_GET['pagesize'] : 8;
$offset = ($page - 1) * $pageSize;

switch ($get = $_GET['creative'])
{
case 'list' :
$sql = "SELECT * FROM cms_product where status ='6' limit ". $offset ." , ".$pageSize;
$ret = mysql_query($sql, $connect);
while($sing = mysql_fetch_assoc($ret)){
	$data[] = array(
	'creativeid' =>$sing['id'],
	'thumb' =>$sing['thumb'],
	'title' =>$sing['title'],
	'descriptio'=>$sing['description'],
	);
}
return Response::show('666','活动列表获取成功',$data);
  break;
  
case 'info' :
  $creativeid = $_REQUEST['creativeid'];
  $sql = "SELECT * FROM cms_product where id = '$creativeid'"
  $ret = mysql_query($sql, $connect);
  $sing = mysql_fetch_assoc($ret)
  $data[] = array(
	'title' =>$sing['title'],
	'thumb' =>$sing['thumb'],
	'descriptio'=>$sing['description'],
	'create_time'=>date("Y-m-d",$sing['create_time']),
  );
  return Response::show('666','活动详情获取成功',$data);
  
default:
  echo '123';
}

<?php
header('content-type:text/html;charset=utf-8');
require_once './response.php';
require_once './db.php';
require_once './function.php';


if(!isset($_REQUEST['cate'])){
	return Response::show('001','请先传送分类名称');
}
$cate = $_REQUEST['cate'];
try {
    $connect = Db::getInstance()->connect();
} catch (Exception $e) {
    return Response::show('403', '数据库链接失败');
}
$sql = "SELECT * FROM cms_product where cate ='$cate' AND issale = '0'";
$ret = mysql_query($sql, $connect);
while($sing = mysql_fetch_assoc($ret)){
	$data[] = array(
	'goodid' =>$sing['id'],
	'title'=>$sing['title'],
	'thumb' =>$sing['thumb'],
	'price' =>$sing['price'],
	'text'=>$sing['description'],
	);
}
if(!isset($data)){
	return Response::show('002','无该分类产品');
}
return Response::show('666','分类列表获取成功',$data);
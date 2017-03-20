<?php
header('content-type:text/html;charset=utf-8');
require_once './response.php';
require_once './db.php';
echo '123';
try {
    $connect = Db::getInstance()->connect();
} catch (Exception $e) {
    return Response::show('403', '数据库链接失败');
}
$sql = "SELECT * FROM cms_product where status ='5'";
$ret = mysql_query($sql, $connect);
while($sing = mysql_fetch_assoc($ret)){
	$data[] = array(
	'goodid' =>$sing['id'],
	'thumb' =>$sing['thumb'],
	'title' =>$sing['title'],
	'price' =>$sing['price'],
	'descriptio'=>$sing['description'],
	);
}
return Response::show('666','秒杀列表获取成功',$data);
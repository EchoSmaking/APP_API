<?php
header('content-type:text/html;charset=utf-8');
require_once './response.php';
require_once './db.php';
//require_once './function.php';


if(!isset($_REQUEST['id'])){
	return Response::show('001','没有找到该产品id');
}
$id = $_REQUEST['id'];
try {
    $connect = Db::getInstance()->connect();
} catch (Exception $e) {
    return Response::show('403', '数据库链接失败');
}
$sql = "SELECT * FROM cms_product where id ='$id' AND issale = '0'";
$ret = mysql_query($sql, $connect);
$sing = mysql_fetch_assoc($ret);
if(!$sing){
	return Response::show('008','该产品卖完了');
}
$userid = $sing['userid'];
$sqls = "SELECT mobilenum FROM cms_admin where admin_id ='$userid'";
$rets = mysql_query($sqls, $connect);
$sings = mysql_fetch_assoc($rets);
$num = $sings['mobilenum'];
$data = array(
 'title' =>$sing['title'],
 'keywords'=>explode(',',$sing['keywords']),
 'thumb' =>$sing['thumb'],
 'price' =>$sing['price'],
 'descriptio' =>$sing['description'],
 'create_time'=>date("Y-m-d",$sing['create_time']),
 'mobilenum'  =>$num,
);

return Response::show('666','产品详情获取成功',$data);
<?php
header('content-type:text/html;charset=utf-8');
require_once './response.php';
require_once './db.php';
require_once './function.php';
require_once 'upload.func.php';
session_start();//开启session查询保存的东西

//判断用户是否登录

if(!isset($_SESSION['userid'])){
	return show('004','用户尚未登录');
}


$fileInfo = $_FILES['image'];

$product = $_REQUEST['product'];
$keywords = $_REQUEST['keywords'];
$description = $_REQUEST['description'];
$price = $_REQUEST['price'];
$cate = $_REQUEST['cate'];


if (! trim($product)) {
    return Response::show('001', '请填写产品名称', null);
}
if (! trim($description)) {
    return Response::show('001', '请填写产品描述', null);
}
if (! trim($keywords)){
	return Response::show('001', '请填写关键字', null);
	
}
if (! trim($price)) {
    return Response::show('001', '请填写产品价格', null);
}
if (! trim($cate)) {
    return Response::show('001', '请填写产品分类', null);
}


$uploadPath = 'upload';
$flag = true;
$allowExt = array(
    'jpeg',
    'jpg',
    'gif',
    'png'
);
$maxSize = 2097152;
if ($fileInfo['error'] > 0) {
    switch ($fileInfo['error']) {
        case 1:
            $mes = '上传文件超过了PHP配置文件中upload_max_filesize选项的值';
            break;
        case 2:
            $mes = '超过了表单MAX_FILE_SIZE限制的大小';
            break;
        case 3:
            $mes = '文件部分被上传';
            break;
        case 4:
            $mes = '没有选择上传文件';
            break;
        case 6:
            $mes = '没有找到临时目录';
            break;
        case 7:
        case 8:
            $mes = '系统错误';
            break;
    }
	return Response::show('444',$mes);
}
$ext = pathinfo($fileInfo['name'], PATHINFO_EXTENSION);
if (! is_array($allowExt)) {
    return Response::show('444','系统错误');
}
// 检测上传文件的类型
if (! in_array($ext, $allowExt)) {
    return Response::show('444','非法文件类型');
}
// $maxSize = 2097152; // 2M
// 检测上传文件大小是否符合规范
if ($fileInfo['size'] > $maxSize) {
    return Response::show('444','上传文件过大');
}
// 检测图片是否为真实的图片类型
// $flag=true;
if ($flag) {
    if (! getimagesize($fileInfo['tmp_name'])) {
    return Response::show('444','不是真实的图片类型');
    }
}
// 检测文件是否是通过HTTP POST方式上传上来
if (! is_uploaded_file($fileInfo['tmp_name'])) {
	return Response::show('444','文件不是通过HTTP POST方式上传上来的');
}
// $uploadPath = 'uploads';
if (! file_exists($uploadPath)) {
    mkdir($uploadPath, 0777, true);
    chmod($uploadPath, 0777);
}
$uniName = md5(uniqid(microtime(true), true)) . '.' . $ext;
$destination = $uploadPath . '/' . $uniName;
if (! @move_uploaded_file($fileInfo['tmp_name'], $destination)) {
	return Response::show('444','文件移动失败');
}


try {
    $connect = Db::getInstance()->connect();
} catch (Exception $e) {
    return Response::show('403', '数据库链接失败');
}
$userid = $_SESSION['userid'];

$userup = "SELECT * FROM cms_admin where admin_id = '$userid'";
$userupret = mysql_query($userup, $connect);
$useruprets = mysql_fetch_assoc($userupret);
if($useruprets['status'] === '1'){
	 return Response::show('001', '普通用户没有发布权限');
}
$time = time();
$sql = "INSERT INTO cms_product (title,description,thumb,keywords,price,cate,userid,create_time) 
	VALUES ('$product','$description','$destination','$keywords','$price','$cate','$userid','$time')";
$ret = mysql_query($sql, $connect);
if($ret){
return Response::show('666','产品发布成功');
}




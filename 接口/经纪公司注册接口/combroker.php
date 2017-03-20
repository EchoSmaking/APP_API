<?php
header('content-type:text/html;charset=utf-8');
require_once './response.php';
require_once './db.php';
require_once './function.php';
include_once 'upload.func.php';
session_start();
$fileInfo = $_FILES['image'];
// $newName=uploadFile($fileInfo);
// echo $newName;
// $newName=uploadFile($fileInfo,'imooc');
// echo $newName;
// $allowExt='txt';
// $allowExt=array('jpeg','jpg','png','gif','html','txt');

$mobilephone = $_REQUEST['mobilephone'];
$code = $_REQUEST['code'];
$realname = $_REQUEST['realname'];
$password = $_REQUEST['password'];
$passwordid = $_REQUEST['passwordid'];
$address = $_REQUEST['address'];

$bankid = $_REQUEST['bankid'];
$bankaddress = $_REQUEST['bankaddress'];

$friendname_two = $_REQUEST['friendname_two'];
$friendname_twonum = $_REQUEST['friendname_twonum'];
$comname = $_REQUEST['comname'];
$friendname_faren = $_REQUEST['friendname_faren'];
$comnum = $_REQUEST['comnum'];
$comadd = $_REQUEST['comadd'];

$friendname = $_REQUEST['friendname'];
$friendnum = $_REQUEST['friendnum'];
$passwords = md5($password . 'sing_cms');



if (! trim($mobilephone)) {
    return Response::show('001', '手机号码不能为空', null);
}
if (! trim($realname)) {
    return Response::show('001', '真实姓名不能为空', null);
}
if ($code != $_SESSION['public']) {
    return Response::show('001', '验证码错误', null);
}
if ($mobilephone != $_SESSION['phone']) {
    return Response::show('001', '手机号码错误', null);
}
if (! trim($password)) {
    return Response::show('001', '密码不能为空', null);
}

if (! trim($bankid)) {
    return Response::show('001', '收款人银行卡号不能为空', null);
} 
if (! trim($bankaddress)) {
    return Response::show('001', '开户行地址不能为空', null);
}


if (! trim($comname)) {
    return Response::show('001', '公司名称不能为空', null);
}
if (! trim($friendname_faren)) {
    return Response::show('001', '法人身份证不能为空', null);
}
if (! trim($comnum)) {
    return Response::show('001', '公司座机不能为空', null);
}
if (! trim($comadd)) {
    return Response::show('001', '公司地址不能为空', null);
}



if (! trim($passwordid)) {
    return Response::show('001', '身份证不能为空', null);
}
if (! trim($address)) {
    return Response::show('001', '地址不能为空', null);
}
if (! trim($friendname)) {
    return Response::show('001', '紧急联系人不能为空', null);
}
if (! trim($friendnum)) {
    return Response::show('001', '紧急联系人号码不能为空', null);
}

try {
    $connect = Db::getInstance()->connect();
} catch (Exception $e) {
    return Response::show('403', '数据库链接失败');
}
$sql = "select * from cms_admin where mobilenum = '$mobilephone' limit 1";
$ret = mysql_query($sql, $connect);
$sing = mysql_fetch_assoc($ret);
if ($sing) {
    // echo $code;
    return Response::show('100', '该手机号码已存在');
}

$uploadPath = 'uploads';
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
// return $destination;

$data = array(
    'mobilenum' => $mobilephone,
    'password' => $password
);
$sqll = "INSERT INTO cms_admin (password,mobilenum,realname,passwordid,address,friendname,friendnum,status,bankaddress,bankid,comname,friendname_faren,comnum,comadd) 
	VALUES ('$passwords',$mobilephone,$realname,$passwordid,$address,$friendname,$friendnum,'3','$comname','$friendname_faren','$comnum','$comadd')";
$retss = mysql_query($sqll, $connect);
if ($retss) {
    $sql2 = "select * from cms_admin where mobilenum = '$mobilephone'";
    $ret = mysql_query($sql2, $connect);
    $sing = mysql_fetch_assoc($ret);

    // echo $code;
    $user1id = $sing['admin_id'];
    $sql2 = "INSERT INTO cms_photo (admin_id,admin_photo) VALUES ($user1id,'$destination')";
    $rests = mysql_query($sql2, $connect);
    return Response::show('666', '注册成功');
}

	
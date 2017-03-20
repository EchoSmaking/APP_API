<?php
//通过手机验证码修改密码的接口

require_once './response.php';//引入接口类文件包

require_once './db.php';//引入链接数据库文件

require_once './function.php';//引入接口调用的方法文件

session_start();

$mobilephone = $_REQUEST['mobilephone'];

$newpassword = $_REQUEST['newpassword'];

$code = $_REQUEST['code'];

//判断传进来的值

    if (! trim($mobilephone)) {
        return Response::show('001', '手机号码不能为空', null);
    }
    
    if (! trim($newpassword)) {
        return Response::show('002', '新密码不能为空', null);
    }
    
	if ($code!=$_SESSION['public']) {
        return Response::show('003', '验证码不能为空', null);
    }
//捕获数据库链接问题
    try {
        $connect = Db::getInstance()->connect();
    } catch (Exception $e) {
        return Response::show('403', '数据库链接失败');
    }
//查找数据库看手机号码是否存在。
    $sql = "select * from cms_admin where mobilenum = '$mobilephone' limit 1";
	$ret = mysql_query($sql, $connect);
	if(!$ret){
		//echo $code;
		return Response::show('100', '该手机号码尚未注册');	
	}
	$sqll = "update cms_admin set password = '$newpassword' where mobilenum = '$mobilephone'";
	$rets = mysql_query($sqll, $connect);
	
	if($rets){
		return Response::show('666','密码更改成功');
	}
	





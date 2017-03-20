<?php
require_once './response.php';
require_once './db.php';
require_once './function.php';

//开启session文件，这样可以找到短信接口存的验证码
 session_start();
	$mobilephone = $_REQUEST['mobilephone'];
	$password = $_REQUEST['password'];
	$code = $_REQUEST['code'];
	$passwords = md5($password.'sing_cms');
    if (! trim($mobilephone)) {
        return Response::show('001', '手机号码不能为空', null);
    }
    if (! trim($password)) {
        return Response::show('002', '密码不能为空', null);
    }
	if ($code!=$_SESSION['public']){
		return Response::show('003', '验证码错误', null);
	}

    try {
        $connect = Db::getInstance()->connect();
    } catch (Exception $e) {
        return Response::show('403', '数据库链接失败');
    }
	//$password = $passwords;
    $sql = "select * from cms_admin where mobilenum = '$mobilephone'";
	$ret = mysql_query($sql, $connect);
	$sing = mysql_fetch_assoc($ret);
	if($sing){
		//echo $code;
		return Response::show('100', '该手机号码已存在');	
	}
	$data = array(
            'mobilenum' =>$mobilephone,
            'password' =>$password,
        );
	
	$sqll = "INSERT INTO cms_admin (password,mobilenum) VALUES ('$passwords','$mobilephone')";
	$retss = mysql_query($sqll, $connect);
	if($retss){
		$sql2 = "select * from cms_admin where mobilenum = '$mobilephone'";
		$ret  = mysql_query($sql2, $connect);
		$sing = mysql_fetch_assoc($ret);
		$data = array(
		'userid'=>$sing['admin_id'],
		'mobilephone'=>$sing['mobilenum'],
		);
		//echo $code;
		return Response::show('666', '注册成功',$data);
	}


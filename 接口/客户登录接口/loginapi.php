<?php
require_once './response.php';
require_once './db.php';
require_once './function.php';
	session_start();
$mobilephone = $_REQUEST['mobilephone'];
$password	 = $_REQUEST['password'];

    if (! trim($mobilephone)) {
        return Response::show('001', '手机号码不能为空', null);
    }
    
    if (! trim($password)) {
        return Response::show('002', '密码不能为空', null);
    }
    
    try {
        $connect = Db::getInstance()->connect();
    } catch (Exception $e) {
        return Response::show('403', '数据库链接失败');
    }
    
    $sql = "select * from cms_admin where mobilenum = '$mobilephone' limit 1";
    
    $ret = mysql_query($sql, $connect);
    $sing = mysql_fetch_assoc($ret);

    if (! $sing) {
        return Response::show('101', '该手机号码尚未注册');
    }
    if ($sing['password'] != getMd5Password($password)) {
        return Response::show('102', '密码不正确');
    }
    $data = array(
        'mobilephone' => $sing['mobilenum'],
        'userid' => $sing['admin_id']
    );
	$_SESSION['userid'] = $data['userid'];
	return Response::show('666', '登录成功', $data);









<?php
//ͨ���ֻ���֤���޸�����Ľӿ�

require_once './response.php';//����ӿ����ļ���

require_once './db.php';//�����������ݿ��ļ�

require_once './function.php';//����ӿڵ��õķ����ļ�

session_start();

$mobilephone = $_REQUEST['mobilephone'];

$newpassword = $_REQUEST['newpassword'];

$code = $_REQUEST['code'];

//�жϴ�������ֵ

    if (! trim($mobilephone)) {
        return Response::show('001', '�ֻ����벻��Ϊ��', null);
    }
    
    if (! trim($newpassword)) {
        return Response::show('002', '�����벻��Ϊ��', null);
    }
    
	if ($code!=$_SESSION['public']) {
        return Response::show('003', '��֤�벻��Ϊ��', null);
    }
//�������ݿ���������
    try {
        $connect = Db::getInstance()->connect();
    } catch (Exception $e) {
        return Response::show('403', '���ݿ�����ʧ��');
    }
//�������ݿ⿴�ֻ������Ƿ���ڡ�
    $sql = "select * from cms_admin where mobilenum = '$mobilephone' limit 1";
	$ret = mysql_query($sql, $connect);
	if(!$ret){
		//echo $code;
		return Response::show('100', '���ֻ�������δע��');	
	}
	$sqll = "update cms_admin set password = '$newpassword' where mobilenum = '$mobilephone'";
	$rets = mysql_query($sqll, $connect);
	
	if($rets){
		return Response::show('666','������ĳɹ�');
	}
	





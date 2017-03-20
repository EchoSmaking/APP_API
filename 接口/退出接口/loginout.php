<?php
require_once './response.php';
require_once './db.php';
require_once './function.php';
	session_start();

    if (isset($_SESSION['userid'])) {
		session_destroy();
        return Response::show('666', '退出成功', null);
    }
    
    if (!isset($_SESSION['userid'])) {
        return Response::show('100', '退出过了', null);
    }
<?php
require_once './response.php';
	session_start();
	function Newsend(){
		$phone = $_REQUEST['phone'];
		$randStr = str_shuffle('123456789');  
		$code = substr($randStr,0,6);
		$sign="【盛世传奇】";
		$msg="你的验证码为：".$code.$sign;
		
        $url="http://service.winic.org:8009/sys_port/gateway/index.asp?";
    	$data = "id=%s&pwd=%s&to=%s&content=%s&time=";
    	$id = 'a424631312';
    	$pwd = '1354724681';
    	$to = $phone; 
    	$content = iconv("UTF-8","GB2312",$msg);
    	$rdata = sprintf($data, $id, $pwd, $to, $content);
		
    	$ch = curl_init();
    	curl_setopt($ch, CURLOPT_POST,1);
    	curl_setopt($ch, CURLOPT_POSTFIELDS,$rdata);
    	curl_setopt($ch, CURLOPT_URL,$url);
    	curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    	$result = curl_exec($ch);
    	curl_close($ch);
    	$result = substr($result,0,3);	
		$_SESSION['public'] = $code;
		$_SESSION['phone'] = $phone;
        return $result;
}		
echo Newsend();
?>


<html>
<head>
<meta http-equiv="Content-type"content="text/html;charset=utf-8">
</head>
<style>
body{
	font-size:12px;FONT-FAMILY:verdana;width:100%;
}
div.page{
 text-align:center;
}
div.content{
	height:300px;
}
div.page a{
	border:#aaaadd 1px solid;text-decoration:none;padding:2px 5px 2px 5px; margin:2px;
}
div.page span.current{
	border:#000099 1px solid;background-color:#000099;padding:5px 5px 5px 5px;margin:2px;color:#fff;
	font-weight:bold;
}
div.page span.disable{
	border:#eee 1px solid;padding:2px 5px 2px 5px;margin:2px;color:#ddd;
}
div.page form{
	display:inline;
}
</style>
<body>
<?php
// 1 传入页码
$page = $_GET['p'];
// 2 根据页码取出数据 :php->mysql处理
$host = "localhost";
$username = "root";
$password = "";
$db = "test";//数据库
$pageSize = 4;  //一页有多少条数据
$showPage = 5;	//显示出多少个页码
//连接数据库
$conn = @mysql_connect($host,$username,$password);
if(!$conn){
	echo "数据库连接失败";
	exit;
}
//选择要操作的数据库
mysql_select_db($db); 
//设置数据库编码格式
mysql_query("SET NAMES UTF8");
//编写sql 获取分页数据 SELECT * FROM 表名 LIMIT 起始位置 显示条数
$sql = "SELECT * FROM page LIMIT ".($page-1)*$pageSize .",{$pageSize}";
//把sql语句传送到数据中
$result = mysql_query($sql);
//处理我们的数据
echo "<div class='content'>";
echo "<table border = 1 cellspacing = 0 width = 40% align=center>";
echo "<tr><td>ID</td><td>NAME</td></tr>";
while($row = mysql_fetch_assoc($result)){
	echo "<tr>";
	echo "<td>{$row['id']}</td>";
	echo "<td>{$row['name']}</td>";
	echo "</tr>";
}
echo "</table>";
echo "<div>";
//释放结果，关闭链接;
mysql_free_result($result);
//获取数据总数
$total_sql = "SELECT COUNT(*) FROM page";
$total_result = mysql_fetch_array(mysql_query($total_sql));
$total = $total_result[0];
//计算页数
$total_pages = ceil($total/$pageSize);
mysql_close($conn);
// 3 显示数据 + 分页条
$page_banner = "<div class='page'>";
// 4 计算偏移量
$pageoffset = ($showPage-1)/2;
if($page >1){
	$page_banner .= "<a href='".$_SERVER['PHP_SELF']."?p=1'>首页</a>";
	$page_banner .= "<a href='".$_SERVER['PHP_SELF']."?p=".($page-1)."'>上一页</a>";
}else{
	$page_banner .= "<span class = 'disable'>首页</a></span>";
	$page_banner .= "<span class = 'disable'>上一页</a></span>";
}
// 5 初始化数据
$start = 1;
$end = $total_pages;
if($total_pages > $showPage){
	if($page>$pageoffset+1){
		$page_banner .="...";
	}
	if($page > $pageoffset){
		$start = $page - $pageoffset;
		$end = $total_pages > $page+$pageoffset ? $page + $pageoffset : $total_pages;	
	}else{
		$start = 1;
		$end = $total_pages >$showPage ? $showPage:$total_pages;
	}
	if($page + $pageoffset > $total_pages){
		$start = $start - ($page + $pageoffset - $end);
	}
}
for($i = $start;$i <= $end; $i++){
 if($page == $i){
	 $page_banner.="<span class ='current'>{$i}</span>"; 
 }else{
	$page_banner.= "<a href='".$_SERVER['PHP_SELF']."?p=".$i."'>{$i}</a>";
	}
}
//尾部省略
if($total_pages >$showPage && $total_pages > $page + $pageoffset){
	$page_banner .= "...";
}
if($page < $total_pages){
	$page_banner.= "<a href='".$_SERVER['PHP_SELF']."?p=".($page+1)."'>下一页</a>";
	$page_banner.= "<a href='".$_SERVER['PHP_SELF']."?p=".($total_pages)."'>尾页</a>";
}else{
	$page_banner .= "<span class = 'disable'>首页</a></span>";
	$page_banner .= "<span class = 'disable'>上一页</a></span>";
}
$page_banner.= "共{$total_pages}页,";
$page_banner.= "<form action = 'mypage.php' method='get'>";
$page_banner.= "到 第 <input type ='text' size ='2' name = 'p'>页";
$page_banner.= "<input type = 'submit' value = '确定'>";
$page_banner.= "</form></div>";
echo $page_banner;
?>
</body>
</html>
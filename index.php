<?php
/**
* TestGuest Version1.0
* ================================================
* Copy 2010-2012 yc60
* Web: http://www.yc60.com
* ================================================
* Author: Lee
* Date: 2010-8-10
*/
//定义个常量，用来授权调用includes里面的文件
define('IN_TG',true);
//定义个常量，用来指定本页的内容
define('SCRIPT','index');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php'; //转换成硬路径，速度更快
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>多用户留言系统--首页</title>
<?php 
	require ROOT_PATH.'includes/title.inc.php';
?>
</head>
<body>

<?php 
	require ROOT_PATH.'includes/header.inc.php';
?>

<div id="list">
	<h2>帖子列表</h2>
</div>

<div id="user">
	<h2>新进会员</h2>
</div>

<div id="pics">
	<h2>最新图片</h2>
</div>

<?php 
	require ROOT_PATH.'includes/footer.inc.php';
?>

</body>
</html>

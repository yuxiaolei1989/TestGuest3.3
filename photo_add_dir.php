<?php
/**
* TestGuest Version1.0
* Author: yulei@addcn.com
* Date: 2015-8-23
*/
session_start();
//定义个常量，用来授权调用includes里面的文件
define('IN_TG',true);
//定义个常量，用来指定本页的内容
define('SCRIPT','photo_add_dir');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';

_manage_login();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php 
	require ROOT_PATH.'includes/title.inc.php';
?>
<script type="text/javascript" src="js/photo_add_dir.js"></script>
</head>
<body>
<?php 
	require ROOT_PATH.'includes/header.inc.php';
?>

<div id="photo_add_dir">
	<h2>添加相册目录</h2>
	<form action="?action=adddir" method="post">
	<dl>
	   <dd>相册名称：<input type="text" name="name" class="text" /></dd>
	   <dd>相册类型：<input type="radio" name="type" value="0" /> 公开<input type="radio" name="type" value="1" /> 私密 </dd>
	   <dd id="pass">相册密码：<input type="password" name="password" class="text" /></dd>
	   <dd>相册描述：<textarea name="content"></textarea></dd>
	   <dd><input type="submit" value="添加目录" class="submit" /></dd>
	</dl>
	</form>
</div>
<?php 
	require ROOT_PATH.'includes/footer.inc.php';
?>
</body>
</html>

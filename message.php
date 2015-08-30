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
define('SCRIPT','message');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';

if(!isset($_COOKIE['username'])){
    _alert_close("请先登录！");
}

if(isset($_GET['id'])){
    $_rows = _fetch_array("SELECT tg_username FROM tg_user WHERE tg_id='{$_GET['id']}' LIMIT 1");
    if($_rows){
        $_rows = _html($_rows);
    }else{
        _alert_close("数据获取失败！");
    }
}else{
    _alert_close("非法操作！");
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>多用户留言系统--发消息</title>
<?php 
	require ROOT_PATH.'includes/title.inc.php';
?>
<script type="text/javascript" src="js/code.js"></script>
<script type="text/javascript" src="js/message.js"></script>
</head>
<body>

<div id="message">
	<h3>写短信</h3>
	<form>
	   <dl>
	       <dd><input type="text" class="text" value="to:<?php echo $_rows['tg_username']?>" /></dd>
	       <dd><textarea rows="" cols="" name="content"></textarea></dd>
	       <dd>验 证 码：<input type="text" name="code" class="text code"  /> <img src="code.php" alt="验证码" id="code" /> <input type="submit" class="button" id="submit" value="发送信息" /></dd>
	   </dl>
	</form>
	
</div>
</body>
</html>

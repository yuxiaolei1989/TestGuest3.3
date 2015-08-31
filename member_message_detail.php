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
define('SCRIPT','member_message_detail');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';

if(!isset($_COOKIE['username'])){
    _alert_back("请登录账号！", "login.php");
}
if(isset($_GET['id'])){
    $_rows = _fetch_array("SELECT
                                tg_fromuser,tg_content,tg_date
                            FROM
                                tg_message
                            WHERE
                                tg_id='{$_GET['id']}'
                            LIMIT
                                1
        ");
    if(!$_rows){
        _alert_back("此信息不存在！");
    }else{
        $_rows = _html($_rows);
    }
}else{
    _alert_back("非法登录");
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>多用户留言系统--短信详情</title>
<?php 
	require ROOT_PATH.'includes/title.inc.php';
?>
</head>
<body>
<?php 
	require ROOT_PATH.'includes/header.inc.php';
?>

<div id="member_message">
	<?php 
    	require ROOT_PATH.'includes/member.inc.php';
    ?>
	<div id="member_main">
	   <h2>短信详情</h2>
	   <dl>
	       <dd>发 信 人：<?php echo $_rows['tg_fromuser']?></dd>
	       <dd class="content">内容：<strong><?php echo $_rows['tg_content']?></strong></dd>
	       <dd>发信时间：<?php echo $_rows['tg_date']?></dd>
	       <dd class="button"><input type="button" value="返回列表" onclick="javascript:history.back();" /> <input type="button" value="删除短信" /></dd>
	   </dl>
	</div>
</div>
<?php 
	require ROOT_PATH.'includes/footer.inc.php';
?>
</body>
</html>

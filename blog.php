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
define('SCRIPT','blog');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';

global $_pagesize,$_pagenum;
_page("SELECT tg_id FROM tg_user", 15);

$_result = _query("SELECT tg_id,tg_username,tg_sex,tg_face FROM tg_user ORDER BY tg_reg_time DESC LIMIT $_pagenum,$_pagesize");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>多用户留言系统--博友</title>
<?php 
	require ROOT_PATH.'includes/title.inc.php';
?>
<script type="text/javascript" src="js/blog.js"></script>
</head>
<body>
<?php 
	require ROOT_PATH.'includes/header.inc.php';
?>

<div id="blog">
	<h2>博友列表</h2>
	
	<?php 
	   while(!!$_rows = _fetch_array_list($_result)){
	       $_rows = _html($_rows);
	?>
	<dl>
	   <dd class="user"><?php echo $_rows['tg_username']?>(<?php echo $_rows['tg_sex']?>)</dd>
	   <dt><img src="<?php echo $_rows['tg_face']; ?>" alt="<?php echo $_rows['tg_username']?>" /></dt>
	   <dd class="message"><a href='javascript:;' name="message" title="<?php echo $_rows['tg_id']?>">发消息</a></dd>
	   <dd class="friend"><a href='javascript:;' name="friend" title="<?php echo $_rows['tg_id']?>">加为好友</a></dd>
	   <dd class="guest">写留言</dd>
	   <dd class="flower">给他送花</dd>
	</dl>
	<?php }?>
	
	<?php 
	   _free_result($_result);
	   _paging(1)
	?>
</div>
<?php 
	require ROOT_PATH.'includes/footer.inc.php';
?>
</body>
</html>

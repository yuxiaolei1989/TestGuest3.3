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

$_result = _query("SELECT tg_id,tg_username,tg_sex,tg_face,tg_email,tg_url FROM tg_user ORDER BY tg_reg_time DESC LIMIT 1");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>多用户留言系统--首页</title>
<?php 
	require ROOT_PATH.'includes/title.inc.php';
?>
<script type="text/javascript" src="js/blog.js"></script>
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
	   <dd class="flower"><a href='javascript:;' name="flower" title="<?php echo $_rows['tg_id']?>">给他送花</a></dd>
	   <dd class="email">郵件：<?php echo $_rows['tg_email']?></dd>
	   <dd class="url">網址：<?php echo $_rows['tg_url']?></dd>
	</dl>
	<?php }?>
	
	<?php 
	   _free_result($_result);
	?>
</div>

<div id="pics">
	<h2>最新图片</h2>
</div>

<?php 
	require ROOT_PATH.'includes/footer.inc.php';
?>

</body>
</html>

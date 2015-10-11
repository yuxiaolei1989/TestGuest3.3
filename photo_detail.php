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
define('SCRIPT','photo_detail');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';

if(isset($_GET['id'])){
    if(!!$_rows = _fetch_array("SELECT
                                    *
                                FROM
                                    tg_photo
                                WHERE
                                    tg_id='{$_GET['id']}'
                             ")){
                             _query("UPDATE
                                 tg_photo
                                 SET
                                 tg_readcount = tg_readcount+1
                                 WHERE
                                 tg_id='{$_GET['id']}'");
        
    }else{
        _alert_back("不存在此相册");
    }
}else{
    _alert_back("非法操作！");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php 
	require ROOT_PATH.'includes/title.inc.php';
?>
<script type="text/javascript" src="js/code.js"></script>
<script type="text/javascript" src="js/article.js"></script>
</head>
<body>
<?php 
	require ROOT_PATH.'includes/header.inc.php';
?>

<div id="photo">
	<h2><?php echo $_rows['tg_name']?></h2>
    <dl class="detail">
	   <dd><?php echo $_rows['tg_name']?></dd>
	   <dd><img src="<?php echo $_rows['tg_url']?>" /></dd>
	   <dd>图片简介：<?php echo $_rows['tg_content']?> </dd>
	   <dd>浏览量 （<strong><?php echo $_rows['tg_readcount']?></strong>） 评论量 （<strong><?php echo $_rows['tg_commendcount']?></strong>）<br/>上传者：<?php echo $_rows['tg_username']?> 发表时间：<?php echo $_rows['tg_date']?></dd>
	</dl>
	
	<?php if($_COOKIE['username']){?>
	<p class="line"></p>
	<form method="post" name="post" action="?action=rephoto">
	   <a name="re"></a>
		<dl class="reply">
			<dd>标　　题：<input type="text" name="title" class="text" value="RE:<?php echo $_rows['tg_title']?>" /> (*必填，至少两位)</dd>
			<dd id="q">贴　　图：<a href="javascript:;">Q图系列[1]</a> <a href="javascript:;">Q图系列[2]</a> <a href="javascript:;">Q图系列[3]</a></dd>
			<dd>
			     <?php include ROOT_PATH.'includes/ubb.inc.php';?>
			     <textarea name="content" rows="9"></textarea>
			</dd>
			
			<dd>

			         验 证 码：<input type="text" name="code" class="text yzm"  /> <img src="code.php" alt="验证码" id="code" /> 

		    <input type="submit" class="submit" id="submit" value="发表帖子" />
		    </dd>
		</dl>
		<input type="hidden" name="reid" value="<?php echo $_rows['tg_id'];?>" />
		<input type="hidden" name="type" value="<?php echo $_rows['tg_type'];?>" />
	</form>
	<?php }?>
</div>
<?php 
	require ROOT_PATH.'includes/footer.inc.php';
?>
</body>
</html>

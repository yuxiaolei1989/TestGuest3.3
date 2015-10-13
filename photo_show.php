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
define('SCRIPT','photo_show');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';

if(isset($_GET['id'])){
    if(!!$_rows = _fetch_array("SELECT
                                    tg_id,
                                    tg_name
                                FROM
                                    tg_dir
                                WHERE
                                    tg_id='{$_GET['id']}'
                             ")){
         if($_POST['password']){
             if(!!$_rows2 = _fetch_array("SELECT
                 tg_password
                 FROM
                 tg_dir
                 WHERE
                 tg_id='{$_rows['tg_id']}'
                 ")){
                 if($_rows2['tg_password'] == sha1($_POST['password'])){
                     setcookie('photo'.$_rows['tg_id'],$_rows['tg_name']);
                     _location(null, 'photo_show.php?id='.$_rows['tg_id']);
                 }else{
                     _alert_back("相册密码不正确！");
                 }
             }
         }
        
    }else{
        _alert_back("不存在此相册");
    }
}else{
    _alert_back("非法操作！");
}

global $_pagesize,$_pagenum,$_system,$_id;
$_id = 'id='.$_GET['id'].'&';
_page("SELECT tg_id FROM tg_photo WHERE tg_sid='{$_GET['id']}'", $_system['tg_photo']);
$_result = _query("SELECT * FROM tg_photo WHERE tg_sid='{$_GET['id']}' ORDER BY tg_date DESC LIMIT $_pagenum,$_pagesize");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php 
	require ROOT_PATH.'includes/title.inc.php';
?>
<script type="text/javascript" src="js/blog.js"></script>
</head>
<body>
<?php 
	require ROOT_PATH.'includes/header.inc.php';
?>

<div id="photo">
	<h2><?php echo $_rows['tg_name']?></h2>
	<?php 
	
	if(!empty($_rows['tg_type']) || $_COOKIE['photo'.$_rows['tg_id']] == $_rows['tg_name'] || isset($_SESSION['admin'])){
	   while(!!$_rows = _fetch_array_list($_result)){
	       $_rows = _html($_rows);
	?>
	   <dl>
    	   <dt><a href="photo_detail.php?id=<?php echo $_rows['tg_id']?>"><img src="thumb.php?filename=<?php echo $_rows['tg_url']?>&percent=0.3"/></a></dt>
    	   <dd class="name"><?php echo $_rows['tg_name']?></dd>
    	   <dd class="friend">浏览量 （<strong><?php echo $_rows['tg_readcount']?></strong>） 评论量 （<strong><?php echo $_rows['tg_commendcount']?></strong>）<br/>上传者：<?php echo $_rows['tg_username']?></dd>
    	</dl>
	   
	<?php }?>
	
	<?php 
	   _free_result($_result);
	   _paging(2);
	?>
    
	<p><a href="photo_add_img.php?id=<?php echo $_GET['id']?>">上传图片</a></p>

	<?php 
	}else{
	    echo '<form method="post" action="photo_show.php?id='.$_GET['id'].'">';
	    echo '<p>请输入密码：<input type="password" name="password" /> <input type="submit" value="确认" /></p>';
	    echo '</form>';
	}
	?>
</div>
<?php 
	require ROOT_PATH.'includes/footer.inc.php';
?>
</body>
</html>

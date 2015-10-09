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
define('SCRIPT','photo');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';

global $_pagesize,$_pagenum,$_system;
_page("SELECT tg_id FROM tg_dir", $_system['tg_photo']);

$_result = _query("SELECT 
                        * 
                    FROM 
                        tg_dir 
                    ORDER BY 
                        tg_date DESC 
                    LIMIT 
                        $_pagenum,$_pagesize");


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
	<h2>相册列表</h2>
	<?php 
	   while(!!$_rows = _fetch_array_list($_result)){
	       
	       if($_rows['tg_type'] == 1){
	           $_rows['type_html'] = '(公开)';
	       }else{
	           $_rows['type_html'] = '(私密)';
	       }
	       
	       $_rows = _html($_rows);
	       
	       if(empty($_rows['tg_face'])){
	           $_rows['face_html'] = "";
	       }else{
	           $_rows['face_html'] = '<img src="'.$_rows['tg_face'].'" alt="'.$_rows['tg_name'].'" />';
	       }
	?>
	<dl>
	   <dt><a href="photo_show.php?id=<?php echo $_rows['tg_id']?>"><?php echo $_rows['face_html']?></a></dt>
	   <dd><a href="photo_show.php?id=<?php echo $_rows['tg_id']?>"><?php echo $_rows['tg_name']?></a></dd>
	   <?php if(isset($_SESSION['admin'])){?>
	   <dd>[<a href="photo_modify_dir.php?id=<?php echo $_rows['tg_id']?>">修改</a>] [<a>删除</a>]</dd>
	   <?php }?>
	</dl>
	<?php }?>
	<?php if(isset($_SESSION['admin'])){?>
	<p><a href="photo_add_dir">添加相册目录</a></p>
	<?php }?>
</div>
<?php 
	require ROOT_PATH.'includes/footer.inc.php';
?>
</body>
</html>

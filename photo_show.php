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
	   while(!!$_rows = _fetch_array_list($_result)){
	       $_rows = _html($_rows);
	?>
	   <dl>
    	   <dt><a href="photo_detail.php?id=<?php echo $_rows['tg_id']?>"><img src="thumb.php?filename=<?php echo $_rows['tg_url']?>&percent=0.3"/></a></dt>
    	   <dd class="name"><?php echo $_rows['tg_name']?></dd>
    	   <dd class="friend"></dd>
    	</dl>
	   
	<?php }?>
	
	<?php 
	   _free_result($_result);
	   _paging(2);
	?>
    
	<p><a href="photo_add_img.php?id=<?php echo $_GET['id']?>">上传图片</a></p>

</div>
<?php 
	require ROOT_PATH.'includes/footer.inc.php';
?>
</body>
</html>

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
define('SCRIPT','member_message');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';

if(!isset($_COOKIE['username'])){
    _alert_back("请登录账号！", "login.php");
}

global $_pagesize,$_pagenum;
_page("SELECT tg_id FROM tg_message", 15);

$_result = _query("SELECT
                        tg_id,tg_fromuser,tg_content,tg_date 
                    FROM 
                        tg_message 
                    ORDER BY 
                        tg_date 
                    DESC LIMIT 
                        $_pagenum,$_pagesize
                ");


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>多用户留言系统--短信查询</title>
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
	   <h2>短信管理中心</h2>
	   <table cellspacing="1">
	       <tr>
	           <th>发信人</th><th>短信内容</th><th>时间</th><th>操作</th>
	       </tr>
	       <?php 
               while (!!$_rows = _fetch_array_list($_result)){
           ?>
           <tr>
               <td><?php echo $_rows['tg_fromuser']?></td>
               <td><a href="member_message_detail.php?id=<?php echo $_rows['tg_id']?>" title="<?php echo $_rows['tg_content']?>"><?php echo _title($_rows['tg_content'])?></a></td>
               <td><?php echo $_rows['tg_date']?></td>
               <td><input type="checkbox" /></td>
           </tr>
           <?php 
              }
           ?>
	   </table>
	   <?php 
    	   _free_result($_result);
    	   _paging(2)
    	?>
	</div>
</div>
<?php 
	require ROOT_PATH.'includes/footer.inc.php';
?>
</body>
</html>

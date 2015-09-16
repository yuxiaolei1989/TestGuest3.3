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
define('SCRIPT','manage_job');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';

_manage_login();

echo $_SESSION['admin'];

if($_GET['action'] == 'add'){
    $username = _mysql_string($_POST['manage']);
    $_result = _fetch_array("SELECT
                                    tg_id,
                                    tg_level
                                FROM
                                    tg_user
                                WHERE
                                    tg_username='{$username}'
                            ");
    
    if(!$_result){
        _alert_back("此用户不存在！");
    }
    if($_result['tg_level'] == 1){
        _alert_back("此用户已经是管理员了！");
    }
    _query("UPDATE
                tg_user
            SET
                tg_level=1
            WHERE
                tg_username='{$username}'
            LIMIT
                1
        ");
    if(_affected_rows() == 1){
        _close();
        _location("管理员添加成功！", "manage_job.php");
    }else{
        _close();
        _location("很遗憾，管理员添加失败", "manage_job.php");
    }
    
}

if($_GET['action'] == 'del' && isset($_GET['id'])){
    $_id = $_GET['id'];
    $_result = _fetch_array("SELECT
                            tg_username
                        FROM
                            tg_user
                        WHERE
                            tg_id='{$_id}'
                     ");
    if($_result['tg_username'] != $_COOKIE['username']){
        _alert_back("只能给自己辞职，不能帮别人辞职！");
    }
    _query("UPDATE
                tg_user
            SET
                tg_level=0
            WHERE
                tg_username='{$_COOKIE['username']}'
            LIMIT
                1
           ");
    if(_affected_rows() == 1){
        _close();
        _session_destroy();
        _location("辞职成功！", "index.php");
    }else{
        _close();
        _location("很遗憾，辞职失败", "manage_member.php");
    }
}

global $_pagesize,$_pagenum;
_page("SELECT tg_id FROM tg_user", 15);

$_result = _query("SELECT 
                        tg_id,
                        tg_username,
                        tg_reg_time,
                        tg_email
                    FROM 
                        tg_user 
                    WHERE
                        tg_level=1
                    ORDER BY 
                        tg_reg_time 
                    DESC LIMIT
                        $_pagenum,$_pagesize
                ");




?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php 
	require ROOT_PATH.'includes/title.inc.php';
?>
<script type="text/javascript" src="js/member_message.js"></script>
</head>
<body>
<?php 
	require ROOT_PATH.'includes/header.inc.php';
?>

<div id="member_message">
	<?php 
    	require ROOT_PATH.'includes/manage.inc.php';
    ?>
	<div id="member_main">
	   <h2>会员列表中心</h2>
	   <table cellspacing="1">
	       <tr>
	           <th>ID号</th><th>会员名</th><th>邮件</th><th>注册时间</th><th>操作</th>
	       </tr>
	       <?php 
	           while(!!$_rows = _fetch_array_list($_result)){
	           $_rows = _html($_rows);
	           if($_rows['tg_username'] == $_COOKIE['username']){
	               $_job_html = '[<a href="?action=del&id='.$_rows['tg_id'].'">辞职</a>]';
	           }else{
	               $_job_html = '[无权操作]';
	           }
	       ?>
           <tr>
               <td><?php echo $_rows['tg_id']?></td>
               <td>
                    <?php echo $_rows['tg_username']?>
               </td>
               <td><?php echo $_rows['tg_email']?></td>
               <td>
               <?php 
                echo $_rows['tg_reg_time'];
               ?>
               </td>
               <td> <?php echo $_job_html;?> </td>
           </tr>
           <?php 
              }
           ?>
          
	   </table>
	   <form method="post" action="?action=add">
	       <input name="manage" class="text" /> <input type="submit" value="添加管理员" />
	   </form>
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

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
define('SCRIPT','member_friend');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';

if(!isset($_COOKIE['username'])){
    _alert_back("请登录账号！", "login.php");
}

if($_GET['action'] == 'check' && isset($_GET['id'])){
    $_rows = _fetch_array("SELECT
                                tg_id
                           FROM
                                tg_friend
                           WHERE
                                tg_id='{$_GET['id']}'
                           LIMIT
                                1
                          ");
    if(!!$_rows){
        _query("UPDATE
                    tg_friend
                SET
                    tg_state=1
                WHERE
                    tg_id='{$_GET['id']}'
                LIMIT
                    1
              ");
        if(_affected_rows() == 1){
            _close();
            _location("验证成功！", "member_friend.php");
        }else{
            _close();
            _alert_back("验证失败！");
        }
    }else{
        _alert_back("此好友不存在！");
    }
}

if($_GET['action'] == 'delete' && isset($_POST['ids'])){
    $_clean = array();
    $_clean['ids'] = _mysql_string(implode(",",$_POST['ids']));
   
    if(!!$_rows =_fetch_array("SELECT tg_uniqid FROM tg_user WHERE tg_username='{$_COOKIE['username']}'")){
    
        _uniqid($_rows['tg_uniqid'], $_COOKIE['uniqid']);
        
        _query("DELETE FROM 
                    tg_friend
                WHERE
                    tg_id
                IN
                    ({$_clean['ids']})
        ");
        if(_affected_rows()){
            _close();
            _location("好友删除成功！", "member_friend.php");
        }else{
            _close();
            _alert_back("好友删除失败！");
        }
    }else{
        _alert_back("非法登录！");
    }
}

global $_pagesize,$_pagenum;
_page("SELECT 
            tg_id 
       FROM 
            tg_friend 
       WHERE
            tg_touser='{$_COOKIE['username']}'
       OR 
            tg_fromuser='{$_COOKIE['username']}'
       ", 15);

$_result = _query("SELECT
                        tg_id,tg_state,tg_fromuser,tg_touser,tg_content,tg_date 
                    FROM 
                        tg_friend
                    WHERE
                        tg_touser='{$_COOKIE['username']}'
                    OR 
                        tg_fromuser='{$_COOKIE['username']}'
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
<title>多用户留言系统--好友列表</title>
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
    	require ROOT_PATH.'includes/member.inc.php';
    ?>
	<div id="member_main">
	   <h2>好友设置中心</h2>
	   <form name="" method="post" action="?action=delete">
	   <table cellspacing="1">
	       <tr>
	           <th>好友</th><th>请求内容</th><th>时间</th><th>狀態</th><th>操作</th>
	       </tr>
	       <?php 
               while (!!$_rows = _fetch_array_list($_result)){
           ?>
           <tr>
               <td>
                    <?php 
                        if($_rows['tg_fromuser'] == $_COOKIE['username']){
                            echo $_rows['tg_touser'];
                        }else{
                            echo $_rows['tg_fromuser'];
                        }
                    ?>
               </td>
               <td>
                    <?php
                        if(empty($_rows['tg_state'])){
                            echo "<strong>"._title($_rows['tg_content'])."</strong>";
                        }else{
                            echo _title($_rows['tg_content']);
                        }
                    ?>
               </td>
               <td><?php echo $_rows['tg_date']?></td>
               <td>
               <?php 
                    if($_rows['tg_fromuser'] == $_COOKIE['username']){
                        if(empty($_rows['tg_state'])){
                            echo "<span style='color:gray'>对方未验证！</span>";
                        }else{
                            echo "<span style='color:green'>对方验证通过！</span>";
                        }
                    }else{
                        if(empty($_rows['tg_state'])){
                            echo "<a href='?action=check&id=".$_rows['tg_id']."' style='color:red'>你未验证！</a>";
                        }else{
                            echo "<span style='color:green'>你验证通过！</span>";
                        }
                    }
                ?>
               </td>
               <td><input type="checkbox" name="ids[]" value="<?php echo $_rows['tg_id']?>" /></td>
           </tr>
           <?php 
              }
           ?>
           <tr><td colspan="5"> <label>全选 <input type="checkbox" name="chkall" id="all"/></label> <input type="submit" value="批删除" /></td></tr>
	   </table>
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

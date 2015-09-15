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
define('SCRIPT','login');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';

_login_state();

global $_system;

if($_GET['action'] == 'login'){
    if($_system['tg_code'] == 1){
        _check_code($_POST['code'],$_SESSION['code']);
    }
    include ROOT_PATH.'includes/login.func.php';
    
    $_clean = array();
    
    $_clean['username'] = _check_username($_POST['username'],2,20);
    $_clean['password'] = _check_password($_POST['password'],6);
    $_clean['time'] = _check_time($_POST['time']);
    
    if(!!$_rows = _fetch_array("SELECT tg_username,tg_level,tg_uniqid FROM tg_user WHERE tg_username='{$_clean['username']}' AND tg_password='{$_clean['password']}' AND tg_active='' LIMIT 1")){
        _query("UPDATE tg_user SET 
                        tg_last_time=NOW(),
                        tg_last_ip='{$_SERVER["REMOTE_ADDR"]}',
                        tg_login_count=tg_login_count+1
                        WHERE tg_username='{$_rows['tg_username']}'");
        if($_rows['tg_level'] == 1){
            $_SESSION['admin'] = $_rows['tg_username'];
        }
        _close();
        _setcookies($_rows['tg_username'],$_rows['tg_uniqid'],$_clean['time']);
        _location(null,"member.php");
    }else{
        _close();
        _session_destroy();
        _location("该用户名、密码不正确或者账户未激活！","login.php");
    }
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
<script type="text/javascript" src="js/login.js"></script>
</head>
<body>
<?php 
	require ROOT_PATH.'includes/header.inc.php';
?>

<div id="login">
    <h2>登录</h2>
    <form method="post" name="login" action="login.php?action=login">
		<dl>
			<dt>&nbsp;</dt>
			<dd>用户名：<input type="text" name="username" class="text" /> (*必填，至少两位)</dd>
			<dd>密　码：<input type="password" name="password" class="text" /> (*必填，至少六位)</dd>
			<dd>保　留：<input type="radio" name="time" checked="checked" value="0" /> 不保留  <input type="radio" name="time" value="1" />一天 <input type="radio" name="time" value="2" />一周  <input type="radio" name="time" value="3" />一个月</dd>
			<?php if($_system['tg_code'] == 1){ ?>
			<dd>验 证 码：<input type="text" name="code" class="text code"  /> <img src="code.php" alt="验证码" id="code" /></dd>
			<?php }?>
			<dd><input type="submit" class="button" id="submit" value="登录" /><input type="button" class="button" id="regiter" value="注册" /></dd>
	   </dl>
	</form>
</div>

<?php 
	require ROOT_PATH.'includes/footer.inc.php';
?>
</body>
</html>
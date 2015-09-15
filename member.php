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
define('SCRIPT','member');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';

if(!isset($_COOKIE['username'])){
    _location("请登录账号！", "login.php");
}else{
    $_rows = _fetch_array("SELECT tg_username,tg_sex,tg_face,tg_email,tg_url,tg_qq,tg_level,tg_reg_time FROM tg_user WHERE tg_username='{$_COOKIE['username']}' LIMIT 1");
    if($_rows){
        $_html = array();
        $_html['username'] = $_rows['tg_username'];
        $_html['sex'] = $_rows['tg_sex'];
        $_html['face'] = $_rows['tg_face'];
        $_html['email'] = $_rows['tg_email'];
        $_html['url'] = $_rows['tg_url'];
        $_html['qq'] = $_rows['tg_qq'];
        $_html['reg_time'] = $_rows['tg_reg_time'];
        switch($_rows['tg_level']){
            case 0 : 
                $_html['text'] = "普通会员";
                break;
            case 1 :
                $_html['text'] = "管理员";
                break;
        }
        $_html = _html($_html);
    }else{
        _alert_back("此用户不存在！");
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
</head>
<body>
<?php 
	require ROOT_PATH.'includes/header.inc.php';
?>

<div id="member">
	<?php 
    	require ROOT_PATH.'includes/member.inc.php';
    ?>
	<div id="member_main">
	   <h2>会员管理中心</h2>
	   <dl>
	       <dd>用  户 名：<?php echo $_html['username']?></dd>
	       <dd>性　　别：<?php echo $_html['sex']?></dd>
	       <dd>头　　像：<?php echo $_html['face']?></dd>
	       <dd>电子邮件：<?php echo $_html['email']?></dd>
	       <dd>主　　页：<?php echo $_html['url']?></dd>
	       <dd>　　QQ :<?php echo $_html['qq']?></dd>
	       <dd>注册时间：<?php echo $_html['reg_time']?></dd>
	       <dd>身　　份：<?php echo $_html['text']?></dd>
	   </dl>
	</div>
</div>
<?php 
	require ROOT_PATH.'includes/footer.inc.php';
?>
</body>
</html>

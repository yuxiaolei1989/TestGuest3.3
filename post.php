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
define('SCRIPT','post');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';

if($_GET['action'] == 'register'){
    
    _check_code($_POST['code'],$_SESSION['code']);
    
    include ROOT_PATH.'includes/check.func.php';
    $_clean = array();
    $_clean['uniqid'] = _check_uniqid($_POST['uniqid'],$_SESSION['uniqid']);
    $_clean['active'] = _sha1_uniqid();
    $_clean['username'] = _check_username($_POST['username']);
    $_clean['password'] = _check_password($_POST['password'], $_POST['notpassword'],6);
    $_clean['question'] = _check_qustion($_POST['question'],2,20);
    $_clean['answer'] = _check_answer($_POST['question'],$_POST['answer'],2,20);
    $_clean['sex'] = _check_sex($_POST['sex']);
    $_clean['face'] = _check_face($_POST['face']);
    $_clean['email'] = _check_email($_POST['email'],6,40);
    $_clean['qq'] = _check_qq($_POST['qq']);
    $_clean['url'] = _check_url($_POST['url'],40);
    
    _is_repeat("SELECT tg_username FROM tg_user WHERE tg_username='{$_clean['username']}' LIMIT 1","对不起，此用户已被注册！");
    
    _query(
        "INSERT INTO tg_user (
            tg_uniqid,
            tg_active,
            tg_username,
            tg_password,
            tg_question,
            tg_answer,
            tg_sex,
            tg_face,
            tg_email,
            tg_qq,
            tg_url,
            tg_reg_time,
            tg_last_time,
            tg_last_ip
        ) VALUES (
            '{$_clean['uniqid']}',
            '{$_clean['active']}',
            '{$_clean['username']}',
            '{$_clean['password']}',
            '{$_clean['question']}',
            '{$_clean['answer']}',
            '{$_clean['sex']}',
            '{$_clean['face']}',
            '{$_clean['email']}',
            '{$_clean['qq']}',
            '{$_clean['url']}',
            NOW(),
            NOW(),
            '{$_SERVER['REMOTE_ADDR']}'
    )");
    
    if(_affected_rows() == 1){
        $_clean['id'] = _insert_id();
        _close();
        _session_destroy();
        
        _set_xml("new.xml",$_clean);
        _location("恭喜你注册成功！", "active.php?active=".$_clean['active']);
    }else{
        _close();
        _session_destroy();
        _location("很遗憾，注册失败了~_~", "register.php");
    }
    
}

$_SESSION['uniqid'] = $_uniqid = _sha1_uniqid();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>多用户留言系统--发布</title>
<?php 
	require ROOT_PATH.'includes/title.inc.php';
?>
<script type="text/javascript" src="js/code.js"></script>
<script type="text/javascript" src="js/post.js"></script>
</head>
<body>
<?php 
	require ROOT_PATH.'includes/header.inc.php';
?>

<div id="post">
	<h2>发布帖子</h2>
	<form method="post" name="post" action="?action=post">
		<dl>
			<dt>请认真填写一下内容</dt>
			<dd>
			     类　　型：
			 <?php 
			     foreach(range(1,16) as $_num){
			         if($_num == 1){
			             echo '<label><input type="radio" value="'.$_num.'" name="type" checked="checked" /> ';
			         }else{
			             echo '<label><input type="radio" value="'.$_num.'" name="type" /> ';
			         }
			         
			         echo '<img src="images/icon'.$_num.'.gif" /></label>　';
			         
			         if($_num == 8){
			             echo "<br/>　　　　　 ";
			         }
			     }
			 ?>
			</dd>
			<dd>标　　题：<input type="text" name="title" class="text" /> (*必填，至少两位)</dd>
			<dd><textarea></textarea></dd>
			<dd>验 证 码：<input type="text" name="code" class="text yzm"  /> <img src="code.php" alt="验证码" id="code" /> <input type="submit" class="submit" id="submit" value="发表帖子" /></dd>
		</dl>
	</form>
</div>
<?php 
	require ROOT_PATH.'includes/footer.inc.php';
?>
</body>
</html>

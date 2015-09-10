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
define('SCRIPT','member_modify');

//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';
    
if($_GET['action'] == 'modify'){
    _check_code($_POST['code'],$_SESSION['code']);
        
    if(!!$_rows =_fetch_array("SELECT tg_uniqid FROM tg_user WHERE tg_username='{$_COOKIE['username']}'")){
        
        _uniqid($_rows['tg_uniqid'], $_COOKIE['uniqid']);
        
        include ROOT_PATH.'includes/check.func.php';
        
        $_clean = array();
        $_clean['password'] = _check_modify_password($_POST['password'],6);
        $_clean['sex'] = _check_sex($_POST['sex']);
        $_clean['face'] = _check_face($_POST['face']);
        $_clean['switch'] = _check_face($_POST['switch']);
        $_clean['autograph'] = _check_autograph($_POST['autograph'],200);
        $_clean['email'] = _check_email($_POST['email'],6,40);
        $_clean['qq'] = _check_qq($_POST['qq']);
        $_clean['url'] = _check_url($_POST['url'],40);
        
        
        if(empty($_clean['password'])){
            _query("UPDATE tg_user SET
                tg_sex='{$_clean['sex']}',
                tg_face='{$_clean['face']}',
                tg_switch='{$_clean['switch']}',
                tg_autograph='{$_clean['autograph']}',
                tg_email='{$_clean['email']}',
                tg_qq='{$_clean['qq']}',
                tg_url='{$_clean['url']}'
             WHERE
                tg_username='{$_COOKIE['username']}'
                ");
        }else{
            _query("UPDATE tg_user SET
                tg_password='{$_clean['password']}',
                tg_sex='{$_clean['sex']}',
                tg_face='{$_clean['face']}',
                tg_switch='{$_clean['switch']}',
                tg_autograph='{$_clean['autograph']}',
                tg_email='{$_clean['email']}',
                tg_qq='{$_clean['qq']}',
                tg_url='{$_clean['url']}'
             WHERE
                tg_username='{$_COOKIE['username']}'
                ");
        }
        
        if(_affected_rows() == 1){
            _close();
            _session_destroy();
            _location("恭喜你修改成功！", "member.php");
        }else{
            _close();
            _session_destroy();
            _location("很遗憾，没有任何数据被修改", "member_modify.php");
        }
    }
}
if(!isset($_COOKIE['username'])){
    _location("请登录账号！", "login.php");
}else{
    $_rows = _fetch_array("SELECT tg_username,tg_sex,tg_face,tg_switch,tg_autograph,tg_email,tg_url,tg_qq FROM tg_user WHERE tg_username='{$_COOKIE['username']}'");
    if($_rows){
        $_html = array();
        $_html['username'] = $_rows['tg_username'];
        $_html['sex'] = $_rows['tg_sex'];
        $_html['face'] = $_rows['tg_face'];
        $_html['switch'] = $_rows['tg_switch'];
        $_html['autograph'] = $_rows['tg_autograph'];
        $_html['email'] = $_rows['tg_email'];
        $_html['url'] = $_rows['tg_url'];
        $_html['qq'] = $_rows['tg_qq'];        
        $_html = _html($_html);
        
        //性别选择
        if($_html['sex'] == '男'){
            $_html['sex_html'] = '<input type="radio" name="sex" value="男" checked="checked" />男 <input type="radio" name="sex" value="女" />女';
        }else{
            $_html['sex_html'] = '<input type="radio" name="sex" value="男" />男 <input type="radio" name="sex" value="女" checked="checked" />女';
        }
        
        //头像选择
        $_html['face_html'] = '<select name="face">';
        foreach(range(1,9) as $_num){
            $_html['face_html'] .= '<option value="face/m0'.$_num.'.gif">face/m0'.$_num.'.gif</option>';
        }
        foreach(range(10,64) as $_num){
            $_html['face_html'] .= '<option value="face/m'.$_num.'.gif">face/m'.$_num.'.gif</option>';
        }
        $_html['face_html'] .= '</select>';
        
        //签名开关
        if($_html['switch'] == 1){
            $_html['switch_str'] = '<input type="radio" name="switch" value="1" checked="checked"/> 启用 <input type="radio" name="switch" value="0"/> 禁用 ';
        }else{
            $_html['switch_str'] = '<input type="radio" name="switch" value="1" /> 启用 <input type="radio" name="switch" value="0" checked="checked" /> 禁用 ';
        }
    }else{
        _alert_back("此用户不存在！");
    }
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>多用户留言系统--个人中心</title>
<?php 
	require ROOT_PATH.'includes/title.inc.php';
?>
<script type="text/javascript" src="js/code.js"></script>
<script type="text/javascript" src="js/member_modify.js"></script>
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
	   <form method="post" action="?action=modify">
	   <dl>
	       <dd>用  户 名：<?php echo $_html['username']?></dd>
	       <dd>密　　码：<input type="password" name="password" class="text" /> (留空则不修改)</dd>
	       <dd>性　　别：<?php echo $_html['sex_html']?></dd>
	       <dd>头　　像：<?php echo $_html['face_html']?></dd>
	       <dd>电子邮件：<input type="text" class="text" name="email" value="<?php echo $_html['email']?>" /></dd>
	       <dd>主　　页：<input type="text" class="text" name="url" value="<?php echo $_html['url']?>"/></dd>
	       <dd>　　QQ :<input type="text" class="text" name="qq" value="<?php echo $_html['qq']?>" /></dd>
	       <dd>
	                           个性签名：<?php echo $_html['switch_str'];?>(可以使用UBB代码)
	           <p><textarea name="autograph"><?php echo $_html['autograph'];?></textarea></p>
	       </dd>
	       <dd>验 证 码：<input type="text" name="code" class="text yzm"  /> <img src="code.php" alt="验证码" id="code" /></dd>
		   <dd><input type="submit" class="submit" id="submit" value="修改资料" /></dd>
	   </dl>
	   </form>
	</div>
</div>
<?php 
	require ROOT_PATH.'includes/footer.inc.php';
?>
</body>
</html>

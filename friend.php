<?php
/**
* TestGuest Version1.0
* Author: yulei@addcn.com
* Date: 2015-9-02
*/
session_start();
//定义个常量，用来授权调用includes里面的文件
define('IN_TG',true);
//定义个常量，用来指定本页的内容
define('SCRIPT','friend');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';

if(!isset($_COOKIE['username'])){
    _alert_close("请先登录！");
}

if($_GET['action'] == 'add'){
    _check_code($_POST['code'],$_SESSION['code']);
    
    if(!!$_rows =_fetch_array("SELECT tg_uniqid FROM tg_user WHERE tg_username='{$_COOKIE['username']}'")){
        _uniqid($_rows['tg_uniqid'], $_COOKIE['uniqid']);
        include ROOT_PATH.'includes/check.func.php';
        $_clean = array();
        $_clean['touser'] = $_POST['touser'];
        $_clean['fromuser'] = $_COOKIE['username'];
        $_clean['content'] = _check_content($_POST['content'],10,200);
        $_clean = _mysql_string($_clean);
        
        if($_clean['touser'] == $_clean['fromuser']){
            _alert_close("请不要添加自己！");
            exit;
        }
        
        if(!!$_rows2 = _fetch_array("SELECT
                                        tg_id
                                     FROM
                                        tg_friend
                                     WHERE
                                        (tg_touser='{$_clean['touser']}'
                                     AND
                                        tg_fromuser='{$_clean['fromuser']}')
                                     OR
                                        (tg_touser='{$_clean['fromuser']}'
                                     AND
                                        tg_fromuser='{$_clean['touser']}')
                                     LIMIT
                                        1
                                    ")){
            _alert_close("你们已经是好友了！");
            exit;
        }
    
        _query("INSERT INTO tg_friend (
            tg_touser,
            tg_fromuser,
            tg_content,
            tg_date
        )
            VALUES (
            '{$_clean['touser']}',
            '{$_clean['fromuser']}',
            '{$_clean['content']}',
            NOW()
        )
        ");
    
        if(_affected_rows() == 1){
            _close();
            _session_destroy();
            _alert_close("添加好友信息已發送！");
        }else{
            _close();
            _session_destroy();
            _alert_back("发送信息失败！");
        }
    }else{
        _alert_close("非法登录！");
    }
    
}

if(isset($_GET['id'])){
    $_rows = _fetch_array("SELECT tg_username FROM tg_user WHERE tg_id='{$_GET['id']}' LIMIT 1");
    if($_rows){
        $_rows = _html($_rows);
    }else{
        _alert_close("数据获取失败！");
    }
}else{
    _alert_close("非法操作！");
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>多用户留言系统--添加好友</title>
<?php 
	require ROOT_PATH.'includes/title.inc.php';
?>
<script type="text/javascript" src="js/code.js"></script>
<script type="text/javascript" src="js/message.js"></script>
</head>
<body>

<div id="message">
	<h3>添加好友</h3>
	<form method="post" action="?action=add">
	   <dl>
	       <dd><input type="text" class="text" readonly="readonly" value="to:<?php echo $_rows['tg_username']?>" /></dd>
	       <dd><textarea rows="" cols="" name="content">我非常想和你交朋友！</textarea></dd>
	       <dd>验 证 码：<input type="text" name="code" class="text code"  /> <img src="code.php" alt="验证码" id="code" /> <input type="submit" class="button" id="submit" value="確認添加" /></dd>
	   </dl>
	   <input type="hidden" name="touser" value="<?php echo $_rows['tg_username']?>" />
	</form>
	
</div>
</body>
</html>

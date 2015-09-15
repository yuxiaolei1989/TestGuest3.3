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
define('SCRIPT','flower');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';

if(!isset($_COOKIE['username'])){
    _alert_close("请先登录！");
}

if($_GET['action'] == 'send'){
    _check_code($_POST['code'],$_SESSION['code']);
    
    if(!!$_rows =_fetch_array("SELECT tg_uniqid FROM tg_user WHERE tg_username='{$_COOKIE['username']}'")){
        _uniqid($_rows['tg_uniqid'], $_COOKIE['uniqid']);
        include ROOT_PATH.'includes/check.func.php';
        $_clean = array();
        $_clean['touser'] = $_POST['touser'];
        $_clean['fromuser'] = $_COOKIE['username'];
        $_clean['flower'] = $_POST['flower'];
        $_clean['content'] = _check_content($_POST['content'],10,200);
        $_clean = _mysql_string($_clean);
        
        if($_clean['touser'] == $_clean['fromuser']){
            _alert_close("请不要给自己送花！");
            exit;
        }
    
        _query("INSERT INTO tg_flower (
            tg_touser,
            tg_fromuser,
            tg_flower,
            tg_content,
            tg_date
        )
            VALUES (
            '{$_clean['touser']}',
            '{$_clean['fromuser']}',
            '{$_clean['flower']}',
            '{$_clean['content']}',
            NOW()
        )
        ");
    
        if(_affected_rows() == 1){
            _close();
            //_session_destroy();
            _alert_close("送花成功！");
        }else{
            _close();
            //_session_destroy();
            _alert_back("送花失败！");
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
<?php 
	require ROOT_PATH.'includes/title.inc.php';
?>
<script type="text/javascript" src="js/code.js"></script>
<script type="text/javascript" src="js/message.js"></script>
</head>
<body>

<div id="message">
	<h3>送花</h3>
	<form method="post" action="?action=send">
	   <dl>
	       <dd>
	           <input type="text" class="text" readonly="readonly" value="to:<?php echo $_rows['tg_username']?>" />
	           <select name="flower">
	           <?php 
	               foreach(range(1,100) as $_num){
	                   echo '<option value="'.$_num.'">x'.$_num.'朵</option>';
	               }
	           ?>
	               
	           </select>
	       </dd>
	       <dd><textarea rows="" cols="" name="content">我非常看好你唷！送你一朵大红花！</textarea></dd>
	       <dd>验 证 码：<input type="text" name="code" class="text code"  /> <img src="code.php" alt="验证码" id="code" /> <input type="submit" class="button" id="submit" value="確認送花" /></dd>
	   </dl>
	   <input type="hidden" name="touser" value="<?php echo $_rows['tg_username']?>" />
	</form>
	
</div>
</body>
</html>

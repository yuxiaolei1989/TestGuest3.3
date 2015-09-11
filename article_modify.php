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
define('SCRIPT','article_modify');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';

if(!isset($_COOKIE['username'])){
    _location("请先登录", "login.php");
}

if(isset($_GET['id'])){
    $_rows = _fetch_array("SELECT
                                tg_username,
                                tg_type,
                                tg_title,
                                tg_content,
                                tg_reid
                            FROM
                                tg_article
                            WHERE
                                tg_id='{$_GET['id']}'
        ");
    if(!!$_rows){
        if($_rows['tg_username'] != $_COOKIE['username']){
            _alert_back("這不是你的帖子，你不能進行修改！");
        }
        $_rows = _html($_rows);
        if($_rows['tg_reid'] == 0){
            $_clean['text'] = '帖子';
        }else{
            $_clean['text'] = '回復';
        }
    }else{
        _alert_back("找不到這個帖子！");
    }
}else{
    _alert_back("非法操作！");
}

if($_GET['action'] == 'modify'){
    
    _check_code($_POST['code'],$_SESSION['code']);
    
    $_rows2 = _fetch_array("SELECT 
                            tg_uniqid
                     FROM
                            tg_user
                     WHERE
                            tg_username='{$_COOKIE['username']}'");

    
    _uniqid($_rows2['tg_uniqid'], $_COOKIE['uniqid']);
    
    include ROOT_PATH.'includes/check.func.php';
    $_clean = array();
    if($_rows['tg_reid'] == 0){
        $_clean['type'] = $_POST['type'];
        $_clean['id'] = $_GET['id'];
    }else{
        $_clean['type'] = $_rows['tg_type'];
        $_clean['id'] = $_rows['tg_reid'];
    }

    $_clean['title'] = _check_post_title($_POST['title'],2,40);
    $_clean['content'] = _check_post_content($_POST['content'],2);
    $_clean = _mysql_string($_clean);
    _query("UPDATE
                tg_article
            SET
                tg_title = '{$_clean['title']}',
                tg_type = '{$_clean['type']}',
                tg_content = '{$_clean['content']}',
                tg_modify_date = NOW()
            WHERE
                tg_id='{$_GET['id']}'
        ");
    
    if(_affected_rows() == 1){
        _close();
        //_session_destroy();
        _location('恭喜你'.$_clean['text'].'修改成功！', "article.php?id=".$_clean['id']);
    }else{
        _close();
        //_session_destroy();
        _alert_back('很遗憾，'.$_clean['text'].'修改失败了~_~');
    }
    
}

$_SESSION['uniqid'] = $_uniqid = _sha1_uniqid();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>多用户留言系统--修改<?php echo $_clean['text']?></title>
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
	<h2>修改<?php echo $_clean['text']?></h2>
	<form method="post" name="post" action="?id=<?php echo $_GET['id']?>&action=modify">
		<dl>
			<dt>请认真修改以下内容</dt>
			<?php if($_rows['tg_reid'] == 0){?>
			<dd>
			     类　　型：
			 <?php 
			     foreach(range(1,16) as $_num){
			         if($_num == $_rows['tg_type']){
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
			<?php }?>
			<dd>标　　题：<input type="text" name="title" class="text" value="<?php echo $_rows['tg_title']?>" /> (*必填，至少两位)</dd>
			<dd id="q">贴　　图：<a href="javascript:;">Q图系列[1]</a> <a href="javascript:;">Q图系列[2]</a> <a href="javascript:;">Q图系列[3]</a></dd>
			<dd>
			     <?php include ROOT_PATH.'includes/ubb.inc.php';?>
			     <textarea name="content" rows="9"><?php echo $_rows['tg_content']?></textarea>
			</dd>
			<dd>验 证 码：<input type="text" name="code" class="text yzm"  /> <img src="code.php" alt="验证码" id="code" /> <input type="submit" class="submit" id="submit" value="修改<?php echo $_clean['text']?>" /></dd>
		</dl>
	</form>
</div>
<?php 
	require ROOT_PATH.'includes/footer.inc.php';
?>
</body>
</html>

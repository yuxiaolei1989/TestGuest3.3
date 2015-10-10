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
define('SCRIPT','photo_add_img');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';

if(!$_COOKIE['username']){
    _alert_back("非法登录");
}



if($_GET['action'] == 'addimg'){
    if (!!$_rows = _fetch_array("SELECT
        tg_uniqid
        FROM
        tg_user
        WHERE
        tg_username='{$_COOKIE['username']}'
        LIMIT
        1"
    )) {
        _uniqid($_rows['tg_uniqid'],$_COOKIE['uniqid']);
        include ROOT_PATH.'includes/check.func.php';
        $_clean = array();
        $_clean['name'] = _check_dir_name($_POST['name'],2,20);
        $_clean['url'] = _check_photo_url($_POST['url']);
        $_clean['content'] = $_POST['content'];
        $_clean['sid'] = $_POST['sid'];
        $_clean = _mysql_string($_clean);
        _query("INSERT INTO tg_photo (
            tg_name,
            tg_url,
            tg_content,
            tg_sid,
            tg_username,
            tg_date
        )
            VALUES (
            '{$_clean['name']}',
            '{$_clean['url']}',
            '{$_clean['content']}',
            '{$_clean['sid']}',
            '{$_COOKIE['username']}',
            NOW()
        )
        ");
        if(_affected_rows() == 1){
            _close();
            _location("恭喜你图片添加成功！", "photo_show.php?id=".$_clean['sid']);
        }else{
            _close();
            _location("很遗憾，图片添加失败！", "photo_add_dir.php");
        }
    }else{
        _alert_back("非法操作444！");
    }   
}

if(isset($_GET['id'])){
    if(!!$_rows = _fetch_array("SELECT
        tg_id,
        tg_dir
        FROM
        tg_dir
        WHERE
        tg_id='{$_GET['id']}'
        ")){
         

    }else{
        _alert_back("不存在此相册");
    }
}else{
    _alert_back("非法操作！");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php 
	require ROOT_PATH.'includes/title.inc.php';
?>
<script type="text/javascript" src="js/photo_add_img.js"></script>
</head>
<body>
<?php 
	require ROOT_PATH.'includes/header.inc.php';
?>

<div id="photo_add_dir">
	<h2>添加上传图片</h2>
	<form action="?action=addimg" method="post">
	<dl>
	   <dd>图片名称：<input type="text" name="name" class="text" /></dd>
	   <dd>图片地址：<input type="text" name="url" id="url" readonly="readonly" class="text" /> <a href="javascript:;" id="up" title="<?php echo $_rows['tg_dir']?>">上传</a></dd>
	   <dd>图片描述：<textarea name="content"></textarea></dd>
	   <dd><input type="submit" value="添加图片" class="submit" /></dd>
	</dl>
	<input type="hidden" name="sid" value="<?php echo $_GET['id']?>"/>
	</form>
</div>
<?php 
	require ROOT_PATH.'includes/footer.inc.php';
?>
</body>
</html>

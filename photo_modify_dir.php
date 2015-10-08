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
define('SCRIPT','photo_add_dir');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';

_manage_login();

if($_GET['action'] == 'modify'){

    _check_code($_POST['code'],$_SESSION['code']);

    $_rows2 = _fetch_array("SELECT
                                tg_uniqid
                            FROM
                                tg_user
                            WHERE
                                tg_username='{$_COOKIE['username']}'
                            ");


    _uniqid($_rows2['tg_uniqid'], $_COOKIE['uniqid']);

    include ROOT_PATH.'includes/check.func.php';
    $_clean = array();
    $_clean['id'] = $_POST['id'];
    $_clean['name'] = _check_dir_name($_POST['name'],2,20);
    $_clean['type'] = $_POST['type'];
    if($_clean['type'] == 1){
        $_clean['password'] = _check_dir_password($_POST['password'],2);
    }else{
        $_clean['password'] = "";
    }
    $_clean['face'] = $_POST['face'];
    $_clean['content'] = $_POST['content'];
    $_clean = _mysql_string($_clean);
    if(empty($_clean['type'])){
        _query("UPDATE
                    tg_dir
                SET
                    tg_name = '{$_clean['name']}',
                    tg_type = '{$_clean['type']}',
                    tg_password = NULL,
                    tg_face = '{$_clean['face']}',
                    tg_content = '{$_clean['content']}'
                WHERE
                    tg_id='{$_clean['id']}'
                LIMIT
                    1
            ");
    }else{
        _query("UPDATE
                    tg_dir
                SET
                    tg_name = '{$_clean['name']}',
                    tg_type = '{$_clean['type']}',
                    tg_password = '{$_clean['password']}',
                    tg_face = '{$_clean['face']}',
                    tg_content = '{$_clean['content']}'
                WHERE
                    tg_id='{$_clean['id']}'
                LIMIT
                    1
            ");
    }

    if(_affected_rows() == 1){
        _close();
        //_session_destroy();
        _location('恭喜你修改成功！', "photo.php");
    }else{
        _close();
        //_session_destroy();
        _alert_back('很遗憾，修改失败了~_~');
    }

}

if(isset($_GET['id'])){
    $_rows = _fetch_array("SELECT
                                *
                            FROM
                                tg_dir
                            WHERE
                                tg_id='{$_GET['id']}'
                            LIMIT
                                  1
        ");
    if(!!$_rows){
        $_rows = _html($_rows); 
        if($_rows['tg_type'] == 1){
            $_rows['isShow'] = 'style="display:block"';
            $_rows['tg_type_html'] = '<input type="radio" name="type" value="0" /> 公开<input type="radio" name="type" value="1" checked="checked" /> 私密';
        }else{
            $_rows['isShow'] = 'style="display:none"';
            $_rows['tg_type_html'] = '<input type="radio" name="type" value="0" checked="checked" /> 公开<input type="radio" name="type" value="1" /> 私密';
        }
    }else{
        _alert_back("找不到這個相册！");
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
<script type="text/javascript" src="js/photo_add_dir.js"></script>
</head>
<body>
<?php 
	require ROOT_PATH.'includes/header.inc.php';
?>

<div id="photo_add_dir">
	<h2>修改相册目录</h2>
	<form action="?action=modify" method="post">
	<dl>
	   <dd>相册名称：<input type="text" name="name" class="text" value="<?php echo $_rows['tg_name']?>" /></dd>
	   <dd>相册类型： <?php echo $_rows['tg_type_html']?></dd>
	   <dd id="pass" <?php echo $_rows['isShow']?>>相册密码：<input type="password" name="password" class="text" /></dd>
	   <dd>相册封面：<input type="text" name="face" class="text" value="<?php echo $_rows['tg_face']?>"/></dd>
	   <dd>相册描述：<textarea name="content"><?php echo $_rows['tg_content']?></textarea></dd>
	   <dd><input type="submit" value="修改目录" class="submit" /></dd>
	</dl>
	<input type="hidden" name="id" value="<?php echo $_rows['tg_id']?>" />
	</form>
</div>
<?php 
	require ROOT_PATH.'includes/footer.inc.php';
?>
</body>
</html>

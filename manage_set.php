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
define('SCRIPT','manage_set');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';

_manage_login();

//修改系統表
if($_GET['action'] == 'set'){
    if(!!$_rows =_fetch_array("SELECT 
                                    tg_uniqid 
                                FROM 
                                    tg_user 
                                WHERE 
                                    tg_username='{$_COOKIE['username']}'
                             ")){
        $_clean = array();
        $_clean['webname'] = $_POST['webname'];
        $_clean['article'] = $_POST['article'];
        $_clean['blog'] = $_POST['blog'];
        $_clean['photo'] = $_POST['photo'];
        $_clean['skin'] = $_POST['skin'];
        $_clean['string'] = $_POST['string'];
        $_clean['post'] = $_POST['post'];
        $_clean['re'] = $_POST['re'];
        $_clean['code'] = $_POST['code'];
        $_clean['register'] = $_POST['register'];
        
        $_clean = _mysql_string($_clean);
        
        //寫入數據
        _query("UPDATE
                    tg_system
                SET
                    tg_webname='{$_clean['webname']}',
                    tg_article='{$_clean['article']}',
                    tg_blog='{$_clean['blog']}',
                    tg_photo='{$_clean['photo']}',
                    tg_skin='{$_clean['skin']}',
                    tg_string='{$_clean['string']}',
                    tg_post='{$_clean['post']}',
                    tg_re='{$_clean['re']}',
                    tg_code='{$_clean['code']}',
                    tg_register='{$_clean['register']}'
                WHERE
                    tg_id=1
                LIMIT
                    1
               ");
        if(_affected_rows() == 1){
            _close();
            _location("恭喜你修改成功！", "manage_set.php");
        }else{
            _close();
            _location("很遗憾，没有任何数据被修改", "manage_set.php");
        }
    }else{
        _alert_back("異常！");
    }
}

if(!!$_rows =_fetch_array("SELECT
                                * 
                            FROM 
                                tg_system 
                            WHERE 
                                tg_id=1
                          ")){
    $_rows = _html($_rows);
    
    //皮肤
    if ($_rows['tg_skin'] == 1) {
        $_rows['tg_skin_html'] = '<select name="skin"><option value="1" selected="selected">一号皮肤</option><option value="2">二号皮肤</option><option value="3">三号皮肤</option></select>';
    } elseif ($_rows['tg_skin'] == 2) {
        $_rows['tg_skin_html'] = '<select name="skin"><option value="1">一号皮肤</option><option value="2" selected="selected">二号皮肤</option><option value="3">三号皮肤</option></select>';
    } elseif ($_rows['tg_skin'] == 3) {
        $_rows['tg_skin_html'] = '<select name="skin"><option value="1">一号皮肤</option><option value="2">二号皮肤</option><option value="3" selected="selected">三号皮肤</option></select>';
    }
    
    //发帖时间限制
    if ($_rows['tg_post'] == 30) {
        $_rows['tg_post_html'] = '<input type="radio" name="post" value="30" checked="checked" /> 30秒 <input type="radio" name="post" value="60" /> 1分钟 <input type="radio" name="post" value="180" /> 3分钟';
    } elseif ($_rows['tg_post'] == 60) {
        $_rows['tg_post_html'] = '<input type="radio" name="post" value="30" /> 30秒 <input type="radio" name="post" value="60" checked="checked" /> 1分钟 <input type="radio" name="post" value="180" /> 3分钟';
    } elseif ($_rows['tg_post'] == 180) {
        $_rows['tg_post_html'] = '<input type="radio" name="post" value="30" /> 30秒 <input type="radio" name="post" value="60" /> 1分钟 <input type="radio" name="post" value="180" checked="checked" /> 3分钟';
    }
    
    //回帖时间限制
    if ($_rows['tg_re'] == 15) {
        $_rows['tg_re_html'] = '<input type="radio" name="re" value="15" checked="checked" /> 15秒 <input type="radio" name="re" value="30" /> 30秒 <input type="radio" name="re" value="45" /> 45秒';
    } elseif ($_rows['tg_re'] == 30) {
        $_rows['tg_re_html'] = '<input type="radio" name="re" value="15" /> 15秒 <input type="radio" name="re" value="30" checked="checked" /> 30秒 <input type="radio" name="re" value="45" /> 45秒';
    } elseif ($_rows['tg_re'] == 45) {
        $_rows['tg_re_html'] = '<input type="radio" name="re" value="15" /> 15秒 <input type="radio" name="re" value="30" /> 30秒 <input type="radio" name="re" value="45" checked="checked" /> 45秒';
    }
    
    //是否开启验证码
    if($_rows['tg_code']){
        $_rows['tg_code_html'] = '<input type="radio" name="code" value="1" checked="checked"> 启用 <input type="radio" name="code" value="0"> 不启用'; 
    }else{
        $_rows['tg_code_html'] = '<input type="radio" name="code" value="1"> 启用 <input type="radio" name="code" value="0" checked="checked"> 不启用';
    }
    
    //是否开启会员注册
    if($_rows['tg_register']){
        $_rows['tg_register_html'] = '<input type="radio" name="register" value="1" checked="checked"> 启用 <input type="radio" name="register" value="0"> 不启用';
    }else{
        $_rows['tg_register_html'] = '<input type="radio" name="register" value="1"> 启用 <input type="radio" name="register" value="0" checked="checked"> 不启用';
    }
}else{
    _alert_back("系统表读取错误，请联系管理员检查！");
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
    	require ROOT_PATH.'includes/manage.inc.php';
    ?>
	<div id="member_main">
	   <h2>后台管理中心</h2>
	   <form method="post" action="?action=set">
	   <dl>
	       <dd>·网站名称：<input type="text" name="webname" class="text" value="<?php echo $_rows['tg_webname']?>" /></dd>
	       <dd>·文章每页列表数：<input type="text" name="article" class="text" value="<?php echo $_rows['tg_article']?>" /></dd>
	       <dd>·博客每页列表数：<input type="text" name="blog" class="text" value="<?php echo $_rows['tg_blog']?>" /></dd>
	       <dd>·相册每页列表数：<input type="text" name="photo" class="text" value="<?php echo $_rows['tg_photo']?>" /></dd>
	       <dd>·站点默认皮肤：<?php echo $_rows['tg_skin_html']?></dd>
	       <dd>·非法字符过滤：<input type="text" name="string" class="text" value="<?php echo $_rows['tg_string']?>" /> (*请用|隔开)</dd>
	       <dd>·发帖间隔时间限制：<?php echo $_rows['tg_post_html']?></dd>
	       <dd>·回帖间隔时间限制：<?php echo $_rows['tg_re_html']?></dd>
	       <dd>·是否启用验证码：<?php echo $_rows['tg_code_html']?></dd>
	       <dd>·是否启用会员注册：<?php echo $_rows['tg_register_html']?></dd>
	       <dd><input type="submit" value="修改系统设置" class="submit" /></dd>
	   </dl>
	   </form>
	</div>
</div>
<?php 
	require ROOT_PATH.'includes/footer.inc.php';
?>
</body>
</html>

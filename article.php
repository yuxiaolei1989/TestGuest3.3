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
define('SCRIPT','article');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';

$_result = _query("SELECT tg_id,tg_username,tg_sex,tg_face,tg_email,tg_url FROM tg_user WHERE tg_username='{$_COOKIE['username']}' LIMIT 1");

$_html = _html(_fetch_array_list($_result));


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>多用户留言系统--帖子详情</title>
<?php 
	require ROOT_PATH.'includes/title.inc.php';
?>
<script type="text/javascript" src="js/blog.js"></script>
</head>
<body>
<?php 
	require ROOT_PATH.'includes/header.inc.php';
?>

<div id="article">
	<h2>帖子详情</h2>
	<div id="subject">
    	<dl>
    	   <dd class="user"><?php echo $_html['tg_username']?>(<?php echo $_html['tg_sex']?>)</dd>
    	   <dt><img src="<?php echo $_html['tg_face']; ?>" alt="<?php echo $_html['tg_username']?>" /></dt>
    	   <dd class="message"><a href='javascript:;' name="message" title="<?php echo $_html['tg_id']?>">发消息</a></dd>
    	   <dd class="friend"><a href='javascript:;' name="friend" title="<?php echo $_html['tg_id']?>">加为好友</a></dd>
    	   <dd class="guest">写留言</dd>
    	   <dd class="flower"><a href='javascript:;' name="flower" title="<?php echo $_html['tg_id']?>">给他送花</a></dd>
    	   <dd class="email">郵件：<a href="mailto:<?php echo $_html['tg_email']?>"><?php echo $_html['tg_email']?></a></dd>
    	   <dd class="url">網址：<a href="<?php echo $_html['tg_url']?>" target="_blank"><?php echo $_html['tg_url']?></a></dd>
    	</dl>
        <div id="content">
            <div class="user">
                <span>1#</span><?php echo $_html['tg_username']?> | 
            </div>
            <h3>主题：天上掉下馅饼《游戏人生》变装拿大奖 <img src="images/icon10.gif" alt="" /></h3>
            <div class="detail">
            天上又掉馅饼了！《游戏人生》天降仙女，天降忍者，天降甲壳虫轿车……各种神仙异侠附体大行动！漂亮护士MM，超级忍者，魔幻厨师，功夫小子，埃及僵尸，变形金刚……想变什么就变什么，当侠客，还是当神仙？还是当一个科学异侠？你自己灵活选择啦！众多奇幻华丽的变身道具让你享受史上超震撼的无厘头刺激！同时各种神秘的惊喜大奖等你来拿喔！
            </div>
        </div>
	</div>
	<p class="line"></p>
</div>
<?php 
	require ROOT_PATH.'includes/footer.inc.php';
?>
</body>
</html>

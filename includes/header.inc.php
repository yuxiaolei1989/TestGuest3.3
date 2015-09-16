<?php
/**
* TestGuest Version1.0
* ================================================
* Copy 2010-2012 yc60
* Web: http://www.yc60.com
* ================================================
* Author: Lee
* Date: 2010-8-10
*/
//防止恶意调用
if (!defined('IN_TG')) {
	exit('Access Defined!');
}
global $_message_html;
?>
<div id="header">
	<h1><a href="index.php">瓢城Web俱乐部多用户留言系统</a></h1>
	<ul>
		<li><a href="index.php">首页</a></li>
		<?php 
		  if(isset($_COOKIE['username'])){
		      echo '<li><a href="member.php">'.$_COOKIE['username'].'·个人中心</a>&nbsp;'.$_message_html.'</li>';
		  }else{
		      echo '<li><a href="register.php">注册</a></li>&nbsp;
		            <li><a href="login.php">登录</a></li>';
		  }
		?>
		<li><a href="blog.php">博友</a></li>
		<li><a href="photo.php">相册</a></li>
		<li>风格</li>
		<?php 
    		if(isset($_COOKIE['username']) && isset($_SESSION['admin'])){
    		    echo '<li><a href="manage.php" class="manage">管理</a></li> ';
    		}
    		
    		if(isset($_COOKIE['username'])){
    		    echo '<li><a href="logout.php">退出</a></li>';
    		}
		?>
		
	</ul>
</div>
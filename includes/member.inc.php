<?php
/**
* TestGuest Version1.0
* Author: yulei@addcn.com
* Date: 2015-8-23
*/
//防止恶意调用
if (!defined('IN_TG')) {
	exit('Access Defined!');
}
?>

	<div id="member_sidebar">
	   <h2>中心导航</h2>
	   <dl>
	       <dt>账号管理</dt>
	       <dd><a href="member.php">个人信息</a></dd>
	       <dd><a href="member_modify.php">修改资料</a></dd>
	   </dl>
	   <dl>
	       <dt>其他管理</dt>
	       <dd><a href="">短信查询</a></dd>
	       <dd><a href="">好友设置</a></dd>
	       <dd><a href="">查询花朵</a></dd>
	       <dd><a href="">个人相册</a></dd>
	   </dl>
	</div>
	
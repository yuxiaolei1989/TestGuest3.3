<?php
/**
* TestGuest Version1.0
* ================================================
* Author: yulei@addcn.com   
* Date: 2015-08-19
*/
//防止恶意调用
if (!defined('IN_TG')) {
	exit('Access Defined!');
}

header('Content-Type:text/html;charset=utf-8;');
//转换硬路径常量
define('ROOT_PATH',substr(dirname(__FILE__),0,-8));

define('GPC',get_magic_quotes_gpc());

//拒绝PHP低版本
if (PHP_VERSION < '4.1.0') {
	exit('Version is to Low!');
}

//引入函数库
require ROOT_PATH.'includes/global.func.php';
require ROOT_PATH.'includes/mysql.func.php';

//执行耗时
define('START_TIME',_runtime());
//$GLOBALS['start_time'] = _runtime();

//数据库链接
define("DB_HOST",'localhost');
define('DB_USER','root');
define("DB_PWD",'yy0118*');
define('DB_NAME','testguest');

_connect();
_select_db();
_set_names();
?>
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

/**
 * _check_username 检测用户名并过滤
 * @access public
 * @param string $_string
 * @param number $_min_num 最小位数
 * @param number $_max_num 最大位数
 * @return string
 */

function _check_username($_string,$_min_num=2,$_max_num=20){
    $_string =trim($_string);

    $_len  = mb_strlen($_string,'utf-8');
    if($_len < $_min_num || $_len > $_max_num ){
        _alert_back("用户名长度小于".$_min_num."或者不能大于".$_max_num."位！");
    }

    $_char_pattern = '/[\'\"<>\ \　]/';
    if(preg_match($_char_pattern,$_string)){
        _alert_back("用户名不得包含敏感字符");
    }

    return _mysql_string($_string);
}



/**
 * _check_password 验证密码
 * @param string $_first_pass
 * @param int $_min_num
 * @return string 返回一个加密后的密码
 */
function _check_password($_string,$_min_num){
    if(strlen($_string) < $_min_num){
        _alert_back("密码不得小于".$_min_num."位！");
    }

    return _mysql_string(sha1($_string));
}

/**
 * _setcookies()生成登录cookie
 * @param unknown $_string
 * @return string
 */
function _check_time($_string){
    $_time = array(0,1,2,3);
    if(!in_array($_string,$_time)){
        _alert_back("保留方式出错！");
    }
    return _mysql_string($_string);
}

function _setcookies($_username,$_uniqid,$_time){
    
    switch ($_time){
        case "0":
            $_cookietime = null;
            break;
        case "1":
            $_cookietime = time()+86400;
            break;
        case "2":
            $_cookietime = time()+604800;
            break;
        case "3":
            $_cookietime = time()+2592000;
            break;
    }
    setcookie("username",$_username,$_cookietime);
    setcookie("uniqid",$_uniqid,$_cookietime);
}
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

if(!function_exists('_alert_back')){
    exit("_alert_back函数不存在");
}

if(!function_exists('_mysql_string')){
    exit("_mysql_string()函数不存在");
}

function _check_uniqid($_first_uniqid,$_end_uniqid){
    if((strlen($_first_uniqid) != 40 || ($_first_uniqid != $_end_uniqid))){
        _alert_back("唯一标识符异常");
    }
    return _mysql_string($_first_uniqid);
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
    
    $_char_pattern = '/[\'\"<>\ ]/';
    if(preg_match($_char_pattern,$_string)){
        _alert_back("用户名不得包含敏感字符");
    }
    
    global $_system;
    $_mg = explode("|",$_system['tg_string']); 
    
    foreach ($_mg as $value){
        $_mg_string .=$value ."、";
    }
    if(in_array($_string, $_mg)){
        _alert_back($_mg_string."以上敏感用户名不得注册！");
    }
    
    
    return _mysql_string($_string);
}

/**
 * _check_password 验证密码
 * @param string $_first_pass
 * @param string $_end_pass
 * @param int $_min_num
 * @return string 返回一个加密后的密码
 */
function _check_password($_first_pass,$_end_pass,$_min_num){
    if(strlen($_first_pass) < $_min_num){
        _alert_back("密码不得小于".$_min_num."位！");
    }
    
    if($_first_pass != $_end_pass){
        _alert_back("密码和确认密码不一致！");
    }
    
    return _mysql_string(sha1($_first_pass));
}

/**
 * 
 * @param unknown $_string
 * @param unknown $_min_num
 * @return unknown
 */
function _check_modify_password($_string,$_min_num){
    if(!empty($_string)){
        if(strlen($_string) < $_min_num){
            _alert_back("密码不得小于".$_min_num."位！");
        }  
    }else{
        return null;
    }
    return _mysql_string(sha1($_string));
}

/**
 * _check_question 验证密码提示
 * @access public
 * @param string $_string
 * @param int $_min_num
 * @param int $_max_num
 * @return string
 */
function _check_qustion($_string,$_min_num,$_max_num){
    $_string = trim($_string);
    $_len = mb_strlen($_string,'utf-8');
    if($_len < $_min_num || $_len > $_max_num){
        _alert_back("密码提示不得小于".$_min_num."位或者大于".$_max_num."位！");
    }
    return _mysql_string($_string);
}

/**
 * _check_answer 验证密码回答
 * @access public
 * @param string $_string
 * @param string $_answer
 * @param int $_min_num
 * @param int $_max_num
 * @return string
 */
function _check_answer($_string,$_answer,$_min_num,$_max_num){
    $_answer = trim($_answer);
    $_len = mb_strlen($_answer,'utf-8');
    if($_len < $_min_num || $_len > $_max_num){
        _alert_back("密码回答不得小于".$_min_num."位或者大于".$_max_num."位！");
    }
    if($_string == $_answer){
        _alert_back("密码提示与回答不得一致！");
    }
    return _mysql_string(sha1($_answer));
}

/**
 * _check_sex 性别
 * @access public
 * @param string $_string
 * @return string
 */
function _check_sex($_string){
    return _mysql_string($_string);
}

/**
 * _check_face
 * @access public
 * @param string $_string
 * @return string
 */
function _check_face($_string){
    return _mysql_string($_string);
}

/**
 * _check_email() 检测邮箱是否合法
 * @access public
 * @param unknown $_string
 * @return unknown
 */
function _check_email($_string,$_min_num,$_max_num){
    
    if(!preg_match('/^[\w-\.]+@[\w-\.]+(\.\w+)+$/',$_string)){
        _alert_back("邮件格式不正确！");
    }
    $len = strlen($_string);
    if($len < $_min_num || $len > $_max_num){
        _alert_back("邮件长度不合法");
    }
    return _mysql_string($_string);
}

/**
 * _check_qq QQ号码验证
 * @param int $_string
 * @return int QQ号码
 */
function _check_qq($_string){
    if(empty($_string)){
        return null;
    } else {
        if(!preg_match('/^[1-9]{1}[0-9]{4,9}$/',$_string)){
            _alert_back("QQ号码不正确！");
        }
    }
    return _mysql_string($_string);
}

/**
 * _check_url 网址验证
 * @access public
 * @param string $_string
 * @return string $_string
 */
function _check_url($_string,$_max_num){
    if(empty($_string) || ($_string == "http://")){
        return null;
    }else{
        if(!preg_match('/^https?:\/\/(\w+\.)?[\w\-\.]+(\.\w+)+$/',$_string)){
            _alert_back("网址不正确！");
        }
        if(strlen($_string) > $_max_num){
            _alert_back("网址太长，本站不收录！");
        }
    }
    return _mysql_string($_string);
}

function _check_content($_string,$_min_num,$_max_num){
    $_len  = mb_strlen($_string,'utf-8');
    if($_len < $_min_num || $_len > $_max_num ){
        _alert_back("短信内容不得小于".$_min_num."位或者大于".$_max_num."位！");
    }
    return $_string;
}

function _check_post_title($_string,$_min,$_max){
    if(mb_strlen($_string,'utf-8') < $_min || mb_strlen($_string,'utf-8') > $_max){
        _alert_back("帖子标题内容不得小于".$_min."位或者大于".$_max."位！");
    }
    return $_string;
}

function _check_post_content($_string,$_num){
    if(mb_strlen($_string,'utf-8') < $_num){
        _alert_back("帖子标题内容不得小于".$_num."位！");
    }
    return $_string;
}

function _check_autograph($_string,$_num){
    if(mb_strlen($_string,'utf-8') > $_num){
        _alert_back("帖子标题内容不得大于".$_num."位！");
    }
    return $_string;
}

/**
 * _check_dir_name 检测相册名并过滤
 * @access public
 * @param string $_string
 * @param number $_min_num 最小位数
 * @param number $_max_num 最大位数
 * @return string
 */

function _check_dir_name($_string,$_min_num=2,$_max_num=20){
    $_string =trim($_string);

    $_len  = mb_strlen($_string,'utf-8');
    if($_len < $_min_num || $_len > $_max_num ){
        _alert_back("名称长度小于".$_min_num."或者不能大于".$_max_num."位！");
    }
    return $_string;
}

/**
 * _check_dir_password 检测相册密码
 * @param unknown $_first_pass
 * @param unknown $_min_num
 * @return string
 */
function _check_dir_password($_first_pass,$_min_num){
    if(strlen($_first_pass) < $_min_num){
        _alert_back("密码不得小于".$_min_num."位！");
    }

    return _mysql_string(sha1($_first_pass));
}


function _check_photo_url($_string){
    if(empty($_string)){
        _alert_back("地址不能为空");
    }
    
    return $_string;
}

?>
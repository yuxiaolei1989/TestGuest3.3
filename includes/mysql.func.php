<?php


//防止恶意调用
if (!defined('IN_TG')) {
    exit('Access Defined!');
}

/**
 * _connect() 连接MySQL数据库
 */
function _connect(){
    global $_conn;
    if(!$_conn = @mysql_connect(DB_HOST,DB_USER,DB_PWD)){
        exit("数据库连接失败！");
    }
}

/**
 * _select_db() 选择数据库
 */
function _select_db(){
    if(!mysql_select_db(DB_NAME)){
        exit("指定的数据库找不到！");
    }
}

/**
 * _set_names() 设置字符集
 */
function _set_names(){
    if(!mysql_query('SET NAMES UTF8')){
        exit("字符集错误！");
    }
}

/**
 * 
 * @param unknown $_sql
 * @return resource
 */
function _query($_sql){
    if(!$result = mysql_query($_sql)){
        exit("SQL执行失败！");
    }
    return $result;
}

/**
 * _fetch_array()只能获取一条数据组
 * @param unknown $_sql
 * @return multitype:
 */
function _fetch_array($_sql){
    return mysql_fetch_array(_query($_sql),MYSQL_ASSOC);
}

/**
 * _fetch_array_list 可以返回指定数据集的所有数据
 * @param unknown $_result
 * @return multitype:
 */
function _fetch_array_list($_result){
    return mysql_fetch_array($_result,MYSQL_ASSOC);
}

/**
 * 获取结果集的条数
 * @param unknown $_result
 * @return number
 */
function _num_rows($_result){
    return mysql_num_rows($_result);
}
/**
 * _affected_rows()表示影响到的数据条数
 * @return number
 */
function _affected_rows(){
    return mysql_affected_rows();
}

/**
 * 
 * @param unknown $_sql
 * @param unknown $_info
 */
function _is_repeat($_sql,$_info){
    if(_fetch_array($_sql)){
        exit($_info);
    }
}

/**
 * 
 * @param unknown $_result
 */
function _free_result($_result){
    mysql_free_result($_result);
}

/**
 * 
 */
function _close(){
    if(!mysql_close()){
        exit("数据库关闭异常！");
    }
}

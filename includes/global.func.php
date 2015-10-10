<?php
/**
* TestGuest Version1.0
* ================================================
* Copy 2010-2012 yc60
* Web: http://www.yc60.com
* ================================================
* Author: Lee
* Date: 2010-8-11
*/

function _manage_login(){
    if((!isset($_COOKIE['username'])) || (!isset($_SESSION['admin']))){
        _location("你不是管理员！", "login.php");
    }
}

/**
 * 限时发帖
 * @param unknown $_now_time
 * @param unknown $_pre_time
 * @param unknown $_second
 */
function _timed($_now_time,$_pre_time,$_second){
    if(($_now_time - $_pre_time) < $_second){
        _alert_back("请阁下休息一会再发帖");
    }
}


/**
 *_runtime()是用来获取执行耗时
 * @access public  表示函数对外公开
 * @return float 表示返回出来的是一个浮点型数字
 */
function _runtime() {
	$_mtime = explode(' ',microtime());
	return $_mtime[1] + $_mtime[0];
}

/**
 * @access public
 * @param $_info
 * @return void 弹窗
 */

function _alert_back($_info){
    echo "<script>alert('$_info');history.back();</script>";
    exit;
}

function _alert_close($_info){
    echo "<script>alert('$_info');window.close();</script>";
    exit;
}


/**
 * 
 * @param unknown $_info
 * @param unknown $_url
 */
function _location($_info,$_url){
    if(!empty($_info)){
        echo "<script>alert('$_info');location.href='$_url';</script>";
        exit();
    }else{
        header("Location:".$_url);
    }
}

/**
 * 限制登录时显示
 */
function _login_state(){
   if(isset($_COOKIE['username'])){
       _alert_back("登录状态无法进行此操作！");
   }
}

/**
 * 判断唯一标识符是否异常
 * @param unknown $_mysql_uniqid
 * @param unknown $_cookie_uniqid
 */
function _uniqid($_mysql_uniqid,$_cookie_uniqid){
    if($_mysql_uniqid != $_cookie_uniqid){
        _alert_back("唯一标识符异常");
    }
}


function _thumb($_filename,$_percent){
    header('Content-type:image/png');
    
    $_n = explode('.',$_filename);
    
    list($_width,$_height) = getimagesize($_filename);
    
    $_new_width = $_width * 0.2;
    $_new_height = $_height * 0.2;
    
    $_new_image = imagecreatetruecolor($_new_width, $_new_height);
    
    switch ($_n[1]){
        case 'jpg' : $_image = imagecreatefromjpeg($_filename);
        break;
        case 'png' : $_image = imagecreatefrompng($_filename);
        break;
        case 'gif' : $_image = imagecreatefromjpeg($_filename);
        break;
    }
    
    imagecopyresampled($_new_image,$_image,0,0,0,0,$_new_width,$_new_height,$_width,$_height);
    
    imagepng($_new_image);
    
    imagedestroy($_new_image);
    imagedestroy($_image);
}

function _get_xml($_xmlfile){
    $_xmlfile = "new.xml";
    $_html = array();
    if(file_exists($_xmlfile)){
        $_xml = file_get_contents("new.xml");
        preg_match_all('/<vip>(.*)<\/vip>/s',$_xml,$_dom);
        foreach ($_dom[1] as $_value){
            preg_match_all('/<id>(.*)<\/id>/s',$_value,$_html['id']);
            preg_match_all('/<username>(.*)<\/username>/s',$_value,$_html['username']);
            preg_match_all('/<sex>(.*)<\/sex>/s',$_value,$_html['sex']);
            preg_match_all('/<face>(.*)<\/face>/s',$_value,$_html['face']);
            preg_match_all('/<email>(.*)<\/email>/s',$_value,$_html['email']);
            preg_match_all('/<url>(.*)<\/url>/s',$_value,$_html['url']);
        }
        foreach($_html as $_key => $_item){
            foreach ($_item[1] as $_val){
                $_html[$_key] = $_val;
            }
    
        }
    }else{
        echo "文件不存在！";
    }
    
    return $_html;
}

/**
 * 設置xml - 首頁-新進會員使用
 * @param unknown $_xmlfile
 * @param unknown $_clean
 */
function _set_xml($_xmlfile,$_clean){
    $_fp = @fopen('new.xml','w');
    if(!$_fp){
        exit('系統錯誤，文件不存在！');
    }
    
    flock($_fp,LOCK_EX);
    
    $_string = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\r\n";
    fwrite($_fp, $_string,strlen($_string));
    $_string = "<vip>\r\n";
    fwrite($_fp, $_string,strlen($_string));
    $_string = "\t<id>{$_clean['id']}</id>\r\n";
    fwrite($_fp, $_string,strlen($_string));
    $_string = "\t<username>{$_clean['username']}</username>\r\n";
    fwrite($_fp, $_string,strlen($_string));
    $_string = "\t<sex>{$_clean['sex']}</sex>\r\n";
    fwrite($_fp, $_string,strlen($_string));
    $_string = "\t<face>{$_clean['face']}</face>\r\n";
    fwrite($_fp, $_string,strlen($_string));
    $_string = "\t<email>{$_clean['email']}</email>\r\n";
    fwrite($_fp, $_string,strlen($_string));
    $_string = "\t<url>{$_clean['url']}</url>\r\n";
    fwrite($_fp, $_string,strlen($_string));
    $_string = "</vip>\r\n";
    fwrite($_fp, $_string,strlen($_string));
    
    flock($_fp,LOCK_UN);
    fclose($_fp);
    
}

/**
 * 截取字符串
 * @param unknown $_string
 * @return string
 */
function _title($_string,$_num){
    if(mb_strlen($_string,'utf-8') > $_num){
        $_string = mb_substr($_string,1,$_num,'utf-8')."...";
    }
    return $_string;
}

/**
 * 转移特殊字符 - 运用了递归方法
 * @param string || Array $_string
 * @return string
 */
function _html($_string){
    if(is_array($_string)){
        foreach($_string as $_key => $_value){
            $_string[$_key] = _html($_value);
        }
    }else{
        $_string = htmlspecialchars($_string);
    }
    return $_string;
}

/**
 * 
 * @param unknown $_sql
 * @param unknown $_size
 */
function _page($_sql,$_size){
    global $_pagesize,$_pagenum,$_pageabsolute,$_num,$_page;
    //容错处理
    $_page = $_GET['page'] > 0 ? intval($_GET['page']) : 1;
    
    $_num = _num_rows(_query($_sql));
    $_pagesize = $_size;
    $_pageabsolute = ceil($_num/$_pagesize);
    
    $_page = $_page > $_pageabsolute ? $_pageabsolute : $_page;
    
    $_pagenum = $_page != "" ? ($_page-1)*$_pagesize : 0;
}

/**
 * 分页函数
 * @param int $_type
 */
function _paging($_type){
    echo '<link rel="stylesheet" type="text/css" href="styles/1/page.css">';
    global $_page,$_pageabsolute,$_num,$_id;
    if($_type == 1){
       echo '<div id="page_num">
            <ul>';
        	       for($i=0;$i<$_pageabsolute;$i++){
        	           if($_page == $i+1){
        	              echo '<li><a href="'.SCRIPT.'.php?'.$_id.'page='.($i+1).'" class="selected">' .($i+1). '</a></li>';
        	           }else{
        	              echo '<li><a href="'.SCRIPT.'.php?'.$_id.'page='.($i+1).'">' .($i+1).' </a></li>';
        	           }
        	       
        	        }
        echo	   '</ul>
        	</div>';
    }else{
        echo '<div id="page_text">
                <ul>
                    <li>'.$_page.'/'.$_pageabsolute.'页 |</li>
        	        <li>共有<strong>'.$_num.'</strong>条数据 |</li>';
        	       
        	           if($_page == 1){
        	               echo "<li>首页 |</li>
        	                     <li>上一页 |</li>";
        	           }else{
        	               echo "<li><a href='".SCRIPT.".php'>首页</a> |</li>
        	                     <li><a href='".SCRIPT.".php?".$_id."page=".($_page -1)."'>上一页</a> |</li>";
        	           }
        	           
        	           if($_page == $_pageabsolute){
        	               echo "<li>下一页 |</li>
        	                     <li>尾页 |</li>";
        	           }else{
        	               echo "<li><a href='".SCRIPT.".php?".$_id."page=".($_page+1)."'>下一页</a> |</li>
        	                     <li><a href='".SCRIPT.".php?".$_id."page=".$_pageabsolute."'>尾页</a> |</li>";
        	           }
        	      
        	       
        	       
    	echo '</ul>
    	</div>';
    }
    
}


/**
 * 删除session
 */
function _session_destroy(){
    if(session_start()){
        session_destroy();
    }
    
}

/**
 * 删除cookie
 */
function _unsetcookies(){
    setcookie('username','',time()-1);
    setcookie('uniqid','',time()-1);
    _session_destroy();
    _location("退出成功！", "index.php");
}


function _sha1_uniqid(){
    return _mysql_string(sha1(uniqid(rand(),true)));
}

/**
 * @access public
 * @param string $_string
 * @return string $_string
 */
function _mysql_string($_string){
    if(!GPC){
        if(is_array($_string)){
            foreach($_string as $_key => $_value){
                $_string[$_key] = _mysql_string($_value);
            }
        }else{
            return mysql_real_escape_string($_string);
        }   
    }
    return $_string;
    
}

/**
 * _check_code 验证码验证
 * @param string $_first_code
 * @param string $_end_code
 */
function _check_code($_first_code,$_end_code){
    if($_first_code != $_end_code){
        _alert_back("验证码不正确！");
    }
}

/**
 * _code 是验证码函数
 * @access public
 * @param int $_width 表示验证码的宽度
 * @param int $_height 表示验证码的高度
 * @param int $_rnd_num 表示验证码的个数
 * @param bool $_flag  表示是否有边框
 */
 
function _code($_width=75,$_height=25,$_rnd_num=4,$_flag=false){

    for($i=0;$i<$_rnd_num;$i++){
        $_nmsg .= dechex(mt_rand(0,15));
    }
    
    $_SESSION['code'] = $_nmsg;
    
    $_img = imagecreatetruecolor($_width, $_height);
    
    $_white = imagecolorallocate($_img, 255, 255, 255);
    
    imagefill($_img,0,0,$_white);
    
    if($_flag){
        $_black = imagecolorallocate($_img,0,0,0);
        imagerectangle($_img,0,0,$_width-1,$_height-1,$_black);
    }
    for($i=0;$i<6;$i++){
        $_rnd_color = imagecolorallocate($_img,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));
        imageline($_img,mt_rand(0,$_width),mt_rand(0,$_height),mt_rand(0,$_width),mt_rand(0,$_height),$_rnd_color);
    }
    
    for($i=0;$i<100;$i++){
        $_rnd_color = imagecolorallocate($_img,mt_rand(200,255),mt_rand(200,255),mt_rand(200,255));
        imagestring($_img,1,mt_rand(1,$_width),mt_rand(1,$_height),"*",$_rnd_color);
    }
    
    for($i=0;$i<$_rnd_num;$i++){
        imagestring($_img,5,$i*$_width/$_rnd_num+mt_rand(1,10),mt_rand(1,$_height/2),$_SESSION['code'][$i],imagecolorallocate($_img,mt_rand(0,100),mt_rand(0,150),mt_rand(0,200)));
    }
    
    header("Content-Type:image/png");
    imagepng($_img);
    
    imagedestroy($_img);
}


function _ubb($_string){
    $_string = nl2br($_string);
    $_string = preg_replace('/\[size=(.*)\](.*)\[\/size\]/U', '<span style="font-size:\1px;">\2</span>', $_string);
    $_string = preg_replace('/\[color=(.*)\](.*)\[\/color\]/U', '<span style="color:\1">\2</span>', $_string);
    $_string = preg_replace('/\[b\](.*)\[\/b\]/U', '<strong>\1</strong>', $_string);
    
    $_string = preg_replace('/\[i\](.*)\[\/i\]/U', '<i>\1</i>', $_string);
    
    $_string = preg_replace('/\[u\](.*)\[\/u\]/U', '<u>\1</u>', $_string);
    
    $_string = preg_replace('/\[s\](.*)\[\/s\]/U', '<s>\1</s>', $_string);
    
    $_string = preg_replace('/\[url\](.*)\[\/url\]/U', '<a href="\1" target="_blank">\1</a>', $_string);
    
    $_string = preg_replace('/\[img\](.*)\[\/img\]/U', '<img src="\1" />', $_string);
    
    $_string = preg_replace('/\[email\](.*)\[\/email\]/U', '<a href="mailto:\1" target="_blank">\1</a>', $_string);
    
    $_string = preg_replace('/\[flash\](.*)\[\/flash\]/U', '<embed src="\1" style="width:480px; height:400px;" />', $_string);
    return $_string;
}
?>
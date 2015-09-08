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

if($_GET['action'] == 'rearticle'){
    _check_code($_POST['code'],$_SESSION['code']);
    
    $_rows = _fetch_array("SELECT
        tg_uniqid
        FROM
        tg_user
        WHERE
        tg_username='{$_COOKIE['username']}'");
    
    _uniqid($_rows['tg_uniqid'], $_COOKIE['uniqid']);
    
    include ROOT_PATH.'includes/check.func.php';
    $_clean = array();
    $_clean['username'] = $_COOKIE['username'];
    $_clean['reid'] = $_POST['reid'];
    $_clean['type'] = $_POST['type'];
    $_clean['title'] = _check_post_title($_POST['title'],2,40);
    $_clean['content'] = _check_post_content($_POST['content'],10);
    $_clean = _mysql_string($_clean);
    
    _query(
        "INSERT INTO tg_article (
        tg_username,
        tg_reid,
        tg_title,
        tg_type,
        tg_content,
        tg_date
    ) VALUES (
        '{$_clean['username']}',
        '{$_clean['reid']}',
        '{$_clean['title']}',
        '{$_clean['type']}',
        '{$_clean['content']}',
        NOW()
    )");
    
    if(_affected_rows() == 1){
        _query("UPDATE
                    tg_article
                SET
                    tg_commendcount=tg_commendcount + 1
                WHERE
                    tg_id = '{$_clean['reid']}'");
        _close();
        _session_destroy();
        _location("恭喜你回复发布成功！", "article.php?id=".$_clean['reid']);
    }else{
        _close();
        _session_destroy();
        _alert_back("很遗憾，帖子回复发布失败了~_~");
    }
}

if(isset($_GET['id'])){
    $_rows = _fetch_array("SELECT 
                                tg_id,
                                tg_username,
                                tg_type,
                                tg_title,
                                tg_content,
                                tg_readcount,
                                tg_commendcount,
                                tg_date
                            FROM 
                                tg_article 
                            WHERE 
                                tg_id='{$_GET['id']}'
                            AND
                                tg_reid=0
                        ");
    if(!!$_rows){
        _query("UPDATE
            tg_article
            SET
            tg_readcount = tg_readcount+1
            WHERE
            tg_id='{$_GET['id']}'");
        $_rows = _html($_rows);
        $_rows['tg_content'] = _ubb($_rows['tg_content']);
        
        global $_id;
        $_id = 'id='.$_GET['id'].'&';
        
        $_result = _query("SELECT
            tg_id,
            tg_username,
            tg_sex,
            tg_face,
            tg_email,
            tg_url
            FROM
            tg_user
            WHERE
            tg_username='{$_rows['tg_username']}'
            LIMIT
            1
            ");
        $_html = _html(_fetch_array_list($_result));
        $_html['tg_username_floor'] =$_html['tg_username']. '(楼主)';
        
        global $_pagesize,$_pagenum,$_page;
        _page("SELECT tg_id FROM tg_article WHERE tg_reid=".$_GET['id'], 10);
        
        $_re = _query("SELECT
                            tg_username,
                            tg_type,
                            tg_title,
                            tg_content,
                            tg_date
                        FROM 
                            tg_article 
                        WHERE 
                            tg_reid='{$_GET['id']}' 
                        ORDER BY 
                            tg_date 
                        ASC 
                        LIMIT 
                            $_pagenum,$_pagesize
                    ");
        
    }else{
        _alert_back("不存在这个帖子！");
    }
    
}else{
    _alert_back("非法操作！");
}



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>多用户留言系统--帖子详情</title>
<?php 
	require ROOT_PATH.'includes/title.inc.php';
?>
<script type="text/javascript" src="js/code.js"></script>
<script type="text/javascript" src="js/article.js"></script>
</head>
<body>
<?php 
	require ROOT_PATH.'includes/header.inc.php';
?>

<div id="article">
	<h2>帖子详情</h2>
	<?php if($_page == 1 || $_page == 0){?>
	<div class="subject" id="subject">
    	<dl class="">
    	   <dd class="user"><?php echo $_html['tg_username_floor']?>(<?php echo $_html['tg_sex']?>)</dd>
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
                <span>1#</span><?php echo $_html['tg_username_floor']?> | <?php echo $_rows['tg_date']?>
            </div>
            <h3>主题：<?php echo $_rows['tg_title']?> <img src="images/icon<?php echo $_rows['tg_type']?>.gif" alt="" /></h3>
            <div class="detail">
                <?php echo $_rows['tg_content'];?>
            </div>
            <div class="read">
                                            浏览量（<?php echo $_rows['tg_readcount']?>） 评论数（<?php echo $_rows['tg_commendcount']?>）
            </div>
        </div>
	</div>
	<p class="line"></p>
	<?php }?>
	
	<?php 
	   $_i = 2+$_pagesize * ($_page - 1);
	   while(!!$_rows2 = _fetch_array_list($_re)){
	       $_rows2 = _html($_rows2);
	       $_rows2['tg_content'] = _ubb($_rows2['tg_content']);
	       
	       $_result2 = _query("SELECT
	           tg_id,
	           tg_username,
	           tg_sex,
	           tg_face,
	           tg_email,
	           tg_url
	           FROM
	           tg_user
	           WHERE
	           tg_username='{$_rows2['tg_username']}'
	           LIMIT
	           1
	           ");
	       $_html2 = _html(_fetch_array_list($_result2));
	       if($_i == 2){
	           if($_html2['tg_username'] != $_html['tg_username']){
	               $_html2['tg_username'] .= '(沙发)';
	           }else{
	               $_html2['tg_username'] .= '(楼主)';
	           } 
	       }
	       
	?>
	<div class="subject">
    	<dl>
    	   <dd class="user"><?php echo $_html2['tg_username']?>(<?php echo $_html2['tg_sex']?>)</dd>
    	   <dt><img src="<?php echo $_html2['tg_face']; ?>" alt="<?php echo $_html2['tg_username']?>" /></dt>
    	   <dd class="message"><a href='javascript:;' name="message" title="<?php echo $_html2['tg_id']?>">发消息</a></dd>
    	   <dd class="friend"><a href='javascript:;' name="friend" title="<?php echo $_html2['tg_id']?>">加为好友</a></dd>
    	   <dd class="guest">写留言</dd>
    	   <dd class="flower"><a href='javascript:;' name="flower" title="<?php echo $_html2['tg_id']?>">给他送花</a></dd>
    	   <dd class="email">郵件：<a href="mailto:<?php echo $_html2['tg_email']?>"><?php echo $_html2['tg_email']?></a></dd>
    	   <dd class="url">網址：<a href="<?php echo $_html2['tg_url']?>" target="_blank"><?php echo $_html2['tg_url']?></a></dd>
    	</dl>
        <div id="content">
            <div class="user">
                <span><?php echo $_i;?>#</span><?php echo $_html2['tg_username']?> | <?php echo $_rows2['tg_date']?>
            </div>
            <h3>主题：<?php echo $_rows2['tg_title']?> <img src="images/icon<?php echo $_rows2['tg_type']?>.gif" alt="" /></h3>
            <div class="detail">
                <?php echo $_rows2['tg_content'];?>
            </div>
        </div>
	</div>
	<p class="line"></p>
	<?php 
	   $_i++;
	   }
	   _free_result($_re);
	   _paging(1)
	?>
	<?php if($_COOKIE['username']){?>
	<form method="post" name="post" action="?action=rearticle">
		<dl class="reply">
			<dd>标　　题：<input type="text" name="title" class="text" value="RE:<?php echo $_rows['tg_title']?>" /> (*必填，至少两位)</dd>
			<dd id="q">贴　　图：<a href="javascript:;">Q图系列[1]</a> <a href="javascript:;">Q图系列[2]</a> <a href="javascript:;">Q图系列[3]</a></dd>
			<dd>
			     <?php include ROOT_PATH.'includes/ubb.inc.php';?>
			     <textarea name="content" rows="9"></textarea>
			</dd>
			<dd>验 证 码：<input type="text" name="code" class="text yzm"  /> <img src="code.php" alt="验证码" id="code" /> <input type="submit" class="submit" id="submit" value="发表帖子" /></dd>
		</dl>
		<input type="hidden" name="reid" value="<?php echo $_rows['tg_id'];?>" />
		<input type="hidden" name="type" value="<?php echo $_rows['tg_type'];?>" />
	</form>
	<?php }?>
</div>
<?php 
	require ROOT_PATH.'includes/footer.inc.php';
?>
</body>
</html>

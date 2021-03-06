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

if($_GET['action'] == 'nice' && isset($_GET['id']) && isset($_GET['on'])){
    $_rows = _fetch_array("SELECT
        tg_uniqid,
        tg_article_time
        FROM
        tg_user
        WHERE
        tg_username='{$_COOKIE['username']}'");
    if(!!$_rows){
        _uniqid($_rows['tg_uniqid'], $_COOKIE['uniqid']);
        
        _query("UPDATE
                    tg_article
                SET
                    tg_nice='{$_GET['on']}'
                WHERE
                    tg_id='{$_GET['id']}'
                LIMIT
                     1
              ");
        if(_affected_rows() == 1){
            if($_GET['on']){
                $_str = "设置";
            }else{
                $_str = "取消";
            }
            _close();
            //_session_destroy();
            _location("恭喜你".$_str."精华帖成功！", "article.php?id=".$_GET['id']);
        }else{
            _close();
            //_session_destroy();
            _alert_back("很遗憾，".$_str."精华帖失败了~_~");
        }
        
    }else{
        _alert_back("非法登录！");
    }
    
}

global $_system;
if($_GET['action'] == 'rearticle'){
    if($_system['tg_code'] == 1){
        _check_code($_POST['code'],$_SESSION['code']);
    }
    
    $_rows = _fetch_array("SELECT
        tg_uniqid,
        tg_article_time
        FROM
        tg_user
        WHERE
        tg_username='{$_COOKIE['username']}'");
    
    _uniqid($_rows['tg_uniqid'], $_COOKIE['uniqid']);
    
    _timed(time(), $_rows['tg_article_time'], $_system['tg_re']);
    
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
        setcookie('article_time',time());
        $_article_time = time();
        _query("UPDATE
                    tg_user
                SET
                    tg_article_time='{$_article_time}'
                WHERE
                    tg_username='{$_COOKIE['username']}'
            ");
        _query("UPDATE
                    tg_article
                SET
                    tg_commendcount=tg_commendcount + 1
                WHERE
                    tg_id = '{$_clean['reid']}'");
        _close();
        //_session_destroy();
        _location("恭喜你回复发布成功！", "article.php?id=".$_clean['reid']);
    }else{
        _close();
        //_session_destroy();
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
                                tg_nice,
                                tg_date,
                                tg_modify_date
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
        
        $_result = _query("SELECT
                                tg_id,
                                tg_username,
                                tg_sex,
                                tg_face,
                                tg_switch,
                                tg_autograph,
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
        
        if(isset($_COOKIE['username'])){
            $_html['reply'] = '<a href="#re" name="reply" title="回復'.$_html['tg_username_floor'].'的'.$_rows['tg_title'].'">[回復]</a>';
        }
        
        global $_id;
        $_id = 'id='.$_GET['id'].'&';
        
        if($_rows['tg_modify_date'] != '0000-00-00 00:00:00'){
            $_rows['tg_modify_date_str'] = '本帖已由【'.$_rows['tg_username'].'】在'.$_rows['tg_modify_date'].'時間最後一次修改';
        }
        
        //帖子修改
        if($_html['tg_username'] == $_COOKIE['username'] || isset($_SESSION['admin'])){
            $_html['subject_modify'] = '[<a href="article_modify.php?id='.$_GET['id'].'">修改</a>]';
        }
        
        if($_html['tg_switch'] == 1){
            $_html['autograph_str'] = '<p class="autograph">'._ubb($_html['tg_autograph']).'</p>';
        }
        
        global $_pagesize,$_pagenum,$_page;
        _page("SELECT tg_id FROM tg_article WHERE tg_reid=".$_GET['id'], 10);
        
        $_re = _query("SELECT
                            tg_id,
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
	
	<?php 
	   if($_rows['tg_nice']){
	?>
	<img src="images/nice.gif" alt="精华帖" class="nice" />
	<?php }?>
	<?php 
	   if($_rows['tg_readcount'] >= 200 && $_rows['tg_commendcount'] >= 10){
	?>
	<img src="images/hot.gif" alt="热帖" class="hot" />
	
	<?php }?>
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
                <span>
                    <?php if(empty($_rows['tg_nice'])){?>
                        [<a href="article.php?action=nice&on=1&id=<?php echo $_rows['tg_id'] ?>">设置精华帖</a>]
                    <?php }else{?>
                        [<a href="article.php?action=nice&on=0&id=<?php echo $_rows['tg_id'] ?>">取消精华帖</a>]
                    <?php }?>
                    <?php echo $_html['reply']; ?><?php echo $_html['subject_modify']?>1#</span><?php echo $_html['tg_username_floor']?> | <?php echo $_rows['tg_date']?>
            </div>
            <h3>主题：<?php echo $_rows['tg_title']?> <img src="images/icon<?php echo $_rows['tg_type']?>.gif" alt="" /></h3>
            <div class="detail">
                <?php echo $_rows['tg_content'];?>
            </div>
            <div class="read">
                <?php echo $_html['autograph_str'];?>
                                            浏览量（<?php echo $_rows['tg_readcount']?>） 评论数（<?php echo $_rows['tg_commendcount']?>）<?php echo $_rows['tg_modify_date_str']?>
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
	           tg_switch,
               tg_autograph,
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
	       
	       //帖子修改
	       if($_html2['tg_username'] == $_COOKIE['username']){
	           $_html2['subject_modify'] = '[<a href="article_modify.php?id='.$_rows2['tg_id'].'">修改回復</a>]';
	       }
	       if($_i == 2){
	           if($_html2['tg_username'] != $_html['tg_username']){
	               $_html2['tg_username'] .= '(沙发)';
	           }else{
	               $_html2['tg_username'] .= '(楼主)';
	           } 
	       }
	       
	       if($_html2['tg_switch'] == 1){
	           $_html2['autograph_str'] = '<p class="autograph">'._ubb($_html2['tg_autograph']).'</p>';
	       }
	       
	       if(isset($_COOKIE['username'])){
	           $_html2['reply'] = '<a href="#re" name="reply" title="回復'.$_i.'#的'.$_html2['tg_username'].'">[回復]</a>';
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
                <span> <?php echo $_html2['reply']?>  <?php echo $_html2['subject_modify'] ?> <?php echo $_i;?>#</span><?php echo $_html2['tg_username']?> | <?php echo $_rows2['tg_date']?>
            </div>
            <h3>主题：<?php echo $_rows2['tg_title']?> <img src="images/icon<?php echo $_rows2['tg_type']?>.gif" alt="" /></h3>
            <div class="detail">
                <?php echo $_rows2['tg_content'];?>
                <?php echo $_html2['autograph_str'];?>
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
	   <a name="re"></a>
		<dl class="reply">
			<dd>标　　题：<input type="text" name="title" class="text" value="RE:<?php echo $_rows['tg_title']?>" /> (*必填，至少两位)</dd>
			<dd id="q">贴　　图：<a href="javascript:;">Q图系列[1]</a> <a href="javascript:;">Q图系列[2]</a> <a href="javascript:;">Q图系列[3]</a></dd>
			<dd>
			     <?php include ROOT_PATH.'includes/ubb.inc.php';?>
			     <textarea name="content" rows="9"></textarea>
			</dd>
			
			<dd>
			<?php if($_system['tg_code'] == 1){ ?>
			         验 证 码：<input type="text" name="code" class="text yzm"  /> <img src="code.php" alt="验证码" id="code" /> 
		    <?php }?>
		    <input type="submit" class="submit" id="submit" value="发表帖子" />
		    </dd>
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

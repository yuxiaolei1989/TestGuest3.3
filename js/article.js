window.onload = function(){
	var message = document.getElementsByName('message'),
		friend = document.getElementsByName('friend'),
		flower = document.getElementsByName('flower');

	for(var i=0;i<message.length; i++){
		message[i].onclick = function(){
			centerWindow("message.php?id="+this.title,'message',250,400);
		}
	}
	
	for(var i=0;i<friend.length; i++){
		friend[i].onclick = function(){
			centerWindow("friend.php?id="+this.title,'friend',250,400);
		}
	}
	
	for(var i=0;i<flower.length; i++){
		flower[i].onclick = function(){
			centerWindow("flower.php?id="+this.title,'flower',250,400);
		}
	}
	
	code();
	
	var fm = document.getElementsByTagName("form")[0],
		ubb = document.getElementById("ubb"),
		html = document.getElementsByTagName("html")[0],
		font = document.getElementById("font"),
		color = document.getElementById("color"),
		q = document.getElementById('q');
		
	
	if(fm != undefined){
		fm.onsubmit = function(){	
			var title = fm.title.value;
			if(title.length <2 || title.length > 40){
				alert("title不得小于2位或不能大于20位");
				fm.title.value = "";
				fm.title.focus();
				return false;
			}
			
			var content = fm.content.value;
			if(content.length <10){
				alert("content不得小于10位");
				fm.content.value = "";
				fm.content.focus();
				return false;
			}
			
			var code = fm.code.value;
			if(code.length != 4){
				alert("验证码必须为四位！");
				fm.code.value = "";
				fm.code.focus();
				return false;
			}
			
			return true;	
		}
		
		html.onmouseup = function(){
			font.style.display = "none";
			color.style.display = "none";
		}

		fm.t.onmouseup = function(e){
			e.stopPropagation();
		}
	
	}
	
	if(q != null){
		qa = q.getElementsByTagName("a");
		qa[0].onclick = function(){
			window.open('q.php?num=48&path=qpic/1/','face','width=410,height=400,top=0,left=0,scrollbars=1');
		}
		qa[1].onclick = function(){
			window.open('q.php?num=10&path=qpic/2/','face','width=410,height=400,top=0,left=0,scrollbars=1');
		}
		qa[2].onclick = function(){
			window.open('q.php?num=39&path=qpic/3/','face','width=410,height=400,top=0,left=0,scrollbars=1');
		}
	}

	
	if(ubb != null){
		var ubbimg = ubb.getElementsByTagName("img");
		//字体大小
		ubbimg[0].onclick = function(){
			font.style.display = "block";
		}
		
		ubbimg[2].onclick = function(){
			content("[b][/b]");
		}
		ubbimg[3].onclick = function(){
			content("[i][/i]");
		}
		ubbimg[4].onclick = function(){
			content("[u][/u]");
		}
		ubbimg[5].onclick = function(){
			content("[s][/s]");
		}
		
		ubbimg[7].onclick = function(){
			color.style.display = "block";
		}
		
		ubbimg[8].onclick = function(){
			var url = prompt("请输入网址：","http://");
			if(url){
				if(/^https?:\/\/(\w+\.)?[\w\-\.]+(\.\w+)+$/.test(url)){
					content("[url]"+url+"[/url]");
				}else{
					alert("网址输入错误！");
				}
				
			}
			
		}
		
		ubbimg[9].onclick = function(){
			var email = prompt("请输入电子邮件地址：","@");
			if(email){
				if(/^[\w-\.]+@[\w-\.]+(\.\w+)+$/.test(email)){
					content("[email]"+email+"[/email]");
				}else{
					alert("电子邮件输入错误！");
				}
				
			}
		}
		
		ubbimg[10].onclick = function(){
			var img = prompt("请输入图片地址：","");
			if(img){
				content('[img]'+img+'[/img]');
			}
				
		}
		
		ubbimg[11].onclick = function(){
			var flash = prompt("请输入flash网址：","http://");
			if(flash){
				if(/^https?:\/\/(\w+\.)?[\w\-\.]+(\.\w+)+/.test(flash)){
					content("[flash]"+flash+"[/flash]");
				}else{
					alert("flash网址输入错误！");
				}
				
			}
		}
		
		ubbimg[18].onclick = function(){
			fm.content.rows += 1;
		}
		ubbimg[19].onclick = function(){
			fm.content.rows -= 1;
		}
	}
	
	function content(string){
		fm.content.value += string;
	}
	
	if(fm != undefined){
		fm.t.onblur = function(){
			if(this.value != "" && this.value != "#"){
				showcolor(this.value);
			}
		}
	}
	
	var reply = document.getElementsByName("reply");
	for(var i=0;i<reply.length; i++){
		reply[i].onclick = function(){
			fm.title.value = this.title;
		}
	}
}
function font(size){
	var fm = document.getElementsByTagName("form")[0];
	fm.content.value += "[size="+size+"][/size]";
}

function showcolor(value){
	var fm = document.getElementsByTagName("form")[0];
	fm.content.value += "[color="+value+"][/color]";
}


function centerWindow(url,name,height,width){
	var top=(screen.height - height) / 2;
	var left = (screen.width - width) / 2;
	window.open(url,name,"height="+height+",width="+width+",top="+top+",left="+left);
}
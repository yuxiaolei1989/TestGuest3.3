//等在网页加载完毕再执行
window.onload = function () {
	code();
	
	var fm = document.getElementsByTagName("form")[0],
		ubb = document.getElementById("ubb"),
		ubbimg = ubb.getElementsByTagName("img"),
		html = document.getElementsByTagName("html")[0],
		font = document.getElementById("font"),
		color = document.getElementById("color"),
		q = document.getElementById('q'),
		qa = q.getElementsByTagName("a");

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
	
	qa[0].onclick = function(){
		window.open('q.php?num=48&path=qpic/1/','face','width=410,height=400,top=0,left=0,scrollbars=1');
	}
	qa[1].onclick = function(){
		window.open('q.php?num=10&path=qpic/2/','face','width=410,height=400,top=0,left=0,scrollbars=1');
	}
	qa[2].onclick = function(){
		window.open('q.php?num=39&path=qpic/3/','face','width=410,height=400,top=0,left=0,scrollbars=1');
	}
	
	html.onmouseup = function(){
		font.style.display = "none";
		color.style.display = "none";
	}
	
	fm.t.onmouseup = function(e){
		e.stopPropagation();
	}
	
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
	
	function content(string){
		fm.content.value += string;
	}
	
	fm.t.onblur = function(){
		if(this.value != "" && this.value != "#"){
			showcolor(this.value);
		}
		
	}
	
};


function font(size){
	var fm = document.getElementsByTagName("form")[0];
	fm.content.value += "[size="+size+"][/size]";
}

function showcolor(value){
	var fm = document.getElementsByTagName("form")[0];
	fm.content.value += "[color="+value+"][/color]";
}
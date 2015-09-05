//等在网页加载完毕再执行
window.onload = function () {
	code();
	
	var fm = document.getElementsByTagName("form")[0],
		ubb = document.getElementById("ubb"),
		ubbimg = ubb.getElementsByTagName("img"),
		html = document.getElementsByTagName("html")[0],
		font = document.getElementById("font"),
		color = document.getElementById("color");
	
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
				content("[b]"+url+"[/b]");
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
		content('[img]'+img+'[/img]')	
	}
	
	ubbimg[11].onclick = function(){
		var flash = prompt("请输入flash网址：","http://");
		if(flash){
			if(/^https?:\/\/(\w+\.)?[\w\-\.]+(\.\w+)+$/.test(flash)){
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
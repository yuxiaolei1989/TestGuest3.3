/**
 * 
 */

window.onload = function(){
	var up = document.getElementById("up");
	up.onclick = function(){
		centerWindow("upimg.php?dir="+this.title,'up',150,400);
	}
	
	var fm = document.getElementsByTagName("form")[0];
	
	fm.onsubmit = function(){	
		var name = fm.name.value;
		if(name.length <2 || name.length > 40){
			alert("图片名不得小于2位或不能大于20位");
			fm.name.value = "";
			fm.name.focus();
			return false;
		}
		
		var url = fm.url.value;
		if(url == ""){
			alert("地址不得为空");
			fm.url.value = "";
			return false;
		}
	}
}

function centerWindow(url,name,height,width){
	var top=(screen.height - height) / 2;
	var left = (screen.width - width) / 2;
	window.open(url,name,"height="+height+",width="+width+",top="+top+",left="+left);
}
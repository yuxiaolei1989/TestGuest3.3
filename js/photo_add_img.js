/**
 * 
 */

window.onload = function(){
	var up = document.getElementById("up");
	up.onclick = function(){
		centerWindow("upimg.php?dir="+this.title,'up',150,400);
	}
}

function centerWindow(url,name,height,width){
	var top=(screen.height - height) / 2;
	var left = (screen.width - width) / 2;
	window.open(url,name,"height="+height+",width="+width+",top="+top+",left="+left);
}
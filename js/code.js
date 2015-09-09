//等在网页加载完毕再执行
function code () {
	var code = document.getElementById("code");
	
	if(code != null){
		code.onclick = function(){
			this.src = "code.php?t="+Math.random();
		}
	}
	
}
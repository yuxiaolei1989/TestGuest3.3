//等在网页加载完毕再执行
function code () {
	document.getElementById("code").onclick = function(){
		this.src = "code.php?t="+Math.random();
	}
}
//等在网页加载完毕再执行
window.onload = function () {
	code();
	
	var fm = document.getElementsByTagName("form")[0]; 
	
	fm.onsubmit = function(){
		var username = fm.username.value;
		if(username.length <2 || username.length > 20){
			alert("用户名不得小于2位或不能大于20位");
			fm.username.value = "";
			fm.username.focus();
			return false;
		}
		
		if(/[<>\'\"\ \　]/.test(username)){
			alert("用户名不得包含非法字符");
			fm.username.value = "";
			fm.username.focus();
			return false;
		}
		
		if(fm.password.value.length < 6){
			alert("密码不得小于6位！");
			fm.password.value = "";
			fm.password.focus();
			return false;
		}
		
		var code = fm.code.value;
		if(code.length != 4){
			alert("验证码必须为四位！");
			fm.code.value = "";
			fm.code.focus();
			return false;
		}
	}
}
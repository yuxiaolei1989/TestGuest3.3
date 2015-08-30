/**
 * 
 */

window.onload = function(){
	code();
	
	var fm = document.getElementsByTagName("form")[0];
	fm.onsubmit = function(){
		if(fm.password.value != "" && fm.password.value.length < 6){
			alert("密码不得小于6位！");
			fm.password.value = "";
			fm.password.focus();
			return false;
		}
		
		var email = fm.email.value;
		if(!/^[\w-\.]+@[\w-\.]+(\.\w+)+$/.test(email)){
			alert("请输入正确的邮箱！");
			fm.email.value = "";
			fm.email.focus();
			return false;
		}
		
		var qq = fm.qq.value;
		if(qq != ""){
			if(!/^[1-9]{1}[0-9]{4,9}$/.test(qq)){
				alert("QQ号码不正确！！");
				fm.qq.value = "";
				fm.qq.focus();
				return false;
			}
		}
		
		var url = fm.url.value;
		if(!/^https?:\/\/(\w+\.)?[\w\-\.]+(\.\w+)+$/.test(url)){
			alert("网址输入不正确！");
			fm.url.value = "";
			fm.url.focus();
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
}
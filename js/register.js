//等在网页加载完毕再执行
window.onload = function () {
	var faceimg = document.getElementById('faceimg');
	faceimg.onclick = function () {
		window.open('face.php','face','width=400,height=400,top=0,left=0,scrollbars=1');
	}
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
		
		if(/[<>\'\"\ ]/.test(username)){
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
		
		if(fm.notpassword.value.length < 6){
			alert("密码确认不得小于6位！");
			fm.notpassword.value = "";
			fm.notpassword.focus();
			return false;
		}

		if(fm.password.value != fm.notpassword.value){
			alert("密码和确认密码必须一致！");
			fm.notpassword.value = "";
			fm.notpass.focus();
			return false;
		}
		
		var question = fm.question.value;
		if(question.length < 2 || question.length > 20){
			alert("密码提示不得小于2位或者大于20位！");
			question = "";
			fm.question.focus();
			return false;
		}
		
		var answer = fm.answer.value;
		if(answer.length < 2 || answer.length > 20){
			alert("密码回答不得小于2位或者大于20位！");
			fm.answer.value = "";
			fm.answer.focus();
			return false;
		}
		
		if(question == answer){
			alert("密码提示和回答不得相等！");
			fm.answer.value = "";
			fm.answer.focus();
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
};
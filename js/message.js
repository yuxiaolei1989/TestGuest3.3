/**
 * 
 */

window.onload = function(){
	code();
	var fm = document.getElementsBytagName("form")[0];
	fm.onsubmit = function(){
		var len = fm.content.value.length;
		if(len < 10 || len > 200){
			alert("发信内容不能小于10位或者大于200位字符！");
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
	}
}
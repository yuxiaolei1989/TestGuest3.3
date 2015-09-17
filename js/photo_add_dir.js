/**
 * 
 */

window.onload = function(){
	var fm = document.getElementsByTagName("form")[0];
	var pass = document.getElementById("pass");
	
	fm[1].onclick = function(){
		pass.style.display="none";
	}
	
	fm[2].onclick = function(){
		pass.style.display="block";
	}
	
	fm.onsubmit = function(){
		var name = fm.name.value;
		if(name.length <2 || name.length > 20){
			alert("相册名不得小于2位或不能大于20位");
			fm.name.value = "";
			fm.name.focus();
			return false;
		}
		
		if(fm[2].checked){
			var password = fm.password.value;
			if(password.length <6){
				alert("密码不得小于6位");
				fm.password.value = "";
				fm.password.focus();
				return false;
			}
		}
	}
}
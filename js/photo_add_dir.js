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
}
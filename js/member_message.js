/**
 * 
 */

window.onload = function(){
	var all = document.getElementById("all"),
		form = document.getElementsByTagName("form")[0];
	all.onclick = function(){
		for(var i=0; i<form.elements.length;i++){
			if(form.elements[i].name != "chkall"){
				form.elements[i].checked = this.checked;
			}
		}
	}
	form.onsubmit = function(){
		if(confirm("确定要删除？")){
			return true;
		}else{
			return false;
		}
	}
}
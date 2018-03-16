window.onload = function(){
	
	document.getElementsByClassName("play")[0].addEventListener('click', function(){
		toggle(document.getElementsByClassName("difficults")[0]);	
	});
			
	document.getElementById('scoreBoard').addEventListener('click',function(){
		toggle(document.getElementById('scoreResult'));
	});
	
	function toggle(element){
		if(element.style.display == 'none'){
			element.style.display = 'block'
		}else{
			element.style.display = 'none';
		}
	}
}

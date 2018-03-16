function easy(numberOfBoxes){

	var [images,count,number,guess1,guess2] = [[],0,0,"",""];
	
	function makingContainer(){
		var container = document.createElement('div');
		container.setAttribute("id", "container");
		document.getElementsByClassName('playBoard')[0].innerHTML = '';
		document.getElementsByClassName('main')[0].appendChild(container);
		if(numberOfBoxes == 10){
			document.getElementById('container').style.padding = "0 22%";
		}
	}
	
	function getAndSortImages(){
		var pictures = new Set();
		for(var i = 0; i < numberOfBoxes;i++){
			pictures.add(i + ".jpg");
		}
		var uniquePicture = [...pictures];			
		for(var j = 0; j < uniquePicture.length / 2; j++){
			images.push(uniquePicture[j],uniquePicture[j]);
		}
		images = images.sort(() => 0.5 - Math.random());
	}

	function drawBoxes(){
		var output = "<ol>"; 
		for (var k = 0; k < numberOfBoxes; k++) { 
		  output += "<li class='boxes'>";
		  output += "<img src = 'pictures/" + images[k] + "'/>";
		  output += "</li>";
		}
		output += "</ol>";
		document.getElementById('container').innerHTML = output;
		var imgs = document.getElementsByTagName('img');
		for(var img of imgs){
			img.style.display = 'none';
		}
	}
	
	//On Process
	function checkingTime(){
		var counter = 0;
		var result = [];
		
		var time = setInterval(function(){
			counter++  
			if(document.getElementById('container').style.opacity == "0.3"){
			  clearInterval(time);
		    }
		}, 1000);
		
	}
	
	makingContainer();
	getAndSortImages();
	drawBoxes();
	checkingTime();

	for(var box of document.getElementsByClassName('boxes')){
		box.addEventListener("click",guessTheCards);
	}
		
	function guessTheCards(){
		var source = this.children[0].src;
		source = source.substring(source.length-14,source.length);
		if((count < 2) && !this.children[0].classList.contains('face-up')){
			count++;
			this.children[0].style.display = "inline"; 	
			this.children[0].classList.add("face-up"); 	
				if (count === 1) {  
					guess1 = source; 
				}else { 
					guess2 = source;    
					if (guess1 === guess2) { 
						var imgs = document.getElementsByTagName('img');
						for(var img of imgs){
							if(img.getAttribute('src') == guess2){
								img.classList.add('match');
							}
						}	
						number++;
						if(number === numberOfBoxes / 2){
							var container = document.getElementById("container");
							container.style.opacity = "0.3";
							container.innerHTML = "<a href='welcome.php'>Play Again</a>";
							container.style.fontSize = "70px";
						}
					}else { 
						console.log("miss");
						setTimeout(function() {
						var imgs = document.getElementsByTagName('img');
							for(var img of imgs){
								if(!img.classList.contains('match')){
									img.style.display = "none";
									img.classList.remove('face-up');
								}
							}
						}, 1000);
					}
				  count = 0; 
				  setTimeout(function() { console.clear(); }, 60000);      
				}
			}
		}
}


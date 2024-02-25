 			//variable that will increment through the images
			var step=0;
			var slidenow;

			function initial()
				{
					document.images.slide.src=image[0];
					document.getElementById("slider_imageNumber").innerHTML = eval(step +1) + " / " + eval(image.length);
					step =0;
				}

			function slide()
				{
					if (step<eval(image.length-1))
						{
							step++;
						}
					
					else
						{
							step=0;
						}
					document.images.slide.src=image[step];
					document.getElementById("slider_imageNumber").innerHTML = eval(step +1) + " / " + eval(image.length);			
				}

			function start()
				{
					clearInterval(slidenow);
					slidenow = setInterval(function(){slide()}, 2000);
				}

			function stop()
				{
					clearInterval(slidenow);
				}

			function back()
				{	
					clearInterval(slidenow);
					
					if (step>0)
						{
							step--;
						}
					
					else
						{
							step=eval(image.length-1);
						}
					document.images.slide.src=image[step];
					document.getElementById("slider_imageNumber").innerHTML = eval(step +1) + " / " + eval(image.length);
					setTimeout("start()", 4000);
				}

			function forward()
				{
					clearInterval(slidenow);
					
					if (step<eval(image.length-1))
						{
							step++;
						}
					
					else
						{
							step=0;
						}
					document.images.slide.src=image[step];
					document.getElementById("slider_imageNumber").innerHTML = eval(step +1) + " / " + eval(image.length);
					setTimeout("start()", 4000);
				}
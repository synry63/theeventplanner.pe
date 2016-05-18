$(document).ready(function(){

	$('.gallery_content').createDiagonalSlider();

	$('.flexslider').flexslider({
		animation: "slide"
	});
	$('.flexslider2').flexslider({
		animation: "slide",
		controlNav: "thumbnails",
		prevText: "Previous",
		nextText: "Next"
	});
	$('.slick_wedding').slick({
        arrows:true,
        autoplay:true,
		infinite: false,
		speed: 300,
		slidesToShow: 4,
		slidesToScroll: 4,
		responsive: [
			{
				breakpoint: 1024,
				settings: {
					slidesToShow: 3,
					slidesToScroll: 3,
					infinite: true,
					dots: true
				}
			},
			{
				breakpoint: 600,
				settings: {
					slidesToShow: 2,
					slidesToScroll: 2
				}
			},
			{
				breakpoint: 480,
				settings: {
					slidesToShow: 1,
					slidesToScroll: 1
				}
			}
			// You can unslick at a given breakpoint now by adding:
			// settings: "unslick"
			// instead of a settings object
		]
	});

    $("#rateYo").rateYo({
        rating: 3.6,
        ratedFill: "#35b3b4"
    });

    $("#rateYo2").rateYo({
        rating: 3.6,
        ratedFill: "#35b3b4"
    });


    new AnimOnScroll( document.getElementById( 'grid' ), {
        minDuration : 0.4,
        maxDuration : 0.7,
        viewportFactor : 0.2
    } );


});

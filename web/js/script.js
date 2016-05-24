$(document).ready(function(){

	$('.gallery_content').createDiagonalSlider();

	$('.flexslider').flexslider({
		animation: "slide",
        controlNav: true,
        directionNav: true,
        prevText: "Previous",
        nextText: "Next",
    });

	$('.flexslider2').flexslider({
		animation: "slide",
		controlNav: "thumbnails",

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
					dots: false,
                    arrows:false
				}
			},
			{
				breakpoint: 600,
				settings: {
					slidesToShow: 2,
					slidesToScroll: 2,
                    arrows: false,
                    dots: false
				}
			},
			{
				breakpoint: 480,
				settings: {
					slidesToShow: 1,
					slidesToScroll: 1,
					arrows: false
				}
			}
			// You can unslick at a given breakpoint now by adding:
			// settings: "unslick"
			// instead of a settings object
		]
	});

    var test=$("#rateYo").attr("data-rating");

    var test3=$(".rateYo3").attr("data-rating3");

    $("#rateYo").rateYo({
        rating: test,
        ratedFill: "#35b3b4",
		readOnly: true
    });

    $("#rateYo2").rateYo()
        .on("rateyo.set", function (e, data) {
            $("#comentario_proveedor_nota").val(data.rating);
        });


	/*$(".rateYo3").rateYo({
		rating: test3,
		starWidth: "20px",
        readOnly: true,
		ratedFill: "#35b3b4"
	});*/

    $( ".rateYo_comments" ).each(function( index ) {
        $(this).rateYo({
            rating: $(this).attr("data-rating"),
            starWidth: "20px",
            readOnly: true,
            ratedFill: "#35b3b4"
        });
    });


	new AnimOnScroll( document.getElementById( 'grid' ), {
        minDuration : 0.4,
        maxDuration : 0.7,
        viewportFactor : 0.2
    } );


});

;(function( window, undefined ){ 
 'use strict'; 
;(function($) {

	"use strict";
    
    var Core = {
        
        initialize: function() {

			//PRELOADER SCRIPT
			this.loader();
            
            //WOW ANIMATION SETTINGS
            this.animation();
            
            //STELLAR (PARALLAX) SETTINGS
            this.parralax();
            
            },
            loader: function() {
				$('.loader-icon').removeClass('spinning-cog').addClass('shrinking-cog');
				$('.overlay-loader').fadeOut();

			},
            animation: function() {
                
                 $("[data-animation]").each(function() {
                    var $this = $(this);
                    $this.addClass("wow");
                    if(!$("html").hasClass("no-csstransitions") && $(window).width() > 767) {
                        $this.addClass( $this.data( 'animation' ) );
                    } 
        
                });  
                
                var wow = new WOW({
                	offset:100,        // distance to the element when triggering the animation (default is 0)
                	mobile:false       // trigger animations on mobile devices (default is true)
              	});
            	wow.init();
                
            },
            parralax : function() {
                
                if(!(navigator.userAgent.match(/iPhone|iPad|iPod|Android|BlackBerry|IEMobile/i))) {
                	$.stellar({
                		horizontalScrolling: false,
                		positionProperty: 'transform'
                	});
                }
                
            },
           
    }
    
    var CoreReady = {
        
        initialize: function() {
            
            //CUSTOM SCRIPT
            this.customScript();
            
            //NIVIGATION SCRIPT
            this.nav();
            
            //INTRO SLOGAN TYPE
            this.slogan();
            
            //INTRO SUPERSLIDER SETTINGS
            this.slider();
            
            //SCREENSHOTS SLIDER SETTINGS
            this.screenShot();
            
            //TESTIMONIALS SLIDER SETTINGS
            this.testimonial();

            
        },
        customScript : function() {
            
            $('.ray-effect').each(function(){
                var $this = $(this);
                var $parent = $this.parents('.container');
                $parent.after($this);
            })	
            
            if( $('.navbar').data( 'sticky' ) ){   
              $('.navbar').addClass('no-sticky');
            }
            
            $( '#modalContact2' ).each(function(){
               var $this = $( this );
               $('footer').after($this);
            });
        },
        nav: function(){
            
           	$('.nav').onePageNav({
        		currentClass: 'active',
        		filter: ':not(.external)',
        		scrollOffset: 40
        	});
            $('.call-action .goto[href^="#"]').on('click',function (e) {
        	    e.preventDefault();
        
        	    var target = this.hash,
        	    $target = $(target);
        
        	    $('html, body').stop().animate({
        	        'scrollTop': $target.offset().top - 75
        	    }, 1000, 'swing', function () {
        	        window.location.hash = target;
        	    });
       	    }); 
            
        },
        slogan: function(){
        
            var str = $( ".type" ).data("type");
            if( str ){  
                var string = str.split(",");
                $(".type").typed({
                	strings: string,
                    typeSpeed: 200,
            		backDelay:6000,
            		loop: true,
                    loopCount: false,
                });	
            }
            
        },
        slider: function(){
            
            $("#slides").superslides({
        		play: 8000, //Milliseconds before progressing to next slide automatically. Use a falsey value to disable.
        		animation: "fade", //slide or fade. This matches animations defined by fx engine.
        		pagination: false,
        		inherit_height_from:".intro-block",
        		inherit_width_from:".intro-block"
        	});
            
        },
        screenShot: function(){
                
            $('.screenshots-slider').each(function(){
                var id = $(this).attr('id');
                 $('#'+id ).owlCarousel({
                    items : 5, 
                    itemsDesktop : [1400,4], 
                    itemsDesktopSmall : [1200,3], 
                    itemsTablet: [900,2], 
                    itemsMobile : [600,1],
            		stopOnHover:true
                });
                
                $('#'+id ).magnificPopup({
                    delegate: 'a',
                    type: 'image',
                    gallery: {
                        enabled: true
                    }
                });
                
            }); 
            
        },
        testimonial: function(){
            
            $('.testimonials-slider').each(function(){
                var id = $(this).attr('id');
                 $('#'+id ).owlCarousel({
                    singleItem:true,
            		autoPlay:5000,
            		stopOnHover:true
                });
                 
            });
            
        }
    };

    $(document).ready(function() {
        CoreReady.initialize();
    });

    $(window).load(function () {
        Core.initialize(); 
    });
    
     $(window).scroll(function () {
    	  
        if ($(window).scrollTop() > $("nav").height()) {
            $("nav.navbar-slide").addClass("show-menu");
        } else {
            $("nav.navbar-slide").removeClass("show-menu");
			$(".navbar-slide .navMenuCollapse").collapse({toggle: false});
			$(".navbar-slide .navMenuCollapse").collapse("hide");
			$(".navbar-slide .navbar-toggle").addClass("collapsed");
        }
       
    }); 


})(jQuery);
 jQuery('.feature').mouseenter(function(event) {
    var id = jQuery(this).data('instruction-id')
    jQuery('.instruction:visible').hide()
    jQuery(id).fadeIn('100')
})
}( window ));
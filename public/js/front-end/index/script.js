document.body.addEventListener('touchstart', function(){ });
(function ($, window) {

    var App = App ||
        {
            init: function () {
                $('.js-toggle-search').on('click', function () {
                    $('.js-search').toggleClass('is-visible');
                });

                $('.js-gallery').find('a').each(function () {
                    if ($(this).children('img').length) {
                        $(this).attr('rel', 'gallery');
                    }
                }).end().magnificPopup({
                    delegate: '[rel="gallery"]',
                    type: 'image',
                    mainClass: 'mfp-with-zoom', // this class is for CSS animation below
                    gallery: {
                        enabled: true
                    },

                    zoom: {
                        enabled: true,
                        duration: 300, // duration of the effect, in milliseconds
                        easing: 'ease-in-out', // CSS transition easing function
                        opener: function(openerElement) {
                            return openerElement.is('img') ? openerElement : openerElement.find('img');
                        }
                    }
                });

                $('.js-next a').on('click', function (e) {
                    $(infinite_scroll.contentSelector).infinitescroll(infinite_scroll);

                    var $body = $('body');

                    $body.scrollTop($body.scrollTop() - 1);

                    e.preventDefault();
                })
            }
        };

    $(document).ready(function () {
        App.init();

        // animate
        AOS.init(/* {
		offset: 200,
		duration: 400,
		easing: 'ease-in-sine',
		delay: 100,
		} */);

        // Menu	scrolled
        $(window).scroll(function() {
            var mask = $('.bt-nav');

            if ($(this).scrollTop() > 1){
                mask.addClass("scrolled");
            }
            else{
                mask.removeClass("scrolled");
            }
        });

        // Menu	pc view
        $('.navi').addClass('open');
        $('.bt-nav').click( function() {
            $(this).parent().toggleClass( function() {
                if ( $(this).hasClass('open') ) {
                    return 'close';
                } else
                if ( $(this).hasClass('close') ) {
                    return 'open';
                }
            });
        });

        // Menu mobile view
        $( '.main-navigation li.page_item_has_children, .main-navigation li.menu-item-has-children' ).prepend( '<span class="menu-dropdown"><i class="iconfont">&#xe619;</i></span>' );
        // Mobile nav button functionality
        $( '.menu-dropdown' ).bind( 'click', function() {
            $(this).parent().toggleClass( 'open-page-item' );
        });

        // Comments open botton
        $('.btn-slide').click(function(){
            $('#panel').slideToggle("slow");
            $(this).toggleClass("active"); return false;
        });

        // Social share open botton
        $( "#social-share" ).click(function() {
            $("#social").toggleClass("visible").slideToggle(200);
        });


        if ( $( '.welcome' )[0] ){
            $( '.author-info' ).hide();
            $( 'span.info-edit' ).click(function(){
                $( '.author-info' ).toggle(200);
            });
        }




        // CollagePlus Plugin for gallery
        collage();

        function collage(){$('.gallery.gallery-columns-1').removeWhitespace().collagePlus({'fadeSpeed' :1000,'targetHeight' :800}
        );$('.gallery.gallery-columns-2').removeWhitespace().collagePlus({'fadeSpeed' :1000,'targetHeight' :700}
        );$('.gallery.gallery-columns-3').removeWhitespace().collagePlus({'fadeSpeed' :1000,'targetHeight' :520}
        );$('.gallery.gallery-columns-4').removeWhitespace().collagePlus({'fadeSpeed' :1000,'targetHeight' :480}
        );$('.gallery.gallery-columns-5').removeWhitespace().collagePlus({'fadeSpeed' :1000,'targetHeight' :360}
        );$('.gallery.gallery-columns-6').removeWhitespace().collagePlus({'fadeSpeed' :1000,'targetHeight' :280}
        );$('.gallery.gallery-columns-7').removeWhitespace().collagePlus({'fadeSpeed' :1000,'targetHeight' :200}
        );$('.gallery.gallery-columns-8').removeWhitespace().collagePlus({'fadeSpeed' :1000,'targetHeight' :100}
        );$('.gallery.gallery-columns-9').removeWhitespace().collagePlus({'fadeSpeed' :1000,'targetHeight' :80}
        )}

        // browser window is resized
        var resizeTimer = null;
        $(window).bind('resize',function() {
                $('.gallery .gallery-item').css("opacity",0.5);
                if (resizeTimer) clearTimeout(resizeTimer);
                resizeTimer = setTimeout(collage,100)
            }
        );

        // Waypoints
        waypointsInit();

        // Init waypoints for header and footer animations
        function waypointsInit() {
            $('#masthead').waypoint(function(direction) {
                $(this).addClass('animation-on');
            });

            $('#main').waypoint(function(direction) {
                $('#masthead').toggleClass('animation-on');
            });

            $('#footer').waypoint(function(direction) {
                $(this).toggleClass('animation-on');
            } , { offset: 'bottom-in-view' });
        }

        // lazyload
        $(function() {
            $('.comment .profile img').lazyload({threshold :100,effect : "fadeIn"});
        });

        //end
    });

}(jQuery, window));
(function($){
	SlideDeckSkin['image_caption_bottom'] = function(slidedeck){
		var slidedeck = $(slidedeck);
		var slidedeckFrame = slidedeck.closest('.slidedeck_frame')
		var deck = slidedeck.slidedeck();
		var prev = slidedeckFrame.find('.sd-node-previous');
		var next = slidedeckFrame.find('.sd-node-next');
        var prevNext = slidedeckFrame.find('.sd-node-next, .sd-node-previous');
		var primaryNavs = slidedeckFrame.find('.sd-node-nav-primary a.sd-node-nav-link');
        var showHideSpeed = 250;
        
        var sizeDeckFrame = function(){
            if( slidedeck.width() && slidedeck.height() ){
                slidedeckFrame.css({
                    width: slidedeck.css('width'),
                    height: slidedeck.css('height')
                });
            }
        };
		
		var updateActive = function(activeSlide){
			if(deck.options.cycle == false){
				if(activeSlide == 1){
					prev.addClass('disabled');
					next.removeClass('disabled');
				}
				if(activeSlide == deck.slides.length){
					next.addClass('disabled');
					prev.removeClass('disabled');
				}
			}
			primaryNavs.removeClass('active');
			$(primaryNavs[activeSlide - 1]).addClass('active');
		};
		
		var oldNext = deck.next;
		deck.next = function(params){
			var nextSlide = Math.min(deck.slides.length,(deck.current + 1));
			if(deck.options.cycle === true){
				if(deck.current + 1 > deck.slides.length){
					nextSlide = 1;
				}
			}
			
			oldNext(params);
			updateActive(nextSlide);
		};
		var oldPrev = deck.prev;
		deck.prev = function(params){
			var prevSlide = Math.max(1,(deck.current - 1));
			if(deck.options.cycle === true){
				if(deck.current - 1 < 1){
					prevSlide = deck.slides.length;
				}
			}
			
			oldPrev(params);
			updateActive(prevSlide);
		};
		var oldGoTo = deck.goTo;
		deck.goTo = function(ind, params){
		    ind = parseInt(ind);
			oldGoTo(ind, params);
			updateActive(Math.min(deck.slides.length,Math.max(1,ind)));
		};
		
		slidedeckFrame.find('.sd-node-nav-link').bind('click', function(event){
			event.preventDefault();

			var action = this.href.split('#')[1];
			
			deck.pauseAutoPlay = true;

			switch(action){
				case "previous":
					deck.prev();
				break;
				case "next":
					deck.next();
				break;
				default:
					deck.goTo(action);
				break;
			}
		});
		
		$(primaryNavs[0]).addClass('active');
        
        // add animation events to prev/next buttons
        prevNext.show().animate({ opacity: 0 }, 0);
        slidedeckFrame.bind('mouseenter',function(){
            prevNext.stop(true).animate({
                opacity: 1
            }, showHideSpeed);
        });
        slidedeckFrame.bind('mouseleave',function(){
            prevNext.animate({
                opacity: 0
            }, showHideSpeed);
        });
        
        // size the deck frame
        sizeDeckFrame();
		
        function imgLoaded(el, thisSlide){
            if($.data(el, 'image_caption_bottom-sized') == true){
                return false;
            }
            
            var $el = $(el);
            
            var slideWidth = thisSlide.innerWidth();
            var slideHeight = thisSlide.innerHeight();

            // Remove attributes in case img-element has set width and height
            var pic_real_width = $el.width();
            var pic_real_height = $el.height();
            
            $el.removeAttr("width").removeAttr("height").css({ width: "", height: "" }); // Remove css dimensions as well
            pic_real_width = el.width;
            pic_real_height = el.height;
            
            var image_ratio = (pic_real_width / pic_real_height);
            var deck_ratio = (slideWidth / slideHeight);
            
            if(image_ratio < deck_ratio){
                // image too tall
                var newHeight = Math.round(slideWidth / (image_ratio));
                var newWidth = slideWidth;
            }else if(image_ratio >= deck_ratio){
                // image too wide
                var newWidth = Math.round(slideHeight * (image_ratio));
                var newHeight = slideHeight;
            }
            $el.css({
                position: 'absolute',
                top: '50%',
                left: '50%',
                width: newWidth + 'px',
                maxWidth: newWidth + 'px',
                height: newHeight + 'px',
                maxHeight: newHeight + 'px',
                marginLeft: '-' + Math.round(newWidth / 2) + 'px',
                marginTop: '-' + Math.round(newHeight / 2) + 'px'
            });
            
            if(pic_real_width > 0 && pic_real_height > 0){
                $.data(el, 'image_caption_bottom-sized', true);
            }
        }
        
        deck.loaded(function(){
            for(var z=0, slides=slidedeckFrame.find('dd.slide .sd-node-container'); z<slides.length; z++){
                var thisSlide = $(slides[z]);
                
                if(thisSlide.find('.sd-node-image img').length){
                    
                    // strip any false image sizes
                    var img = thisSlide.find('.sd-node-image img')[0];
                    
                    $(img).load(function() {
                        imgLoaded(this, thisSlide);
                    });
                    
                    var src = img.src;
                    img.src = "";
                    img.src = src;
                    // fade the first image in
                    if( z == 0 ){
                        $(img).fadeIn(100);
                    }else{
                        $(img).show();
                    }
                }
            }
        });
        
        
        /**
         * Try to accommodate for the race condition created between the image loading,
         * the DOM being ready, the browser determining when images actual have dimensions
         * associated with them and the page being ready for view. The imgLoaded() function
         * is run on the loaded event of each image as well as DOMReady and Window Loaded.
         * The imgLoaded() function will return boolean(false) if it has already processed
         * and been able to scale the image properly.
         */
        $(document).ready(function(){
            deck.slides.find('.sd-node-image img').each(function(){
                imgLoaded(this, $(this).closest('dd.slide .sd-node-container'));
            });
        });
        $(window).load(function(){
            deck.slides.find('.sd-node-image img').each(function(){
                imgLoaded(this, $(this).closest('dd.slide .sd-node-container'));
            });
        });
		
		return true;
	};
    
    $(document).ready(function(){
        $('.skin-image_caption_bottom .slidedeck').each(function(){
            if(typeof($.data(this, 'skin-image_caption_bottom')) == 'undefined'){
                $.data(this, 'skin-image_caption_bottom', new SlideDeckSkin['image_caption_bottom'](this));
            }
        });
    });
})(jQuery);
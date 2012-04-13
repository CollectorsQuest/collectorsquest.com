<div class="slot1-inner">
  <div id="carousel-holder">
    <span class="carousel_next ir"><a href="#" class="button_carousel_next" title="Next">Next</a></span>
    <span class="carousel_previous ir"><a href="#" class="button_carousel_previous" title="Previous">Prev</a></span>
    <ul id="sample-roundabout">
      <?php foreach ($carousels as $i => $carousel): ?>
      <li>
        <div class="wrapper">
          <?php
            if (sfConfig::get('sf_environment') == 'dev') {
              echo ice_image_tag_flickholdr('520x310', array('i' => $i));
            } else {
              echo image_tag($carousel['image']);
            }
          ?>
          <div class="carousel-in-focus-title-container">
            <div class="carousel-in-focus-title">
              <h2 class="Chivo webfont"><?= $carousel['title'] ?></h2>
              <p><?= $carousel['content'] ?></p>
            </div>
          </div>
        </div>
      </li>
      <?php endforeach; ?>
    </ul>
  </div><!--/#carousel-holder-->
</div><!--/.slot1-inner-->

<script>
  $(document).ready(function()
  {
    var $roundaboutEl = $('#sample-roundabout'),
        originalHtml = $roundaboutEl.html(),
        childInFocus = 0,
        switchResolutions = [996];

    /**
     * Find the current resolution index, 0 based, from switchResolutions
     */
    var findCurrentResolutionIndex = function() {
      var index = 0,
          currentWidth = cq.helpers.getWindowWidth();

      $.each(switchResolutions, function(i, resolution){
        if ( currentWidth > resolution ) {
          index = i+1;
        } else {
          return false; // exit each
        }
      })

      return index;
    };
    var resolutionIndex = findCurrentResolutionIndex();

    var setupRoundabout = function() {
      $roundaboutEl.hide();
      $roundaboutEl.roundabout({
        startingChild: childInFocus,
        shape: 'square',
        minScale: 0.6,
        minOpacity: 1,
        duration: 800,
        easing: 'easeOutQuad',
        triggerFocusEvents: true,
        enableDrag: true,
        responsive: true,
        dropEasing: 'easeOutBounce',
        btnNext: '.button_carousel_next',
        btnPrev: '.button_carousel_previous',
        autoplay: true,
        autoplayDuration: 6000,
        autoplayPauseOnHover: true
      },function(){
        $roundaboutEl.fadeTo(1000, 1)
      });
    }; // setup_roundabout

    /**
     * Callback executed after the window resize operation has finished,
     * and a small timeout has passed (so that it does not fire while the user
     * is still resizing the window
     *
     * If the current resolution has a different index than when the window
     * was loaded then destroy the roundabout and recreate it, so that it will
     * use the new dimensions
     */
    var onResizeComplete = function() {
      if (findCurrentResolutionIndex() !== resolutionIndex) {
        // keep the currenly displayed item
        childInFocus = $roundaboutEl.roundabout('getChildInFocus');
        // fix for multiple intervals left from initial roundabout
        $roundaboutEl.roundabout('stopAutoplay');
        // fade out the current roundabout and regenerate it from the original html
        $roundaboutEl.fadeOut(500, function(){
          $roundaboutEl.html(originalHtml);
          // we need to manually make webfonts visible, because usually
          // the js for them will have executed after we have copied the original
          // html, and the visibility will not be set.
          $roundaboutEl.find('.webfont').css('visibility', 'visible');
          setupRoundabout();
        });

        // set the new resolution index
        resolutionIndex = findCurrentResolutionIndex();
      }
    };

    // bind on window resize, but fire only after 200 ms of no resize happening
    var resizeTimeout = false;
    $(window).on('resize', function() {
      if (false !== resizeTimeout) {
        clearTimeout(resizeTimeout);
      }
      resizeTimeout = setTimeout(onResizeComplete, 200);
      console.log(window.cq.helpers.getWindowWidth());
    });

    // all is in place, setup the roundabout
    setupRoundabout();
  });
</script>

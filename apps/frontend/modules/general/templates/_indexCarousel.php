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
              <h2><?= $carousel['title'] ?></h2>
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
    $("#sample-roundabout").roundabout({
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
    }, function() {
      $(this).fadeTo(500, 1);
    });

    $('#sample-roundabout li')
      .focus(function()
      {
        $(this)
          .find('.carousel-in-focus-title-container')
          .css({visibility: "visible"})
          .fadeIn('slow');
      })
      .blur(function()
      {
        $(this)
          .find('.carousel-in-focus-title-container')
          .fadeOut('slow');
      });
  });
</script>

<div class="slot1-inner">
  <div id="carousel-holder">
    <span class="carousel-next ir"><a href="javascript:void(0)" class="button-carousel-next" title="Next">Next</a></span>
    <span class="carousel-previous ir"><a href="javascript:void(0)" class="button-carousel-previous" title="Previous">Prev</a></span>
    <ul id="sample-roundabout">
      <?php foreach ($carousels as $i => $carousel): ?>
      <li>
        <div class="wrapper">
          <?php
            if (sfConfig::get('sf_environment') == 'dev') {
              echo ice_image_tag_flickholdr('520x310', array('i' => $i, 'alt' => $carousel['title']));
            } else {
              echo cq_image_tag($carousel['image'], array('alt' => $carousel['title']));
            }
          ?>
          <div class="carousel-in-focus-title-container link">
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
  $("#carousel-holder .wrapper a").addClass("target");
  $("#carousel-holder .wrapper a").bigTarget({
    hoverClass: 'over',
    clickZone: '.link'
  });
});
</script>

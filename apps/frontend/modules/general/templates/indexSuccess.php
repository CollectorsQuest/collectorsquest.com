<h2 class="FugazOne" style="font-size: 20px; color: white; width: 100%; background: #0982C2; margin-bottom: 0;">
  &nbsp;&nbsp;Showcase
</h2>
<div class="row">
  <div  id="homepage" class="row-content">
    <div class="span3 brick">
      <a href="#">
        <?= ice_image_tag_flickholdr('138x138'); ?>
      </a>
    </div>
    <div class="span3 brick">
      <a href="#">
        <?= ice_image_tag_flickholdr('138x138'); ?>
    </div>
    <div class="span3 brick">
      <a href="#">
      <?= ice_image_tag_flickholdr('138x138'); ?>
      </a>
    </div>
    <div class="span6 brick">
      <a href="#">
      <?= ice_image_tag_flickholdr('290x290'); ?>
      </a>
    </div>
    <div class="span3 brick">
      <a href="#">
        <?= ice_image_tag_flickholdr('138x138'); ?>
      </a>
    </div>
    <div class="span6 brick masonry-blogpost">
      <div class="masonry-blogpost" style="background: url("www.collectorsquest.com/uploads/blog/2012/04/marathon-breal-silver-cup-spiros-louis-olympics.jpg") no-repeat">
        <a href="#" class="link">adsasd

        </a>
      </div>
    </div>
    <div class="span3 brick">
      <a href="#">
        <?= ice_image_tag_flickholdr('138x138'); ?>
      </a>
    </div>
    <div class="span3 brick">
      <a href="#">
        <?= ice_image_tag_flickholdr('138x138'); ?>
      </a>
    </div>
    <div class="span3 brick">
      <a href="#">
        <?= ice_image_tag_flickholdr('138x138'); ?>
      </a>
    </div>
    <div class="span3 brick">
      <a href="#">
        <?= ice_image_tag_flickholdr('138x138'); ?>
      </a>
    </div>
    <div class="span3 brick">
      <a href="#">
        <?= ice_image_tag_flickholdr('138x138'); ?>
      </a>
    </div>
    <div class="span3 brick">
      <a href="#">
        <?= ice_image_tag_flickholdr('138x138'); ?>
      </a>
    </div>
    <div class="span3 brick">
      <a href="#">
        <?= ice_image_tag_flickholdr('138x138'); ?>
      </a>
    </div>
    <div class="span3 brick">
      <a href="#">
        <?= ice_image_tag_flickholdr('138x138'); ?>
      </a>
    </div>
    <div class="span3 brick">
      <a href="#">
        <?= ice_image_tag_flickholdr('138x138'); ?>
      </a>
    </div>
    <div class="span3 brick">
      <a href="#">
        <?= ice_image_tag_flickholdr('138x138'); ?>
      </a>
    </div>
  </div>
</div>


<script>
  $(document).ready(function()
  {
    var $container = $('#homepage');

    $container.imagesLoaded(function() {
      $container.masonry(
        {
          itemSelector : '.brick',
          columnWidth : 138, gutterWidth: 15,
          isAnimated: !Modernizr.csstransitions
        });
    });
  });
</script>

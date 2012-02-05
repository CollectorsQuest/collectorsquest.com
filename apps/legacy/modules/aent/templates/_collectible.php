<style type="text/css">
  #page {
    background-color: #F5F8DD;
  }
</style>

<table style="width: 990px; margin: -5px 0 -20px 0;">
  <tr>
    <td style="width: 521px; height: 65px; margin: 0; padding: 0;">
      <?php
        echo link_to(
          image_tag('legacy/aetn/'. str_replace(' ', '_', strtolower($collection->getName())) .'_header.png'),
          '@aent_landing'
        );
      ?>
    </td>
    <td style="background: url(/images/legacy/aetn/collectible_title.png) no-repeat 0 1px; height: 65px;  margin: 0; padding: 0">
      <h3 style="color: #fff; font-weight: bold; padding-top: 7px; margin-left: 20px"><?= $title ?></h3>
    </td>
  </tr>
  <tr>
    <td style="height: 500px; padding-left: 23px; background: url(/images/legacy/aetn/blue_gradient.png) 10px bottom no-repeat; vertical-align: top;">
      <div style="position: relative;">
        <?php
          echo link_to_if(
            !empty($video), image_tag_collectible($collectible, '485x365'),
            '@collectible_by_slug?id='. $collectible->getId() .'&slug='. $collectible->getSlug() .'#mediaspace', array('id' => 'video1')
          );
        ?>
        <?php if (!empty($video)): ?>
        <div style="position: absolute; top: 150px; left: 225px">
          <?php
            echo link_to(
              image_tag('icons/play.png'),
              '@collectible_by_slug?id='. $collectible->getId() .'&slug='. $collectible->getSlug() .'#mediaspace',
              array('id' => 'video2')
            );
          ?>
        </div>
        <?php endif; ?>
      </div>
      <h2 style="color: #5f7c8b;"><?= $collectible->getName(); ?></h2>
      <?= $collectible->getDescription(); ?>

      <hr style="width: 98%; border: 1px dotted gray; float: left; margin-bottom: 15px; clear: right;">
      <h2 style="font-size: 18px;">
        More from <?= $collection->getName() ?>
      </h2>
      <div style="margin: 10px;">
        <a href="<?php echo url_for(sprintf('@collectible_by_slug?id=%d&slug=%s', $previous->getId(), $previous->getSlug())); ?>" class="prevPage browse left"></a>
        <div class="scrollable">
          <img src="/images/loading.gif" alt="loading..." class="loading" style="margin: 45px 0 0 90px;"/>
          <ul style="display: none;">
            <?php
            foreach ($collection->getCollectibles() as $c)
            {
              if (!$c instanceof Collectible)
                continue;

              echo '<li style="margin: 0 0px;">';
              echo link_to_collectible(
                $c, 'image', array(
                'width' => 75, 'height' => 75,
                'rel' => url_for('@collectible_by_slug?id=' . $c->getId() . '&slug=' . $c->getSlug())
                )
              );
              echo '</li>';
            }
            ?>
          </ul>
        </div>
        <a href="<?php echo url_for(sprintf('@collectible_by_slug?id=%d&slug=%s', $next->getId(), $next->getSlug())); ?>" class="nextPage browse right"></a>
      </div>
    </td>
    <td style="vertical-align: top; padding-left: 25px;">
      <?php foreach ($collectibles_for_sale as $i => $collectible_for_sale): ?>
        <div class="span-5" style="margin-right: 20px;">
          <div class="stack" style="background: url(/images/legacy/aetn/stack_yellow.png); width: 143px; height: 144px; padding: 5px 0 0 14px;">
            <?= link_to_collectible($collectible_for_sale->getCollectible(), 'image', array('style' => 'width: 135px; height: 135px;')); ?>
          </div>
          <div style="margin-top: 10px;">
            <?= link_to_collectible($collectible_for_sale->getCollectible(), 'text', array('style' => 'text-decoration: none; color: #626261;')); ?>
            <br/><span style="color: red;"><?= money_format('%.2n', $collectible_for_sale->getPrice()); ?></span>
          </div>
      </div>
      <?php if (($i + 1) % 2 == 0) echo '<br clear="all"><br>'; ?>
      <?php endforeach; ?>
    </td>
  </tr>
  <tr>
    <td colspan="2" style="margin: 0; padding: 0;">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" style="background: #fff; padding:  10px;">
      <div style="background-color: #f5f8dd; padding: 5px;">
        <div class="box">
          <h2 style="font-size: 18px; font-weight: bold; padding-left: 15px; background: white url('/images/legacy/black-arrow.png') 0 6px no-repeat;">
            Related Items from Collectors’ Quest
          </h2>
          <br clear="all">
          <?php foreach ($featured as $i => $f): ?>
            <div class="span-5" style="margin-right: 25px; margin-left: 10px; margin-bottom: 40px;">
              <a href="<?= url_for_collection($f); ?>">
                <?= image_tag_collection($f, '150x150', array('style' => 'width: 75px; height: 75px; float: left; margin-right: 10px;')); ?>
                <?= $f->getName(); ?>
              </a>
          </div>
          <? if ($i == 3) echo '<br clear="all">'; ?>
          <?php endforeach; ?>
          <br clear="all">
        </div>
      </div>
    </td>
  </tr>
</table>

<div id="mediaspace" style="display: none;">&nbsp;</div>

<?php if (!empty($video)): ?>
<script type="text/javascript">
  $(document).ready(function()
  {
    $("a#video1, a#video2").fancybox(
    {
      hideOnContentClick: true,
      overlayOpacity: 0.5,
      autoDimensions: false,
      scrolling: 'no',
      width: 848, height: 480, padding: 0,
      enableEscapeButton: true,
      centerOnScroll: true,
      onStart: function()
      {
        jwplayer('mediaspace').setup(
        {
          flashplayer: '/swf/mediaplayer.swf',
          file: '<?= src_tag_multimedia($video, 'original'); ?>',
          autostart: true,
          width: 848, height: 480,
          skin: "/images/legacy/glow.zip",
          'plugins': 'fbit-1,tweetit-1,gapro-2',
          'gapro.accountid': 'UA-669177-1',
          'fbit.link': 'false',
          'tweetit.link': 'false'
        });
      },
      onCleanup: function ()
      {
        jwplayer("mediaspace").remove();
      }
    });
  });
</script>
<?php endif; ?>

<script type="text/javascript">
  $('div.scrollable').ready(function()
  {
    $('div.scrollable .loading').hide();
    $('div.scrollable ul').show();
    $("div.scrollable").jCarouselLite(
    {
      btnNext: "a.nextPage", btnPrev: "a.prevPage",
      mouseWheel: false, visible: 4, scroll: 1, circular: false, start: 0
    });
  });
</script>

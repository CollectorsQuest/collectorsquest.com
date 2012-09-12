<div class="banners-620 spacer-bottom-20">
  <?php
    if ($brand === 'American Pickers')
    {
      echo link_to(image_tag('headlines/2012-0420_AP_Promo_Space_620x67_FIN.jpg'), '@aetn_american_pickers');
    }
    else if ($brand === 'Pawn Stars')
    {
      echo link_to(image_tag('headlines/2012-0420_PS_Promo_Space_620x67_FIN.jpg'), '@aetn_pawn_stars');
    }
    else if ($brand === 'Picked Off')
    {
      echo link_to(image_tag('headlines/2012-0777_Picked_Off_620x67.jpg'), '@aetn_picked_off');
    }
  ?>
</div>
<?php cq_page_title($collectible->getName()); ?>

<div class="brand-item" style="position: relative;">
  <?php
    echo link_to_if(
      !empty($video), image_tag_collectible($collectible, '620x370'),
      '@collectible_by_slug?id='. $collectible->getId() .'&slug='. $collectible->getSlug() .'#mediaspace',
      array('id' => 'video1')
    );
  ?>
  <?php if (!empty($video)): ?>
  <div style="position: absolute; top: 145px; left: 285px;">
    <?php
      echo link_to(
        cq_image_tag('icons/play.png'),
        '@collectible_by_slug?id='. $collectible->getId() .'&slug='. $collectible->getSlug() .'#mediaspace',
        array('id' => 'video2')
      );
    ?>
  </div>
  <?php endif; ?>
</div>

<div class="blue-actions-panel spacer-20">
  <div class="row-fluid">
    <div class="pull-left">
      <ul>
        <li>
        <?php
          echo format_number_choice(
            '[0] no comments yet|[1] 1 Comment|(1,+Inf] %1% Comments',
            array('%1%' => number_format($collectible->getNumComments())),
            $collectible->getNumComments()
          );
        ?>
        </li>
        <li>
          <?php
          echo format_number_choice(
            '[0] no views yet|[1] 1 View|(1,+Inf] %1% Views',
            array('%1%' => number_format($collectible->getNumViews())), $collectible->getNumViews()
          );
          ?>
        </li>
        <!--
          <li>
            In XXX wanted lists
          </li>
        //-->
      </ul>
    </div>
    <div id="social-sharing" class="pull-right share">
      <!-- AddThis Button BEGIN -->
      <?php /*
      <a href="javascript:void(0)" class="btn-lightblue btn-mini-social">
        <i class="add-icon-medium"></i> Add to your wanted list
      </a>
      */?>
      <a class="btn-lightblue btn-mini-social addthis_button_email">
        <i class="mail-icon-mini"></i> Email
      </a>
      <a class="addthis_button_pinterest_pinit" pi:pinit:media="<?= src_tag_collectible($collectible, 'original'); ?>" pi:pinit:layout="horizontal"></a>
      <a class="addthis_button_tweet" tw:twitter:data-count="none"></a>
      <a class="addthis_button_google_plusone" g:plusone:size="medium" g:plusone:annotation="none"></a>
      <a class="addthis_button_facebook_like" fb:like:layout="button_count" fb:like:width="75"></a>
      <!-- AddThis Button END -->
    </div>
  </div>
</div>


<div class="item-info">
  <?= $collectible->getDescription('html'); ?>
</div>

<?php
  include_partial(
    'comments/comments',
    array('for_object' => $collectible->getCollectible())
  );
?>

<?php
  $link = link_to(
    'See all related collectibles &raquo;', '@marketplace',
    array('class' => 'text-v-middle link-align')
  );
  $link = null;

  cq_section_title('Showcase', $link);
?>

<div class="row">
  <div id="collectibles" class="row-content">
  <?php
    if (!empty($related_collectibles))
    {
      /** @var $related_collectibles Collectible[] */
      foreach ($related_collectibles as $i => $collectible)
      {
        include_partial(
          'collection/collectible_grid_view_square_small',
          array('collectible' => $collectible, 'i' => $i)
        );
      }
    }
    else if (!empty($related_collections))
    {
      foreach ($related_collections as $i => $collection)
      {
        include_partial(
          'collection/collection_grid_view_square_small',
          array('collection' => $collection, 'i' => $i)
        );
      }
    }
  ?>
  </div>
</div>

<div id="mediaspace" class="modal hide" tabindex="-1" role="dialog">
  <div id="mediaspace-body" class="modal-body">
    &nbsp;
  </div>
</div>

<?php if (!empty($video)): ?>
<script type="text/javascript">
$(document).ready(function()
{
  $("a#video1, a#video2").click(function(e)
  {
    e.preventDefault();

    jwplayer('mediaspace-body').setup({
      flashplayer: '/swf/mediaplayer.swf',
      file: '<?= src_tag_multimedia($video, 'original'); ?>',
      autostart: true,
      width: 720, height: 416,
      skin: "<?= cq_image_src('glow.zip', false); ?>",
      'plugins': 'fbit-1,tweetit-1,gapro-2',
      'gapro.accountid': 'UA-669177-1',
      'fbit.link': '<?= cq_canonical_url(); ?>',
      'tweetit.link': '<?= cq_canonical_url(); ?>'
    });

    $('#mediaspace').modal();

    return false;
  });
});
</script>
<?php endif; ?>

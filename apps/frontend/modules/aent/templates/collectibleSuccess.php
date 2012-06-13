<div class="banners-620 spacer-bottom-20">
  <?php
    if ($brand === 'American Pickers')
    {
      echo link_to(image_tag('banners/2012-0420_AP_Promo_Space_620x67_FIN.jpg'), '@aetn_american_pickers');
    }
    else if ($brand === 'Pawn Stars')
    {
      echo link_to(image_tag('banners/2012-0420_PS_Promo_Space_620x67_FIN.jpg'), '@aetn_pawn_stars');
    }
  ?>
</div>
<?php cq_page_title($collectible->getName()); ?>

<div class="brand-item">
  <?= image_tag_collectible($collectible, '620x370'); ?>
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
    <div class="pull-right share">
      <!-- AddThis Button BEGIN -->
      <?php /*
      <a href="#" class="btn btn-lightblue btn-mini-social">
        <i class="add-icon-medium"></i> Add to your wanted list
      </a>
      */?>
      <a class="btn btn-lightblue btn-mini-social addthis_button_email">
        <i class="mail-icon-mini"></i> Email
      </a>
      <a class="addthis_button_facebook_like" fb:like:layout="button_count" fb:like:width="40"></a>
      <a class="addthis_button_tweet" tw:twitter:data-count="none"></a>
      <a class="addthis_button_google_plusone" g:plusone:size="medium" g:plusone:annotation="none"></a>
      <a class="addthis_button_pinterest_pinit" pi:pinit:media="<?= src_tag_collectible($collectible, 'original'); ?>" pi:pinit:layout="horizontal"></a>
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

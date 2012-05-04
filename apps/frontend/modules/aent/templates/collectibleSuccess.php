<div class="banners-620 bottom-margin-double">
  <?php
    if ($brand === 'Pawn Stars')
    {
      echo link_to(image_tag('banners/040412_pawnstars_620x67.jpg'), '@aetn_pawn_stars');
    }
  ?>
</div>
<?php cq_page_title($collectible->getName()); ?>

<div class="brand-item">
  <?= image_tag_collectible($collectible, '620x370'); ?>
</div>

<div class="statistics-share-panel bottom-margin-double">
  <div class="row-fluid">
    <div class="span6">
      <ul>
        <li>
          <?php
            echo format_number_choice(
              '[0] no comments yet|[1] 1 Comment|(1,+Inf] %1% Comments',
              array('%1%' => number_format($collectible->getNumComments())), $collectible->getNumComments()
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
        <li>
          In XXX wanted lists
        </li>
      </ul>
    </div>
    <div class="span6 text-right">
      <a href="#" class="btn btn-mini-share btn-lightblue-middle">
        <i class="add-icon-medium"></i> Add this item to your most wanted list
      </a>
      <a href="#" class="btn btn-mini-share btn-lightblue-middle">
        <i class="add-icon-medium"></i> Share
      </a>
    </div>
  </div>
</div>

<div class="item-info">
  <?= $collectible->getDescription('html'); ?>
</div>

<?php include_partial('sandbox/comments'); ?>
<div class="permalink">
  Permalink: <span class="lightblue"><?= url_for_collectible($collectible, true) ?></span>
</div>

<?php
  $link = link_to('See all related collectibles &raquo;', '@marketplace', array('class' => 'text-v-middle link-align'));
  cq_section_title('Showcase', $link);
?>

<div class="row">
  <div id="collectibles" class="row-content">
  <?php
    /** @var $related_collectibles Collectible[] */
    foreach ($related_collectibles as $i => $collectible)
    {
      include_partial(
        'collection/collectible_grid_view_square_small',
        array('collectible' => $collectible, 'i' => $i)
      );
    }
  ?>
  </div>
</div>

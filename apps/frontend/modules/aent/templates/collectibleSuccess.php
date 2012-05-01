<div class="banners-620 bottom-margin-double">
  <?php
    if ($brand === 'Pawn Stars')
    {
      echo '<img src="/images/banners/040412_pawnstars_620x67.jpg" alt="">';
    }
  ?>
</div>
<?php cq_page_title($collectible->getName()); ?>

<div class="brand-item">
  <?= image_tag_collectible($collectible, '620x370'); ?>
</div>

<div class="info-blue-box bottom-margin-double">
  <div class="row-fluid">
    <div class="span6">
      <ul>
        <li>
          <span>XX Comments</span>
        </li>
        <li>
         <span>
           <?php
            echo format_number_choice(
              '[0] no views yet|[1] 1 View|(1,+Inf] %1% Views',
              array('%1%' => number_format($collectible->getNumViews())), $collectible->getNumViews()
            );
            ?>
         </span>
        </li>
        <li>
          <span>In XXX wanted lists</span>
        </li>
      </ul>
    </div>
    <div class="span6 text-right">
      <a href="#" class="btn btn-mini-share btn-lightblue-middle">
        <i class="add-icon-middle"></i> Add this item to your most wanted list
      </a>
      <a href="#" class="btn btn-mini-share btn-lightblue-middle">
        <i class="share-icon-middle"></i> Share
      </a>
    </div>
  </div>
</div>

<div class="item-info">
  <p>9-shot LeMat revolver used by the Confederate Army, American, c. 1856-1865<br>Weight, 3.1 lb unloaded. Length 13.25 in.<p>
  <p>On <a href="#">Guns Blazing</a>, premiere date 02/20/2012, viewers prepared for battle with the Pawn Stars as one of the rarest guns in American history pops into the shop. A 9-shot LeMat revolver used by the Confederate Army, Rick charges into this deal with guns blazing. Only about 2,900 of these unique sidearms (also known as the "Grape Shot Revolver”) were produced. It was developed in New Orleans in 1856 by Dr. Jean Alexander Le Mat, whose manufacturing effort was backed by P.G.T. Beauregard, a general in the Confederate Army.</p>
</div>

<div id="comments">
  <div class="add-comment">
    <div class="input-append post-comment">
      <form method="post" action="comment">
        <input type="text" id="c" data-provide="comment" autocomplete="off" name="c">
        <button type="submit" class="btn btn-large">Comment</button>
        <a class="upload-photo" title="Add a photo">&nbsp;</a>
      </form>
    </div>
  </div>
  <div class="user-comments">
    <div class="row-fluid user-comment">
      <div class="span2 text-right">
        <a href="#">
          <img src="http://placehold.it/65x65" alt="">
        </a>
      </div>
      <div class="span10">
        <p class="bubble left">
          <a href="#" class="username">RobotBacon Wow!</a>
          That gun is a real rarity.  I don't think the south produced much in the way of weaponry, so that is a good find!
          <span class="comment-time">2 hours ago</span>
        </p>
      </div>
    </div>
    <div class="row-fluid user-comment">
      <div class="span2 text-right">
        <a href="#">
          <img src="http://placehold.it/65x65" alt="">
        </a>
      </div>
      <div class="span10">
        <p class="bubble left">
          <a href="#" class="username">RobotBacon Wow!</a>
          That gun is a real rarity.  I don't think the south produced much in the way of weaponry, so that is a good find!
          <span class="comment-time">2 hours ago</span>
        </p>
      </div>
    </div>
  </div>
  <div class="see-more-under-image-set">
    <button class="btn btn-small gray-button see-more-full" id="see-more-comments">
      See all XX comments
    </button>
  </div>

</div>

Permalink: <span class="lightblue"><?= url_for_collectible($collectible, true) ?></span>

<?php
  $link = link_to('See all related collectibles &raquo;', '@marketplace', array('class' => 'text-v-middle link-align'));
  cq_section_title('Showcase', $link);
?>

<div class="row collections-container">
  <div id="collections" class="row-content">
    <?php
    /** @var $collections Collection[] */
    foreach ($collections as $i => $collection)
    {
      include_partial(
        'collection/collection_grid_view_square_small',
        array('collection' => $collection, 'i' => $i)
      );
    }
    ?>
  </div>
</div>
<div class="see-more-under-image-set">
  <button class="btn btn-small gray-button see-more-full" id="see-more-collections">
    See more
  </button>
</div>



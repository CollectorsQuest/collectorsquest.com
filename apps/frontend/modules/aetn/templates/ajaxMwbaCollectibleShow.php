<?php
/**
 * @var $collectible Collectible
 * @var $collection  Collection
 */
?>

<div class="mwba-mp-modal-floral-rooseveltiana"></div>
<!-- <div class="mwba-mp-modal-floral-railroadiana"></div>
<div class="mwba-mp-modal-floral-petroliana"></div> -->

<div id="modal-collectible">

  <div class="red-decorative-title">
    <div class="top">
      <h1 class="Chivo webfont"><?= $collectible->getName() ?></h1>
    </div>
    <div class="bottom"></div>
  </div>

  <?php
    //cq_page_title($collectible->getName());

    echo link_to(
      image_tag_collectible(
        $collectible, '620x0',
        array('width' => null, 'height' => null)
      ),
      src_tag_collectible($collectible, 'original'),
      array('id' => 'collectible_multimedia_primary', 'target' => '_blank')
    );
  ?>

  <div class="blue-actions-panel spacer-20">
    <div class="row-fluid">
      <div class="pull-left">
        <ul>
          <li>
            <?php
            echo format_number_choice(
              '[0] no views yet|[1] 1 View|(1,+Inf] %1% Views',
              array('%1%' => number_format($collectible->getNumViews())), $collectible->getNumViews()
            );
            ?>
          </li>
        </ul>
      </div>
      <div id="social-sharing" class="pull-right share">
        <!-- AddThis Button BEGIN -->
        <a class="btn-lightblue btn-mini-social addthis_button_email">
          <i class="mail-icon-mini"></i> Email
        </a>
        <a class="addthis_button_pinterest_pinit" pi:pinit:layout="horizontal"
           pi:pinit:media="<?= src_tag_collectible($collectible, 'original'); ?>"></a>
        <a class="addthis_button_tweet" tw:twitter:data-count="none"></a>
        <a class="addthis_button_google_plusone" g:plusone:size="medium" g:plusone:annotation="none"></a>
        <a class="addthis_button_facebook_like" fb:like:layout="button_count" fb:like:width="75"></a>
        <!-- AddThis Button END -->
      </div>
    </div>
  </div>

  <?php if ($collectible->getDescription('stripped')): ?>
    <div class="item-description" id="collectible_<?= $collectible->getId(); ?>_description">
      <?= $description = $collectible->getDescription('html'); ?>

      <?php if (!empty($aetn_show)): ?>
      <br><br>

      <small>
        <i>*&nbsp;<?= $aetn_show['name'] ?>,</i>
        HISTORY and the History “H” logo are the trademarks of A&amp;E Television Networks, LLC.
      </small>

      <?php endif; ?>

    </div>
  <?php endif; ?>

  <?php
    include_component('comments', 'comments', array(
        'for_object' => $collectible->getCollectible()
    ));
  ?>

  <a href="<?= url_for_collection($collection); ?>" class="seemore-popupbutton"></a>
</div>

<!-- will transfer there styles later -->
<style>
  .modal-header {
    background: none !important;
  }
  .post-comment textarea {
    width: 390px !important;
  }
  .modal-body {
    max-height: 600px !important;
  }
  .modal-footer {
    display: none;
  }
  .modal {
    -webkit-border-radius: 0px;
    -moz-border-radius: 0px;
    border-radius: 0px;
    top: 35%;
  }
</style>

<script src="//s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4fa2c6240b775d05"></script>

<script type="text/javascript">
  // expanding of comments section textarea and button
  $('textarea').focus(function (e) {
      e.preventDefault();

      $(function () {
        $('textarea').addClass('expand');
        $('.btn.btn-large').addClass('expand');
        $('.extra-fields.non-optional').show();
      });
  });

  addthis.toolbox("#social-sharing");
</script>

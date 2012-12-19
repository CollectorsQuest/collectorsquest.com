<?php
/**
 * @var $collectible Collectible
 * @var $collection  Collection
 * @var $sf_request  cqWebRequest
 */
?>

<div class="modal modal-mwba not-rounded" data-dynamic="true" tabindex="-1">
  <div class="modal-body opened" style="max-height: none;">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <span class="modal-floral-title"></span>

    <div id="modal-collectible">
      <div class="red-decorative-title">
        <div class="top">
          <h3 class="Chivo webfont"><?= $collectible->getName() ?></h3>
        </div>
        <div class="bottom"></div>
      </div>

      <center>
      <?php
        echo link_to(
          image_tag_collectible(
            $collectible, '620x0',
            array('width' => null, 'height' => null)
          ),
          url_for_collectible($collectible), array('target' => '_blank')
        );
      ?>
      </center>

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
          <?php if (!$sf_request->isMobileBrowser()): ?>
          <div id="social-sharing-<?= $collectible->getId(); ?>" class="pull-right share">
            <?php
              include_partial(
                'global/addthis',
                array(
                  'image' => src_tag_collectible($collectible, 'original'),
                  'text' => $collectible->getName(),
                  'url' => url_for_collectible($collectible, true)
                )
              );
            ?>
          </div>
          <?php endif; ?>
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

      <br><a href="<?= url_for_collection($collection); ?>" class="seemore-popupbutton">&nbsp;</a><br>
    </div> <!-- ./modal-collectible -->
  </div> <!-- ./modal-body -->
</div>

<script type="text/javascript">
  addthis.toolbox('#social-sharing-<?= $collectible->getId(); ?>');
</script>

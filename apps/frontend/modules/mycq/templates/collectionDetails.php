<?php
/**
 * @var $collection CollectorCollection
 * @var $form CollectorCollectionEditForm
 */

slot('mycq_dropbox_info_message', 'Drag a photo into the Collection thumbnail below');
?>

<?php
  cq_sidebar_title(
    format_number_choice(
      '[0] %1% <small>(no items yet)</small>|[1] %1% <small>(1 item)</small>|(1,+Inf] %1% <small>(%2% items)</small>',
      array(
        '%1%' => $collection->getName(),
        '%2%' => number_format($total)),
      $total
    ), null,
    array('left' => 10, 'right' => 2, 'class'=>'mycq-red-title row-fluid')
  );
?>

<?php
  include_partial(
    'mycq/partials/collection_gray_bar',
    array('collection' => $collection)
  );
?>

<div id="mycq-tabs">
  <ul class="nav nav-tabs">
    <li>
    <?php
      echo link_to(
        'Items in Collection ('. $total .')',
        'mycq_collection_by_section', array(
          'id' => $collection->getId(),
          'section' => 'collectibles'
        )
      );
    ?>
    </li>
    <li class="active">
    <?php
      echo link_to(
        'Collection Details',
        'mycq_collection_by_section', array(
          'id' => $collection->getId(),
          'section' => 'details'
        )
      );
    ?>
    </li>
  </ul>
  <div class="tab-content">
    <div class="tab-pane active">
      <div class="tab-content-inner">

        <form action="<?= url_for('mycq_collection_by_section', array('id' => $collection->getId(), 'section' => 'details')); ?>" novalidate
              id="form-collection" method="post" enctype="multipart/form-data"
              class="form-horizontal spacer-bottom-reset">
          <?= $form->renderAllErrors(); ?>

          <div class="row-fluid">
            <div id="main-image" class="span3">
              <?php
                include_component(
                  'mycq', 'collectionMultimedia',
                  array('collection' => $collection)
                );
              ?>
            </div>

            <div class="span9">
              <fieldset style="width: 580px;">
                <?= $form; ?>
              </fieldset>
            </div>

            <div class="row-fluid">
              <div class="span12">
                <div class="form-actions text-center spacer-inner-15">
                  <?php $label = $collection->getNumItems() === 0 ? 'Save & Start Adding Items' : 'Save & Go to Items'; ?>
                  <button type="submit" formnovalidate
                          class="btn btn-primary" name="save_and_go" value="<?= $label ?>">
                    <?= $label ?>
                  </button>
                  &nbsp;&nbsp;
                  <button type="submit" formnovalidate
                          class="btn" name="save" value="Save Changes">
                    Save Changes
                  </button>

                  <div style="float: right; margin-right: 15px;">
                    <a href="<?= url_for('mycq_collection_by_section', array('id' => $collection->getId(), 'section' => 'collectibles')) ?>"
                       class="btn spacer-left">
                      Cancel
                    </a>
                  </div>
                </div> <!-- .form-actions.text-center.spacer-inner-15 -->
              </div> <!-- .span12 -->
            </div> <!-- .row-fluid -->
          </div> <!-- .row-fluid -->
        </form>

      </div><!-- .tab-content-inner -->
    </div> <!-- .tab-pane.active -->
    <div id="tab4" class="tab-pane">
      <div class="tab-content-inner spacer">
        &nbsp;
      </div><!-- .tab-content-inner -->
    </div><!-- #tab4.tab-pane -->
  </div><!-- .tab-content -->
</div>

<script>
$(document).ready(function()
{
  $('#collection_description').wysihtml5({
    "font-styles": false, "image": false, "link": false,
    events:
    {
      "load": function() {
        $('#collection_description')
          .removeClass('js-hide')
          .removeClass('js-invisible');
      },
      "focus": function() {
        $(editor.composer.iframe).autoResize();
      }
    }
  });
});
</script>

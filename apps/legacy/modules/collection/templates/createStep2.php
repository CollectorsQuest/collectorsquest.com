<?php
  /**
   * @var  CollectionCreateForm  $form
   * @var  CollectionCategory    $collection_category
   */

  include_partial(
    'global/wizard_bar',
    array('steps' => array(1 => __('Choose Category'), __('Describe Collection'), __('Add Collectibles')), 'active' => 2)
  );
?>

<?php slot('sidebar'); ?>
  <h2 style="margin-top: 10px;">Quick Tips</h2>
  <div style="margin: 0 10px;">
    <p>
      This information defines your ENTIRE collection and will lead people
      to look at all of the collectibles within it.
    </p>
    <ol style="margin: 0; padding-left: 25px;">
      <li style="margin-bottom: 10px;">
        Try to be <strong>specific</strong> for your collection name. Instead of "Joe's book collection", use "Joe's Presidential Book collection".
        This will enable others who are interested in the same thing to find you easier.
      </li>
      <li style="margin-bottom: 10px;">
        Use descriptive terms - brand names, manufacturers and genres are all terms
        that can help users find your collectibles. For example, instead of "political buttons"
        say "1960's political war buttons", etc.
      </li>
      <li style="margin-bottom: 10px;">
        <strong>Tags/Keywords</strong> should be <strong>descriptive</strong> in nature. While using words like "awesome" or "vintage" may describe your collection,
        using words that tell what the item is or uses a brand name are better. Instead of saying "toys" better tags are
        "Batman, DC, comics, action figure". Again, the more descriptive you are, the more people will find and view your collection.
      </li>
    </ol>
  </div>
<?php end_slot(); ?>

<form action="<?= url_for('@collection_create?step=2'); ?>" method="post" enctype="multipart/form-data">
  <div class="span-4" style="text-align: right;">
    <?php echo __('Category:'); ?>
  </div>
  <div class="prepend-1 span-13 last">
    <?= $collection_category->getName(); ?> &nbsp;
    <?= link_to('(change?)', '@collection_create?step=1', array('onclick' => 'return confirm("'. __('Navigation away from this page will not save your current changes. Are you sure you want to continue?') .'");')); ?>
    <input type="hidden" name="collection_category_id" value="<?= $collection_category->getId(); ?>">
  </div>
  <div class="clear append-bottom">&nbsp;</div>

  <div class="span-4" style="text-align: right;">
    <?= cq_label_for($form, 'name', __('Name:')); ?>
    <div class="required"><?= __('(required)'); ?></div>
  </div>
  <div class="prepend-1 span-13 last">
    <?= cq_input_tag($form, 'name', array('width' => 400)); ?>
    <?= $form['name']->renderError(); ?>
  </div>
  <div class="clear append-bottom">&nbsp;</div>

  <div class="span-4" style="text-align: right;">
    <?= cq_label_for($form, 'thumbnail', __('Thumbnail Image:')); ?>
    <div class="required"><?= __('(required)'); ?></div>
  </div>
  <div class="prepend-1 span-13 last">
    <?= $form['thumbnail']; ?>
    <?= $form['thumbnail']->renderError(); ?>
  </div>
  <div class="clear append-bottom">&nbsp;</div>

  <div class="span-4" style="text-align: right;">
    <?php echo cq_label_for($form, 'description', __('Description:')); ?>
    <div class="required"><?= __('(required)'); ?></div>
  </div>
  <div class="prepend-1 span-13 last">
    <?php echo $form['description']->renderError() ?>
    <?php echo cq_textarea_tag($form, 'description', array('width' => 500, 'height' => 200, 'rich' => true)); ?>
  </div>
  <div class="clear append-bottom">&nbsp;</div>
  <div class="span-4" style="text-align: right;">
    <?php echo cq_label_for($form, 'tags', __('Tags / Keywords:')); ?>
    <div style="color: #ccc; font-style: italic;"><?= __('(recommended)'); ?></div>
  </div>
  <div class="prepend-1 span-13 last">
    <?php $tags = !empty($defaults['tags']) ? $defaults['tags'] : $form->getObject()->getTags(); ?>
    <div style="background: #E9E9E9; vertical-align: middle; width: 400px; padding: 5px;">
    <select id="collection_tags" name="collection[tags][]">
      <?php foreach ($tags as $tag): ?>
      <option value="<?= $tag; ?>" class="selected"><?= $tag; ?></option>
      <?php endforeach; ?>
    </select>
    </div>
  </div>
  <div class="clear append-bottom">&nbsp;</div>

  <div class="span-18" style="text-align: right;">
    <?php cq_button_submit(__('Save & Go to the Next Step'), null, 'float: right;'); ?>
  </div>

  <?= $form['_csrf_token']; ?>
</form>

<script src="/js/jquery/tags.js" type="text/javascript"></script>

<?php cq_javascript_tag(); ?>
<script type="text/javascript">
$(function()
{
  $('#collection_description').tinymce(
  {
    script_url: '/js/tiny_mce/tiny_mce.js',
    content_css : "/css/legacy/tinymce.css",

    theme: "advanced",
    theme_advanced_buttons1: "formatselect,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo",
    theme_advanced_buttons2: "",
    theme_advanced_buttons3: "",
    theme_advanced_toolbar_location: "external",
    theme_advanced_toolbar_align: "left",
    theme_advanced_resizing: true
  });

  $('#collection_tags').fcbkcomplete({
    json_url: '<?= url_for('@ajax_autocomplete?section=tags'); ?>',
    maxshownitems: 10,
    cache: true,
    filter_case: true,
    filter_hide: true,
    firstselected: true,
    filter_selected: true,
    addoncomma: true,
    input_min_size: 2,
    width: '388px',
    newel: true
  });
});
</script>
<?php cq_end_javascript_tag(); ?>

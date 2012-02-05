<?php
  include_partial(
    'global/wizard_bar',
    array('steps' => array(1 => __('Choose Category'), __('Describe Collection'), __('Add Collectibles')), 'active' => 3)
  );
?>

<?php slot('sidebar'); ?>
  <h2 style="margin-top: 10px;">Quick Tip</h2>
  <div style="margin: 0 10px;">
    <p>Do you want to <b>upload multiple pictures</b> at once? Here's how:</p>
    <ol style="margin: 0; padding-left: 25px;">
      <li style="margin-bottom: 10px;">Click on the "BROWSE" button to the left. A window will open asking you to search for your files.</li>
      <li style="margin-bottom: 10px;">Change your view to the thumbnail view. This will help you see what photos you are selecting.</li>
      <li style="margin-bottom: 10px;">Select the photos you would like to upload by holding down the CTRL button and clicking on each desired photo. Then click the Open button in the bottom right hand corner.</li>
      <li style="margin-bottom: 10px;">Once you are done selecting photos, click on the "Select" button. You will see the files uploading.</li>
      <li style="margin-bottom: 10px;">Once the photos are uploaded, you will be directed to a page to fill out details about your collection items.</li>
    </ol>
  </div>
<?php end_slot(); ?>

<div class="prepend-4">
  <?php include_partial('collection/collectibles_upload', array('collection' => $collection)); ?>
</div>
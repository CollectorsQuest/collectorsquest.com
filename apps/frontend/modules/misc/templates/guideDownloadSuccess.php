<?php
  /* @var  $sf_request  cqWebRequest */
  /* @var  $hash        string */
?>

<div class="guide-splash-container">
  <div class="wrapper-top">
    <div class="row-fluid">
      <?php include_partial('misc/guide_intro'); ?>
      <div class="span5">
        <div class="signup-form-splash">
          <div class="row-fluid">
            <div class="span6 pdf">
              <a href="<?= url_for('misc_guide_download_file', array('hash' => $hash, 'sf_format' => 'pdf')) ?>"
                 title="Download the Essential Guide to Collecting - PDF">
                <?= cq_image_tag('frontend/pdf-ico.png', array('alt' => 'Adobe PDF'))?>
                <span>Download PDF</span>
              </a>
            </div>
            <div class="span6 zip">
              <a href="<?= url_for('misc_guide_download_file', array('hash'=>$hash, 'sf_format' => 'zip')) ?>"
                 title="Download the Essential Guide to Collecting - ZIP Archive">
                <?= cq_image_tag('frontend/zip-ico.png', array('alt' => 'ZIP archive'))?>
                <span>Download ZIP</span>
              </a>
            </div>
          </div>
          <?php if (!$sf_request->isMobile()): ?>
          <br/><br/><br/>
          <p>
            Share the "<?= link_to('The Essential Guide to Collecting', '@misc_guide_to_collecting') ?>"
            with your friends or fellow collectors:
          </p>
          <br/>
          <!-- AddThis Button BEGIN -->
          <div  id="social-sharing" class="addthis_toolbox addthis_default_style addthis_32x32_style spacer-left">
            <a class="addthis_button_preferred_1"></a>
            <a class="addthis_button_preferred_2"></a>
            <a class="addthis_button_preferred_3"></a>
            <a class="addthis_button_preferred_4"></a>
            <a class="addthis_button_preferred_5"></a>
            <a class="addthis_button_compact"></a>
            <a class="addthis_counter addthis_bubble_style"></a>
          </div>
          <!-- AddThis Button END -->
          <br>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
  <?php include_partial('misc/guide_footer'); ?>
</div>

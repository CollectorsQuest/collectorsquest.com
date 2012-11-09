<?php
  /* @var $organization Organization */
?>

<div class="blue-actions-panel spacer-bottom">
  <div class="row-fluid">
    <div class="pull-left">
      <ul>
        <li>
          <?php
          echo format_number_choice(
            '[0] no views yet|[1] 1 View|(1,+Inf] %1% Views',
            array('%1%' => number_format($organization->getNumViews())), $organization->getNumViews()
          );
          ?>
        </li>
      </ul>
    </div>
    <div class="pull-left">
      <?php if (!$organization->isMember($sf_user->getCollector()) && OrganizationPeer::ACCESS_PRIVATE != $organization->getAccess()): ?>
      <?= form_tag('@organization_join?id='.$organization->getId(), array('class' => 'form-horizontal')) ?>
        <button type="submit" class="btn btn-mini">Join</button>
      </form>
      <?php endif; ?>
    </div>
    <div id="social-sharing" class="pull-right share">
      <?php // removing the addthis_button_email causes a JS error - no toolbar displayed ?>
      <a class="addthis_button_email" style="display: none;"></a>
      <a class="addthis_button_tweet" tw:twitter:data-count="none"></a>
      <a class="addthis_button_google_plusone" g:plusone:size="medium" g:plusone:annotation="none"></a>
      <a class="addthis_button_facebook_like" fb:like:layout="button_count" fb:like:width="75"></a>
    </div>
  </div>
</div>

<div class="row-fluid spacer-top-20">
  <?php cq_sidebar_title('More about ' . $organization->getName(), null); ?>
  <div class="personal-info-sidebar" itemprop="description">
    <?php if ($organization->getUrl()): ?>
      <p><strong>Our website:</strong> <?= link_to($organization->getUrl(), $organization->getUrl()); ?></p>
    <?php endif; ?>
    <?php if ($organization->getPhone()): ?>
      <p><strong>Our phone:</strong> <?= $organization->getPhone(); ?></p>
    <?php endif; ?>
    <?php if ($organization->getDescription()): ?>
      <p><strong>About us:</strong></p>
      <div class="organization-description">
        <?= $organization->getDescription(); ?>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php include_component('organization', 'widgetMembers', array(
    'organization' => $organization,
)); ?>



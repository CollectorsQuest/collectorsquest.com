<script>
  if (document.getElementById('shopping-cart-count') !== null)
  {
    <?php $k = $sf_user->getShoppingCartCollectiblesCount(); ?>
    document.getElementById('shopping-cart-count').innerHTML = '<?= (string) $k ?>';
    <?php if ($k): ?>
    document.getElementById('shopping-cart-count').className = '';
    <?php endif; ?>
    document.getElementById('shopping-cart-count-link').setAttribute(
            'title', '<?= (0 < $k) ? 'View your shopping cart' : 'Your shopping cart is empty!'; ?>'
    );

  }
  if (document.getElementById('header_menu_<?= (string) SmartMenu::getSelected('header'); ?>') !== null)
  {
    document.getElementById('header_menu_<?= (string) SmartMenu::getSelected('header'); ?>').className = 'active';
  }
  if (document.getElementById('q') !== null)
  {
    document.getElementById('q').value = '<?= (string) $sf_params->get('q'); ?>';
  }


</script>
<?php include_component_slot('jquery_footer'); ?>

<?= javascript_include_tag('frontend/pure.js'); ?>
<?php if ($sf_user->isAuthenticated())
{
  /* @var $collector Collector */
  $collector = $sf_user->getCollector();
  $unread_messages = $collector->getUnreadMessagesCount();
  $profile_completed = $collector->getProfile()->getProfileCompleted();
  slot('profile-hint'); ?>
  <?php if (75 <= $profile_completed): ?>
    <a href="<?= url_for('@mycq_profile?ref=' . cq_link_ref('footer')) ?>" class="bold-links">
      Add info about what you collect
    </a>.
    <?php elseif (50 <= $profile_completed): ?>
    <a href="<?= url_for('@mycq_collections?ref=' . cq_link_ref('footer')) ?>" class="bold-links">
      Add a collectible
    </a> in minutes.
    <?php else: ?>
    <a href="<?= url_for('@mycq_collections?ref=' . cq_link_ref('footer')) ?>#my-collections"
       class="bold-links">
      Add a collection
    </a> in minutes.
    <?php endif; ?>
    (+25%)
  <?php end_slot(); ?>
  <script>
    var data = {
      'collector-name':   '<?= $collector->getDisplayName(); ?>!',
      'pm-counter':       <?= $unread_messages < 1000 ? $unread_messages : '&#8734;'; ?>,
      'pm-counter-word':  '<?= format_number_choice('[0]no messages|[1]1 message|(1, +Inf]%count% messages',
                            array('%count%' => $unread_messages), $unread_messages); ?>',
      'profile-complected':  '<?= $profile_completed ?>',
      'profile-hint':     '<?= preg_replace('/^\s+|\n|\r|\s+$/m', '', get_slot('profile-hint')) ?>',
      'shopping-cart-inner': '<?= $sf_user->getShoppingCartCollectiblesCount(); ?>'
    };
    $('footer').autoRender(data);
  </script>
<?php } ?>

<script src="//ajax.googleapis.com/ajax/libs/chrome-frame/1/CFInstall.min.js"></script>

<?php if ($sf_params->get('gcf')): ?>
<script src="//ajax.googleapis.com/ajax/libs/chrome-frame/1/CFInstall.min.js"></script>
<script> CFInstall.check({ mode: "overlay" }); </script>
<?php endif; ?>

<?php
  // Include the cqcdns.com javascript files
  cq_include_javascripts();
?>

<script>
  Modernizr.load([{
    test: Modernizr.isauthenticated,
    yep:  '<?= javascript_path('frontend/scripts.authenticated.bundle.' . GIT_REVISION . '.js'); ?>',
    both: '<?= javascript_path('frontend/scripts.common.bundle.' . GIT_REVISION . '.js'); ?>',
    complete: function ()
    {
      // http://stackoverflow.com/a/8567229
      (function ($, window, document)
      {
        for (func in window._docready) {
          $(document).ready(window._docready[func]);
        }
      }(jQuery, this, this.document));

      // execute the main controller after template level JS to allow
      // window.cq.settings modifications :)
      $(document).ready(CONTROLLER.init);

      // Let is "free" :)
      $.holdReady(false);
    }
  }]);
</script>

<script>
if (document.getElementById('social-sharing') !== null)
{
  (function(d, t)
  {
    var addthis = d.createElement(t);
    var s = d.getElementsByTagName(t)[0];

    addthis.async = true;
    addthis.src = '//s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4fa2c6240b775d05&async=true';
    s.parentNode.insertBefore(addthis, s);

  })(document, 'script');

  $(window).load(function() {
    addthis.toolbox('#social-sharing');
  });
}
</script>

<?php if ($sf_request->isSecure()): ?>
  <script src="https://www.startssl.com/seal.js"></script>
<?php endif; ?>

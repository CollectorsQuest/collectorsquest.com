<div id="header" class="span-21 prepend-4 rounded-top last">
  <div style="float: right; margin: 5px 10px -4px 10px; position: relative;">
    <div style="float: left; margin-top: 5px; margin-right: 10px;">|</div>
    <?= link_to(image_tag('legacy/payment/header_shopping_cart.png', array('style' => 'float: right;')), '@shopping_cart'); ?>
    <?php if (0 < $k = $sf_user->getShoppingCartCollectiblesCount()): ?>
    <div class="rounded" style="position: absolute; top: -3px; left: 25px; background: #BADC70;">
      <?= link_to('&nbsp;&nbsp;'. $k .'&nbsp;&nbsp;', '@shopping_cart', array('style' => 'color: #fff; text-decoration: none; font-weight: bold;')); ?>
    </div>
    <?php endif; ?>
  </div>
  <form class="span-8 last" action="<?php echo url_for('@search'); ?>" id="header-search" method="get">
    <div class="header-search-submit">
      <input alt="Search" class="search_submit" src="/images/legacy/search_icon.png" title="Search" type="image" />
      <a class="style_activator" href="#" title="Select search type" id="flat"></a>
      <div class="hidden">
        <?= image_tag('legacy/black-arrow-down.png'); ?>&nbsp;<strong>Search Only:</strong>
        <ul style="margin-left: 10px;">
          <li><a href="javascript:search('collectibles');"><?= __('Collectibles'); ?></a></li>
          <li><a href="javascript:search('collections');"><?= __('Collections'); ?></a></li>
          <li><a href="javascript:search('collectors');"><?= __('Collectors'); ?></a></li>
          <li><a href="javascript:search('blog');"><?= __('Blog Posts'); ?></a></li>
          <li style="border-bottom: 1px solid silver; height: 1px; margin: 5px 0;"></li>
          <li><?= link_to(__('Advanced Search'), '@search_advanced'); ?></li>
        </ul>
      </div>
    </div>
    <input id="header-search-box" name="q" tabindex="1" type="text" placeholder="<?= $sf_params->get('q', __('Search for collections or collectibles')); ?>" />
  </form>
  <div id="header-account">
    <?php if (!$sf_user->isAuthenticated()): ?>
      <a href="<?php echo url_for('@collector_signup'); ?>" id="header-signup"><strong><?php echo  __('Sign up for an Account'); ?></strong></a>
      &nbsp;|&nbsp;
      <a href="<?php echo url_for('@login'); ?>#ajax-login-tabs" id="header-login" onclick="return false;"><?php echo  __('Sign in to Your Account'); ?></a>
      <div class="visuallyhidden">
        <?php include_component('ajax', 'loginForm'); ?>
      </div>
    <?php else: ?>
      <?php echo sprintf(__('Hello again, %s'), '<b>'.$sf_user->getCollector().'</b>'); ?>!
      &nbsp;
      <?php echo link_to(__('(Sign out)'), $sf_user->getLogoutUrl(), array('style' => 'color: #000;')); ?>
    <?php endif; ?>
  </div>
  <h4 style="color: #fff; margin-top: 9px;"><?php echo  __("Where hunters gather!™"); ?></h4>
</div>


<?php cq_javascript_tag(); ?>
<script type="text/javascript">
$(function()
{
  $('#flat').menu({
    content: $('#flat').next().html(),
    showSpeed: 200,
    positionOpts: {
			posX: 'left',
			posY: 'bottom',
			offsetX: -1,
			offsetY: 5,
			directionH: 'right',
			directionV: 'down',
			detectH: true, // do horizontal collision detection
			detectV: true, // do vertical collision detection
			linkToFront: false
		}
  });

  search = function (which)
  {
    document.location.href = '/search/'+ which +'?q='+ $('#header-search-box').val();
  }

  $('#header-search-box').placeholder();

  $("a#header-login").fancybox(
  {
    hideOnContentClick: false,
    overlayOpacity: 0.5,
    autoDimensions: false,
    width: 410, height: jQuery.browser.opera && 330 || 300,
    enableEscapeButton: true,
    centerOnScroll: true
  });
});
</script>
<?php cq_end_javascript_tag(); ?>

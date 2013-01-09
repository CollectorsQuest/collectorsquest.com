<?php
/* @var  $menu  array */
/* @var  $show_holiday_adv  boolean */
/* @var $sf_request cqWebRequest */
?>

<div id="HolidayMarketHeader">
  <?php if ($show_holiday_adv && !$sf_request->isMobileLayout()): ?>
  <div class="holiday-marker-adv-dialog-below-menu" >
    <a href="<?php echo url_for('@seller_signup?ref=mp_banner'); ?>" class="link" title="Sell Your Stuff with Us"></a>
    <a class="icon-remove close-btn" title="close"></a>
  </div>
  <?php endif; ?>
</div>

<a name="market"></a>
<div id="holiday-market-body">
  <div class="holiday-market-menu-wrapper">
    <div class="navbar-inner">
      <div id="scrollable" class="centering">
        <ul class="items nav">
          <?php foreach ($menu as $i => $item): ?>
          <li <?= $i == 0 ? 'class="active"' : null; ?>>
            <?php
              echo link_to(
                $item['name'], 'ajax_marketplace',
                array('section' => 'component', 'page' => 'holidayTheme', 't' => $i, 'p' => 1),
                array(
                  'anchor' => 'holiday-market-theme', 'class' => 'ajax', 'data-index' => $i,
                  'data-slug' =>  $item['slug']
                )
              );
            ?>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>

      <span class="arrow-previous">
        <a class="arrow-white-previous">&nbsp;</a>
      </span>
      <span class="arrow-next">
        <a class="arrow-white-next">&nbsp;</a>
      </span>
    </div>
  </div>

  <?php include_component('marketplace', 'holidayTheme'); ?>
</div>

<script>
  $(document).ready(function()
  {
    $('a.ajax', '#scrollable').autoajax({
      onstart: function($element) {
        $('#holiday-market-theme').showLoading();

        if ($element.data('index') >= 0)
        {
          $('#scrollable').data('scrollable').seekTo($element.data('index'));
        }
      },
      oncomplete: function() {
        $('.fade-white').mosaic();
        $('#holiday-market-theme').hideLoading();

        var $element = $('li.active a.ajax', '#scrollable');
        if ($element)
        {
          // Change the hash when users press arrows or theme names
          window.location.hash = $element.data('slug');
        }
      }
    });

    // Initialize the scrollable
    $("#scrollable").scrollable({
      next: '.arrow-next',
      prev: '.arrow-previous',
      size: 1,
      itemsPerFrame: 5,
      circular: true,
      onBeforeSeek: function(e, i)
      {
        $('li', '#scrollable').each(function(index)
        {
          $(this).toggleClass('active', $('a', $(this)).data('index') == i);

          if ($('a', $(this)).data('index') == i)
          {
            $('a', $(this)).data('index', -1);
            $('a', $(this)).click();
            $('a', $(this)).data('index', i);
          }
        });

        return true;
      },
      onSeek: function() {
        $('li.cloned:not(.ajaxified) a.ajax', '#scrollable').autoajax({
          onstart: function($element) {
            $('#holiday-market-theme').showLoading();

            if ($element.data('index') >= 0)
            {
              $('#scrollable').data('scrollable').seekTo($element.data('index'));
            }
          },
          oncomplete: function($element) {
            $('.fade-white').mosaic();
            $('#holiday-market-theme').hideLoading();
          }
        });

        $('li.cloned', '#scrollable').addClass('ajaxified');
      }
    });

    // If there is hash set - load page with proper content
    var hash = window.location.hash;
    hash = hash.replace('#','');

    // Check if the hash is in the list of available caches
    if ($.inArray(hash, <?= json_encode(array_map(function($item) { return $item['slug']; }, $menu)); ?>) != -1)
    {
      $('#holiday-market-theme').load('/ajax/marketplace/component/holidayTheme?hash=' + hash, function() {
        var index = $('a[data-slug=' + hash + ']').data('index');
        $('#scrollable').data('scrollable').seekTo(index);
      });
    }

    // use this function to make the back button work
    window.onhashchange = function () {

    };

    <?php if ($show_holiday_adv): ?>
    $('.icon-remove.close-btn').click(function ()
    {
      $('.holiday-marker-adv-dialog-below-menu').load('/ajax/marketplace/close/advDialog', function() {
        $('.holiday-marker-adv-dialog-below-menu').hide();
      });
    });
    <?php endif; ?>
  });
</script>

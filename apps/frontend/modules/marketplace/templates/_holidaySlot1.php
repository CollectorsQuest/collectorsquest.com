<?php
/* @var $menu array */
?>

<div id="HolidayMarketHeader">
  <!--
  <h1>Keep It<br>Classics</h1>
  <h2>for the holidays</h2>
  -->
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
                array('anchor' => 'holiday-market-theme', 'class' => 'ajax', 'data-index' => $i)
              );
            ?>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>

      <span class="arrow-previous js-hide">
        <a href="#slot1" class="arrow-white-previous">&nbsp;</a>
      </span>
      <span class="arrow-next">
        <a href="#slot1" class="arrow-white-next">&nbsp;</a>
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
        $('#scrollable').data('scrollable').seekTo($element.data('index'));
      },
      oncomplete: function($element) {
        $('.fade-white').mosaic();
        $('#holiday-market-theme').hideLoading();

        // TODO: how to check if the pagination link is clicked and not the nav one?
        if ($(this).hasClass('bullet'))
        {
          $.scrollTo('#holiday-market-body');
        }
      }
    });

    // initialize scrollable
    $("#scrollable").scrollable({
      next: '.arrow-next',
      prev: '.arrow-previous',
      circular: false,
      onBeforeSeek: function(e, i)
      {
        $('li', '#scrollable').each(function(index)
        {
          $(this).toggleClass('active', $('a', $(this)).data('index') == i);
        });

        if (0 == i)
        {
          $('.arrow-previous', '#holiday-market-body').hide();
        }
        else
        {
          $('.arrow-previous', '#holiday-market-body').show();
        }

        if ($('li', '#scrollable').length - 5 <= i)
        {
          $('.arrow-next', '#holiday-market-body').hide();

          return false;
        }
        else
        {
          $('.arrow-next', '#holiday-market-body').show();
        }

        return true;
      }
    }).navigator();
  });
</script>

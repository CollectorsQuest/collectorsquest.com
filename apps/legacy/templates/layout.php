<?php
/**
 * @var $sf_user cqUser
 */
?>

<?php include_partial('global/layout-header'); ?>

<div class="clear"></div>
<div id="page" class="span-25 rounded-top last <?php echo (has_component_slot('sidebar')) ? 'with-sidebar' : (has_component_slot('right-ads') ? 'with-right-ads' : ''); ?>">
  <?php
    $columns = 25;
    $columns -= (has_component_slot('sidebar')) ? 6 : 0;
    $columns -= (has_component_slot('right-ads')) ? 4 : 0;
  ?>
  <div id="contents" class="span-<?php echo $columns; ?>">
    <?php if ($sf_user->hasFlash('error')): ?>
      <div class="ui-state-error ui-corner-tl span-19 last">
        <table style="margin: 0;">
          <tr>
            <td style="white-space: nowrap; width: 70px; vertical-align: top; padding: 0;">
              <span class="ui-icon ui-icon-alert" style="float: left; margin-right: 5px; margin-top: 3px;">&nbsp;</span>
              <strong style="font-variant: small-caps;"><?= __('Error:', array(), 'flash'); ?></strong>&nbsp;&nbsp;
            </td>
            <td style=" vertical-align: top; padding: 1px 0 0 0;"><?= has_slot('flash_error') ? get_slot('flash_error') : implode('<br/><br/>', array_filter((array) $sf_user->getFlash('error', null, true))); ?></td>
          </tr>
        </table>
      </div>
      <div class="clear">&nbsp;</div>
    <?php elseif ($sf_user->hasFlash('success')): ?>
      <div class="ui-state-highlight ui-corner-tl span-19 last">
        <span class="ui-icon ui-icon-lightbulb" style="float: left; margin-right: 5px;"></span>
        <strong style="font-variant: small-caps;"><?= __('Success:'); ?></strong>&nbsp;
        <?= $sf_user->getFlash('success', null, true); ?>
      </div>
      <div class="clear">&nbsp;</div>
    <?php elseif ($sf_user->hasFlash('highlight')): ?>
      <div class="ui-state-highlight ui-corner-tl span-19 last">
        <span class="ui-icon ui-icon-lightbulb" style="float: left; margin-right: 5px; margin-top: 2px;"></span>
        <strong style="font-variant: small-caps;"><?= __('Notice:'); ?></strong>&nbsp;
        <?= $sf_user->getFlash('highlight', null, true); ?>
      </div>
      <div class="clear">&nbsp;</div>
    <?php endif; ?>

    <?php echo $sf_content ?>
  </div>
  <?php if (has_component_slot('sidebar')): ?>
    <div id="sidebar" class="span-6 <?php echo (!has_component_slot('right-ads')) ? 'last' : '' ?>">
      <?php echo get_component_slot('sidebar'); ?>
    </div>
  <?php endif; ?>
  <?php if (has_component_slot('right-ads')): ?>
    <div id="right-ads" class="span-4 last" style="text-align: center;">
      <?php echo get_component_slot('right-ads'); ?>
    </div>
  <?php endif; ?>
</div>

<?php include_partial('global/layout-footer'); ?>

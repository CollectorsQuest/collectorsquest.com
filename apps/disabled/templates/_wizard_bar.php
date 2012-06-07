<div id="wizard" style="padding: 10px 0 10px 15px;">
<?php for ($i=1; $i<=count($steps); $i++): ?>
  <div class="wizard <?php echo ($active == $i)?'active':''; ?> <?php echo (1 == $i)?'first': ((count($steps) == $i)?'last':''); ?>" style="width: <?php echo floor(100 / count($steps) - 8); ?>%">
    <?php echo sprintf('Step %d: %s', $i, $steps[$i]); ?>
  </div>
  <?php if ($i != count($steps)): ?>
    <span class="wizard_separator">&nbsp;</span>
  <?php endif;?>
<?php endfor; ?>
</div>
<div class="clear" style="border-top: 1px dashed #C1C1C1; margin: 40px 10px 30px 15px;"></div>

<?php cq_javascript_tag(); ?>
<script type="text/javascript">
  $(document).ready(function()
  {
    $('#wizard .first').corner("round 7px left");
    $('#wizard .last').corner("round 7px right");
  });
</script>
<?php cq_end_javascript_tag(); ?>

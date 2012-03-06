<?php
  /**
   * @var $Collector Collector
   */

  $has_access = $Collector->getCqnextAccessAllowed();
  $has_access_message = 'Has access';
  $no_access_message = 'No access';

?>

<span class="label <?php echo $has_access ? 'label-success' : 'label-warning' ?>"><?php echo $has_access ? $has_access_message : $no_access_message ?></span>

<a href="<?php echo url_for('@collector_signup'); ?>" style="color: #666666;">
  <div style="float: left; margin-right: 30px;" class="choice_box rounded">
    <?= __('Are you a <br> COLLECTOR?'); ?>
    <br><br>
    <font size="2"><?= __('Click Here!'); ?></font>
  </div>
</a>
<a href="<?php echo url_for('@seller_signup'); ?>" style="color: #666666;">
  <div style="float: right;" class="choice_box rounded">
    <?= __('Are you a <br> SELLER?'); ?>
    <br><br>
    <font size="2"><?= __('Click Here!'); ?></font>
  </div>
</a>

<style type="text/css">
  .choice_box {
    width: 150px;
    text-align: center;
    font-size: 20px;
    background: #F8FBE5;
    padding: 30px;
  }

  .choice_box:hover {
    background: #368AA2;
    color: #fff;
    cursor: pointer;
  }
</style>

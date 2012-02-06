<?php /* @var $field sfFormField */ ?>
<h2 style="text-align: center;">Find the Best of What People are Collecting.</h2>
<p style="margin-left: 10px;">Sign up with <strong>Collectors' Quest</strong> for regular updates about the neatest new collectibles, as well as in-depth information about collecting classic items.</p>
<p style="margin-left: 10px;"><strong>Collectors' Quest</strong> is your one-stop shop to find out about the things you don't want to miss out on, from vintage ephemera and art to the coolest new superhero stuff. Follow the most current collecting trends and find out what your fellow collectors are passionate about by becoming part of our growing community!</p>
<form action="" method="post">
  <?php echo $form->renderHiddenFields() ?>
  <?php foreach ($form as $name => $field): ?>
    <?php if ($field->isHidden())
      continue; ?>
    <div class="span-5" style="text-align: right;">
      <?php echo $field->renderLabel() ?>
      <div style="color: #c00; font-style: italic;">(required)</div>
    </div>
    <div class="prepend-1 span-12 last">
      <div style="background: #E9E9E9; vertical-align: middle; width: 400px; padding: 5px;">
        <?php echo $field->render(array('style' => 'border: 1px solid #A7A7A7; font-size: 16px; height: 23px; width: 390px; padding: 4px; margin: 0;')) ?>
      </div>
      <?php if ($field->hasError()): ?>
        <?php echo $field->renderError() ?>
      <?php endif; ?>
    </div>
    <br clear="all" /><br />
  <?php endforeach; ?>
  <br />
  <div style="margin: 0 auto; width: 150px;">
    <button type="submit" class="submit"><span><span>Subscribe</span></span></button>
  </div>
  <div class="clearfix append-bottom"></div>
</form>
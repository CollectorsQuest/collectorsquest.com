<br/><br/>
<div class="row-fluid">
  <div class="span3" style="margin-left: 40px;">
    <h3>Checkout below <br/>without registering</h3>
    <small style="color: #999;">
      You will be able to create account at the end of the checkout process!
    </small>
  </div>
  <div class="span2" style="color: #999; font-size: 60px; font-weight: 700; padding: 45px 0;">OR</div>
  <div class="span6">
    <form action="<?= url_for('@login'); ?>" method="post">
      <div class="row-fluid">
        <h3>Login now to use your saved details:</h3>
        <br/>
        <div class="span5" style="margin-left: 0;">
          <?= $form['username']->renderLabel(); ?>
          <?= $form['username']->render(array('style' => 'width: 95%', 'placeholder' => '')); ?>
        </div>
        <div class="span4">
          <?= $form['password']->renderLabel('Password'); ?>
          <?= $form['password']->render(array('style' => 'width: 95%', 'placeholder' => '')); ?>
        </div>
        <div class="span3">
          <label>&nbsp;</label>
          <button type="submit" class="btn" value="Login" data-loading-text="loading...">
            <i class="icon-user"></i>&nbsp;Login
          </button>
        </div>
      </div>
      <input type="hidden" name="goto" value="<?= $sf_request->getUri(); ?>"/>
    </form>
  </div>
</div>

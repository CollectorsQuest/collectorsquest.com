<br/><br/>
<div class="row-fluid">
  <div class="span3" style="margin-left: 40px;">
    <h3 style="margin-bottom: 10px;">Checkout below <br/>without registering</h3>
    <small style="color: #999;">
      You will be able to create an account<br/>
      at the end of the checkout process!
    </small>
  </div>
  <div class="span2" style="color: #999; font-size: 60px; font-weight: 700; padding: 45px 0;">&nbsp;OR</div>
  <div class="span6">
    <form action="<?= url_for('@login', true); ?>" method="post">
      <div class="row-fluid">
        <h3>Login now to use your saved details:</h3>
        <br/>
        <div class="span5" style="margin-left: 0;">
          <?= $form['username']->renderLabel(); ?>
          <div class="input-prepend">
            <span class="add-on"><i class="icon-user"></i></span>
            <?= $form['username']->render(array('style' => 'width: 75%; margin-left: -4px;', 'placeholder' => '')); ?>
          </div>

        </div>
        <div class="span5">
          <?= $form['password']->renderLabel('Password'); ?>
          <div class="input-prepend">
            <span class="add-on"><i class="icon-asterisk"></i></span>
            <?= $form['password']->render(array('style' => 'width: 75%; margin-left: -4px;', 'placeholder' => '')); ?>
          </div>
        </div>
        <div class="span2">
          <label>&nbsp;</label>
          <button type="submit" class="btn" value="Login" data-loading-text="loading...">
            <i class="icon-signin"></i>&nbsp;Login
          </button>
        </div>
      </div>

      <?= $form->renderHiddenFields(); ?>
    </form>
  </div>
</div>

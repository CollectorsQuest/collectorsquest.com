<?php use_javascripts_for_form($form); ?>

<div class="span6">
  <h3>Why Should You Join?</h3>
  <dl>
    <dt>Show Off Your Collection</dt>
    <dd>
      You bought it, now brag about it. Let other collectors see what's hiding in your closets.
      Or use as a a digital archive on the go so you don't buy the same thing twice!
      (It has happened to all of us).
    </dd>
    <dt>Meet Other Collectors</dt>
    <dd>
      Find other like-minded collectors or branch out and meet new friends
      who can show you a cool thing or two.
    </dd>
    <dt>Hear about the Latest Trends in Collecting</dt>
    <dd>
      If it's out there, you'll hear about it here. Daily blog and video coverage on the
      latest and greatest out in the collecting universe.
    </dd>
  </dl>
</div>

<div class="span6">
  <?= form_tag('@collector_signup', array('class' => 'form-horizontal')) ?>
  <fieldset>
    <?= $form ?>
    <div class="form-actions">
      <input type="submit" class="btn btn-primary" value="Submit" />
    </div>
  </fieldset>
  <?= '</form>' ?>
</div>

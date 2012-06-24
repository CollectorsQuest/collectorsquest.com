<?php
cq_page_title(
  'Join us!', null,
  array('class' => 'row-fluid header-bar spacer-bottom-25')
);
?>

<?php use_javascripts_for_form($form); ?>

<div class="row-fluid">
  <div class="span4 signup-text-bg">
    <h3>Why Should You Join?</h3>
    <dl class="signup-text">
      <dt>Show off your collection</dt>
      <dd>
        You bought it, now brag about it! Let other collectors see what's
        hiding in your closets, or create a digital archive to use on the
        go so you don't buy the same thing twice! (It's happened to all of us).
      </dd>
      <dt>Meet other collectors</dt>
      <dd>
        Find other like-minded collectors or branch out and meet new
        friends who can show you a cool thing or two.
      </dd>
      <dt>Hear about the latest trends in Collecting</dt>
      <dd>
        If it's out there, you'll hear about it here in our daily blog
        and video coverage of the latest and greatest from the collecting universe.
      </dd>
    </dl>
  </div>
  <div class="span8">
    <?= form_tag('@collector_signup', array('class' => 'form-horizontal')) ?>
    <fieldset>
      <?= $form ?>
      <div class="form-actions">
        <input type="submit" class="btn btn-primary" value="Submit" />
      </div>
    </fieldset>
    <?= '</form>' ?>
  </div>
</div>



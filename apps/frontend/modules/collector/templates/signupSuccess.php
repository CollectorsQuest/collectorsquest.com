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
      <dt>Show off your collection!</dt>
      <dd>
        You found it, now brag about it! Let other members see what's hiding in your closets,
        or create a digital archive so you don't buy the same thing twice!
      </dd>
      <dt>Sell your antiques and collectibles!</dt>
      <dd>
        Whether you're a professional seller or just a collector with a few things to sell,
        we have a plan that's right for you. We match your sale items with related items and
        posts across the site, so everyone will see what you have to offer!
      </dd>
      <dt>Meet other members!</dt>
      <dd>
        Find other like-minded collectors or branch out and meet new friends
        who can show you a cool thing or two.
      </dd>
      <dt>Hear about news and trends in the collecting world!</dt>
      <dd>
        If it's out there, you'll hear about it here. Our daily blog and video features
        cover the latest and greatest from the collecting universe.
      </dd>
    </dl>
  </div>
  <div class="span8">
    <fieldset class="rpxnow-login clearfix text-center" id="rpx-login">
      <iframe
        src="<?= $rpxnow['application_domain']; ?>openid/embed?token_url=<?= url_for('@rpx_token', true); ?>"
        scrolling="no" frameBorder="no" style="width:350px; height:217px;" width="350" height="217">
      </iframe>

      <br/>
      <hr/>
      <div style="background: #fff; margin: auto; margin-top: -29px; width: 50px; text-align: center; font-size: 150%;">
        OR
      </div>
    </fieldset>

    <?= form_tag('@collector_signup', array('class' => 'form-horizontal')) ?>
    <fieldset>
      <?= $form ?>
      <div class="form-actions">
        <input type="submit" class="btn btn-primary" value="Sign Up" />
      </div>
    </fieldset>

    <?= cqStatic::getAyahClient()->getPublisherHTML(); ?>
    <?= '</form>' ?>
  </div>
</div>

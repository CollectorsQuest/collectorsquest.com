<?php /* @var $shopping_payment ShoppingPayment */ ?>
<br/>
<h1 style="font-size: 250%;">There was an error processing payment.</h1>

<br/>
<h2 style="color: #877; line-height: 26px; margin-top: 10px;">
  <?php
    foreach ($shopping_payment->getPayPalErrors() as $error)
    {
      echo $error['Message'].'<br />';
    }
  ?>
</h2>

<br/>
<section class="404">
  <p>
    Please <?= link_to('let us know', 'blog_page', array('slug' => 'contact-us')); ?>
    about this error and we'll try to fix it.
  </p>
  <p>Or you can go back to your <?= link_to('shopping cart', '@shopping_cart') ?> and try to checkout again.</p>
</section>

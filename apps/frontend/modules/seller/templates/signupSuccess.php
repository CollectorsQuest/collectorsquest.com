<?php cq_page_title('Sell Your Antique, Vintage and Newer Collectibles with Us!'); ?>


<div class="marketplace-buy-a-package">
  <?= image_tag('frontend/cq_market_promo_collectibles_header.jpg', array(
      'alt' => "Promotional collectibles photos",
      'class' => 'header-image',
      'width' => 939,
      'height' => 280,
  )); ?>

  <div class="row-fluid info-box-wrap">
    <div class="span6 info-box">
      <h3 class="Chivo webfont">Why sell with us?</h3>
      <ul class="unstyled">
        <li><i class="small-circle-1 ir">1</i> We are an active community of collectors.<br />
                                               What better place to sell your collectibles?</li>
        <li><i class="small-circle-2 ir">2</i> The number one question we get asked is<br />
                                               &ldquo;Where can I buy that?&rdquo;</li>
        <li><i class="small-circle-3 ir">3</i> No transaction fees &mdash;<br />
                                               pay for your listings only. </li>
        <li><i class="small-circle-4 ir">4</i> It's easy to do!<br />
                                               Get started in minutes.</li>
      </ul>

      <?= link_to('Sign Up Now!', '@misc_guide_to_collecting', array(
          'class' => 'btn-signup Chivo btn-position',
      )); ?>
    </div>

    <div class="span6 info-box">
      <div class="row-fluid">
        <div class="span8">
          <h3 class="Chivo webfont">Price Points</h3>
          <table cellspacing="0" class="table">
            <thead class="Chivo webfont">
              <tr>
                <th>Listings</th>
                <th>Price</th>
                <th>Per Item</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1</td>
                <td>$2.50</td>
                <td>$2.50</td>
              </tr>
              <tr>
                <td>10</td>
                <td>$20</td>
                <td>$2.00</td>
              </tr>
              <tr>
                <td>100</td>
                <td>$150</td>
                <td>$1.50</td>
              </tr>
              <tr>
                <td>UNLIMITED</td>
                <td>$250</td>
                <td>pennies</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="span4">
          <?= image_tag('frontend/cq_market_package_discounts.png', array(
              'alt' => 'Package discounts for multiple listings',
              'width' => 122,
              'height' => 121,
          )); ?>
          <br />
          <p>Listings last for<br/> six months</p>
          <p>Unlimited listings<br /> last one year</p>
        </div>
      </div>
    </div>
  </div>

  <div class="row-fluid questions">
    <div class="question pull-left clearfix">
      <h3 class="Chivo webfont clearfix">
        <i class="small-circle-questionmark"></i>
        How do fees work<br /> on CQ?
      </h3>
      <p>
        On Collectors Quest, you’re only charged a flat fee per listing.
      </p>
      <p>
        CQ provides multiple listing packages for different levels of sellers, from the casual seller to the very active seller. Once you purchase a listing, it’s live on the site for six months, or until your item sells—whichever comes first.
      </p>
      <p>
        You’ll never be charged any final value fees or any other kind of additional fee. You only pay for your original listing, and nothing more. No fancy math necessary!
      </p>
    </div>

    <div class="question pull-left clearfix">
      <h3 class="Chivo webfont clearfix">
        <i class="small-circle-questionmark"></i>
        What do I need<br /> to sign up?
      </h3>
      <p>
        All sellers on Collectors Quest need a valid email address, and a PayPal account to receive payments.
      </p>
      <p>
        Items sold on CQ are processed through PayPal, so payments for these items will go into your PayPal account.
      </p>
      <p>
        You can pay for your listing credits with either PayPal or a credit card.
      </p>
    </div>

    <div class="question pull-left clearfix">
      <h3 class="Chivo webfont clearfix">
        <i class="small-circle-questionmark"></i>
        What happens when I<br /> sell an item on CQ?
      </h3>
      <p>
        When a buyer decides to purchase an item, the buyer has with multiple payment options with PayPal.
      </p>
      <p>
        After providing shipping information, payment information and any special instructions, payment is processed immediately, and both the buyer and seller will be sent a notification via email with all of this information, as well as a link to access this information on CollectorsQuest.com.
      </p>
      <p>
        You ship the item as soon as you can, and the rest is history!
      </p>
    </div>
  </div>

  <!-- copy structure from above -->
  <div class="row-fluid questions">
    <div class="question pull-left clearfix">
      <br />
      <!-- empty first col -->
    </div>
    <div class="question pull-left clearfix">
      <?= link_to('Sign Up Now!', '@misc_guide_to_collecting', array(
          'class' => 'btn-signup Chivo',
      )); ?>
    </div>
  </div>

  <br />
  <br />
  <?php cq_page_title('Checo out our Guides on Selling!'); ?>
  <br />

  <div class="selling-guides text-center row-fluid">
    <div class="span4">
      <?= link_to(
          image_tag('frontend/cq_market_guide_images.jpg', array(
              'width' => 305,
              'height' => 205,
          )) . 'Guide to Photo Uploading and Editing',
          '/pages/cq-faqs/guide-selling/'
      ); ?>
    </div>
    <div class="span4">
      <?= link_to(
          image_tag('frontend/cq_market_guide_shipping.jpg', array(
              'width' => 305,
              'height' => 205,
          )) . 'Guide to Smart Shiping',
          '/pages/cq-faqs/guide-to-smart-shipping/'
      ); ?>
    </div>
    <div class="span4">
      <?= link_to(
          image_tag('frontend/cq_market_guide_selling.jpg', array(
              'width' => 305,
              'height' => 205,
          )) . 'Guide to Selling',
          '/pages/cq-faqs/how-to-be-a-good-seller/'
      ); ?>
    </div>
  </div>
  <br />
</div>

<div class="packages-container spacer-top-25">
  <div class="bars-wrapper">
    <div class="row-fluid">
      <div class="span3 columns">
        <div class="package-bar package1">
          <span class="Chivo webfont">
            1 listing
          </span>
          <div class="price red">
            $2.50
            <span class="desc">
              $2.50 per listing
            </span>
          </div>
        </div>
      </div>
      <div class="span3 columns">
        <div class="package-bar package2">
          <span class="Chivo webfont">
            10 listings
          </span>
          <div class="price red">
            $20.00
            <span class="desc">
              $2.00 per listing
            </span>
          </div>
        </div>
      </div>
      <div class="span3 columns">
        <div class="package-bar package3">
          <span class="Chivo webfont">
            100 listings
          </span>
          <div class="price red">
            $150.00
            <span class="desc">
              $1.50 per listing
            </span>
          </div>
        </div>
      </div>
      <div class="span3 columns">
        <div class="package-bar package4">
          <span class="Chivo webfont red">
            <strong>UNLIMITED</strong>
          </span>
          <span class="Chivo webfont desc-red">
            LISTINGS
          </span>
          <span class="desc-blue">
            for one year
          </span>
          <div class="price red">
            $250.00
            <span class="desc">
              unlimited items
            </span>
          </div>
        </div>
      </div>
    </div>
    <div class="row-fluid spacer-top-20">
      <div class="span3 text-center">
        <?= link_to('Buy Now', '@seller_packages?package=1', array('class' => 'btn btn-primary')) ?>
      </div>
      <div class="span3 text-center">
        <?= link_to('Buy Now', '@seller_packages?package=2', array('class' => 'btn btn-primary')) ?>
      </div>
      <div class="span3 text-center">
        <?= link_to('Buy Now', '@seller_packages?package=3', array('class' => 'btn btn-primary')) ?>
      </div>
      <div class="span3 text-center">
        <?= link_to('Buy Now', '@seller_packages?package=6', array('class' => 'btn btn-primary')) ?>
      </div>
    </div>
  </div>
  <div class="wrapper-bottom">
    <?php
    cq_sidebar_title(
      'Why Sell on Collectors Quest?', null,
      array('class' => 'row-fluid section-title-yellow spacer-bottom')
    );
    ?>
    <div class="row-fluid">
      <div class="span4">
        <h4 class="Chivo webfont">Broader Exposure<br/>&nbsp;</h4>
        <p>
          Have your sale items paired with related and relevant content across the site.
          Check out any page on the site and you'll see!
        </p>
      </div>
      <div class="span4">
        <h4 class="Chivo webfont">Flat Rate with<br> No Transaction Fees</h4>
        <p>
          No fancy math needed! Annual subscribers can sell as many items as
          they'd like at no additional cost.
        </p>
      </div>
      <div class="span4">
        <h4 class="Chivo webfont">List for Six Months<br/>&nbsp;</h4>
        <p>
          Buy Credits that last for one full year. Once an item is marked for sale,
          it remains in the Market Place for up to 6 months (or until sold).
        </p>
      </div>
    </div>
  </div>
</div>

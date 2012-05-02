<?php
include dirname(__FILE__) . '/../../bootstrap/frontend.php';

cqTest::resetClasses(array('Collector', 'Package', 'PackageTransaction', 'Promotion', 'PromotionTransaction'));
cqTest::loadFixtures(array('01_test_collectors/'));

$t       = new lime_test(null, array(
  'output'          => new lime_output_color(),
  'error_reporting' => true
));
$browser = new cqTestFunctional(new sfBrowser(), $t);

$formName = 'packages';

$browser->
    info(' 1.1. Restricted to registered')->
    get('/seller/packages')->

    with('response')->begin()->
    isStatusCode(401)->
    end()->

    loginNext('ivan.tanev', 'ivanpass');

$browser->
    info(' 1.2. All required fields present')->
    get('/seller/packages')->

    with('request')->begin()->
    isParameter('module', 'seller')->
    isParameter('action', 'packages')->
    end()->

    with('response')->begin()->
    isStatusCode(200)->
    checkElement('form input[name="packages[package_id]"]', true)->
    checkElement('form input[name="packages[promo_code]"]', true)->
    checkElement('form input[name="packages[payment_type]"]', true)->
    checkElement('form input[name="packages[terms]"]', true)->
    checkElement('form select[name="packages[cc_type]"]', true)->
    checkElement('form input[name="packages[cc_number]"]', true)->
    checkElement('form select[name="packages[expiry_date][month]"]', true)->
    checkElement('form select[name="packages[expiry_date][year]"]', true)->
    checkElement('form input[name="packages[cvv_number]"]', true)->
    checkElement('form input[name="packages[first_name]"]', true)->
    checkElement('form input[name="packages[last_name]"]', true)->
    checkElement('form input[name="packages[street]"]', true)->
    checkElement('form input[name="packages[city]"]', true)->
    checkElement('form input[name="packages[state]"]', true)->
    checkElement('form input[name="packages[city]"]', true)->
    checkElement('form select[name="packages[country]"]', true)->
    checkElement('form input[type="submit"]', true)->
    end();

$browser->info('2. Promotions');

//2.1 When apply promo code should get discount message
$browser->
    info(' 2.1. Fixed price promo discount')->
    get('/seller/packages')->
    with('form')->begin()->
    setFormField($formName, 'package_id', 2)->
    setFormField($formName, 'promo_code', 'CQ2012-FIX')->
    click('Apply')->
    end()->

    with('form')->begin()->
    hasErrors(false)->
    end()->

    with('response')->begin()->
    isStatusCode(200)->
    checkElement('body', '#10\$ discount#')->
    end();

$t->todo(' 2.2 Percent promo discount');
$t->todo(' 2.3 Invalid promo code gives error');
$t->todo(' 2.4 Exhausted uses gives error');
$t->todo(' 2.5 Expired promo code gives error');

$t->info(' 3. Orders');
$t->todo(' 3.1 When make successful order should get a record in package_transaction');
$browser->
    info(' 3.2 When choose first package should receive an order for 2.50')->
    get('/seller/packages')->
    with('form')->begin()->
    setFormField($formName, 'package_id', 2)->
    setFormField($formName, 'payment_type', 'paypal')->
    setFormField($formName, 'terms', true)->
    click('Sign up')->
    end()->

    with('response')->begin()->
    checkElement('form[name="frmpaypal"]')->
    end();

$t->todo(' 3.3 When use promo code should get promotion_transaction record');
$t->todo(' 3.4 Use package for 11.25 and discount of 10 - should have transaction of 1.25 and promo transaction');
$t->todo(' 3.5 When use promo code should substract from promo code count avail');
$browser->
    info(' 3.6 Payment with credit card')->
    get('/seller/packages')->
    with('response')->begin()->
    click('Sign up', array(
  'packages'=> array(
    'package_id'  => 1,
    'payment_type'=> 'cc',
    'cc_type'     => 'Visa',
    'cc_number'   => '4111111111111111',
    'expiry_date' => array(
      'month'=> 05,
      'year' => 2016
    ),
    'cvv_number'  => 123,
    'first_name'  => 'Test',
    'last_name'   => 'Test',
    'street'      => 'Test street',
    'city'        => 'Test city',
    'state'       => 'AL',
    'zip'         => 10242,
    'country'     => 'US',
    'terms'       => true,
  )
))->
    end()->

    with('form')->begin()->
    hasErrors(false)->
    debug()->
    end();

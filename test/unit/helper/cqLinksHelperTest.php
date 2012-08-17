<?php

$app = 'frontend';
include(__DIR__ . '/../../bootstrap/functional.php');

/** @var $sf_configuration sfApplicationConfiguration */
$sf_configuration = sfApplicationConfiguration::getActive();
$sf_configuration->loadHelpers('cqLinks');

$t = new lime_test(null, array(
  'output'          => new lime_output_color(),
  'error_reporting' => true
));

$t->diag('cq_link_to()');

$t->is(
  cq_link_to('Home', '@homepage'),
  '<a href="http://www.example.com/">Home</a>'
);
$t->is(
  cq_link_to('Home', array('sf_route' => 'homepage')),
  '<a href="http://www.example.com/">Home</a>'
);
$t->is(
  cq_link_to('Home', '@homepage', array('class' => 'test')),
  '<a class="test" href="http://www.example.com/">Home</a>'
);
$t->is(
  cq_link_to('Messages', '@messages_inbox'),
  '<a class="requires-login" href="http://www.example.com/messages/inbox">Messages</a>'
);
$t->is(
  cq_link_to('Messages', '@messages_inbox', array('class' => 'test')),
  '<a class="test requires-login" href="http://www.example.com/messages/inbox">Messages</a>'
);
$t->is(
  cq_link_to('Messages', 'messages/inbox', array('class' => 'test')),
  '<a class="test requires-login" href="http://www.example.com/messages/inbox">Messages</a>'
);
$t->is(
  cq_link_to('Messages', array('sf_route' => 'messages_inbox'), array('class' => 'test')),
  '<a class="test requires-login" href="http://www.example.com/messages/inbox">Messages</a>'
);
$t->is(
  cq_link_to('Messages', array('sf_route' => 'messages_inbox'), array(
    'class'    => 'test',
    'absolute' => false
  )),
  '<a class="test requires-login" href="/messages/inbox">Messages</a>'
);

$t->diag('link_to_collector()');

cqTest::resetClasses(array('Collector'));

$collector = cqTest::getModelObject('Collector', false);

// Simple call
$t->is(
  link_to_collector($collector, 'text'),
  '<a title="Robotbacon" href="http://www.example.com/collector/1/robotbacon">Robotbacon</a>'
);

// Simple image call
$t->is(
  link_to_collector($collector, 'image'),
  '<a title="Robotbacon" href="http://www.example.com/collector/1/robotbacon"><img alt="Robotbacon" slug="robotbacon" src="//static.example.com/images/frontend/multimedia/Collector/100x100.png" /></a>'
);

// Call with absolute=false
$t->is(
  link_to_collector($collector, 'text', array('absolute'=> false)),
  '<a title="Robotbacon" href="/collector/1/robotbacon">Robotbacon</a>'
);

// Call with alt on $options - alt goes to image
$t->is(
  link_to_collector($collector, 'image', array('alt'=> 'test')),
  '<a title="Robotbacon" href="http://www.example.com/collector/1/robotbacon"><img alt="test" slug="robotbacon" src="//static.example.com/images/frontend/multimedia/Collector/100x100.png" /></a>'
);

// When image title should be only on <a>
$t->is(
  link_to_collector($collector, 'image', array('title'=> 'test')),
  '<a title="test" href="http://www.example.com/collector/1/robotbacon"><img alt="Robotbacon" slug="robotbacon" src="//static.example.com/images/frontend/multimedia/Collector/100x100.png" /></a>'
);

// When image used title is only on <a>, alt goes to <img>
$t->is(
  link_to_collector($collector, 'image', array('title' => 'test_title', 'alt' => 'test_alt')),
  '<a title="test_title" href="http://www.example.com/collector/1/robotbacon"><img alt="test_alt" slug="robotbacon" src="//static.example.com/images/frontend/multimedia/Collector/100x100.png" /></a>'
);

$t->is(
  link_to_collector($collector, 'image', array('title' => 'test'), array('alt' => 'test')),
  '<a title="test" href="http://www.example.com/collector/1/robotbacon"><img alt="test" slug="robotbacon" src="//static.example.com/images/frontend/multimedia/Collector/100x100.png" /></a>'
);

// Alt in $image_options overwrites this from $options
$t->is(
  link_to_collector($collector, 'image', array('title' => 'test', 'alt' => 'test_options'), array('alt' => 'test_image')),
  '<a title="test" href="http://www.example.com/collector/1/robotbacon"><img alt="test_image" slug="robotbacon" src="//static.example.com/images/frontend/multimedia/Collector/100x100.png" /></a>'
);



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
  '<a href="http://www.example.org/">Home</a>'
);
$t->is(
  cq_link_to('Home', array('sf_route' => 'homepage')),
  '<a href="http://www.example.org/">Home</a>'
);
$t->is(
  cq_link_to('Home', '@homepage', array('class' => 'test')),
  '<a class="test" href="http://www.example.org/">Home</a>'
);
$t->is(
  cq_link_to('Messages', '@messages_inbox'),
  '<a class="requires-login" href="http://www.example.org/messages/inbox">Messages</a>'
);
$t->is(
  cq_link_to('Messages', '@messages_inbox', array('class' => 'test')),
  '<a class="test requires-login" href="http://www.example.org/messages/inbox">Messages</a>'
);
$t->is(
  cq_link_to('Messages', 'messages/inbox', array('class' => 'test')),
  '<a class="test requires-login" href="http://www.example.org/messages/inbox">Messages</a>'
);
$t->is(
  cq_link_to('Messages', array('sf_route' => 'messages_inbox'), array('class' => 'test')),
  '<a class="test requires-login" href="http://www.example.org/messages/inbox">Messages</a>'
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
  link_to_collector($collector),
  '<a title="Poptart" href="http://www.example.org/collector/1/poptart">Poptart</a>'
);

// Simple call
$t->is(
  link_to_collector($collector, 'text'),
  '<a title="Poptart" href="http://www.example.org/collector/1/poptart">Poptart</a>'
);

// Simple image call
$t->is(
  link_to_collector($collector, 'image'),
  '<a title="Poptart" href="http://www.example.org/collector/1/poptart"><img alt="Poptart" slug="poptart" src="//static.example.com/images/frontend/multimedia/Collector/100x100.png" /></a>'
);

// Call with absolute=false
$t->is(
  link_to_collector($collector, 'text', array('absolute'=> false)),
  '<a title="Poptart" href="/collector/1/poptart">Poptart</a>'
);

// Call with alt on $options - alt goes to image
$t->is(
  link_to_collector($collector, 'image', array('image_tag' => array('alt'=> 'test'))),
  '<a title="Poptart" href="http://www.example.org/collector/1/poptart"><img alt="test" slug="poptart" src="//static.example.com/images/frontend/multimedia/Collector/100x100.png" /></a>'
);

// When image title should be only on <a>
$t->is(
  link_to_collector($collector, 'image', array('link_to' => array('title'=> 'test'))),
  '<a title="test" href="http://www.example.org/collector/1/poptart"><img alt="Poptart" slug="poptart" src="//static.example.com/images/frontend/multimedia/Collector/100x100.png" /></a>'
);

// When image used title is only on <a>, alt goes to <img>
$t->is(
  link_to_collector($collector, 'image', array(
    'link_to' => array('title' => 'test_title'),
    'image_tag' => array('alt' => 'test_alt')
  )),
  '<a title="test_title" href="http://www.example.org/collector/1/poptart"><img alt="test_alt" slug="poptart" src="//static.example.com/images/frontend/multimedia/Collector/100x100.png" /></a>'
);

$t->is(
  link_to_collector($collector, 'image', array(
    'link_to' => array('title' => 'test'),
    'image_tag' => array('alt' => 'test')
  )),
  '<a title="test" href="http://www.example.org/collector/1/poptart"><img alt="test" slug="poptart" src="//static.example.com/images/frontend/multimedia/Collector/100x100.png" /></a>'
);

// Alt in $image_options overwrites this from $options
$t->is(
  link_to_collector($collector, 'image', array(
    'link_to' => array('title' => 'test', 'alt' => 'test_options'),
    'image_tag' => array('alt' => 'test_image')
  )),
  '<a title="test" href="http://www.example.org/collector/1/poptart"><img alt="test_image" slug="poptart" src="//static.example.com/images/frontend/multimedia/Collector/100x100.png" /></a>'
);

$t->diag('link_to_collectible()');

cqTest::resetClasses(array('Collectible'));

$collectible = cqTest::getModelObject('Collectible', false);

// Simple call with text option and empty array parameters
$t->is(
  link_to_collectible($collectible, 'text', array()),
  '<a title="GEM Razors voice-o-graph" href="/collectible/1/gem-razors-voice-o-graph">GEM Razors voice-o-graph</a>'
);

// Simple call with image option and no additional parameters
$t->is(
  link_to_collectible($collectible, 'image', array()),
  '<a title="GEM Razors voice-o-graph" href="/collectible/1/gem-razors-voice-o-graph"><img title="GEM Razors voice-o-graph" alt="GEM Razors voice-o-graph" width="150" height="150" src="//static.example.com/images/frontend/multimedia/Collectible/150x150.png" /></a>'
);

// Call with old function signature and text option
$t->is(
  link_to_collectible($collectible, 'text', array('width' => 140, 'height' => 140, 'class' => 'mosaic-backdrop')),
  '<a title="GEM Razors voice-o-graph" class="mosaic-backdrop" href="/collectible/1/gem-razors-voice-o-graph">GEM Razors voice-o-graph</a>'
);

// Call with new function signature and image option and "optimal" paramters
$t->is(
  link_to_collectible($collectible, 'image', array(
    'link_to' => array(),
    'image_tag' => array('width' => 140, 'height' => 140, 'class' => 'mosaic-backdrop')
  )),
  '<a title="GEM Razors voice-o-graph" href="/collectible/1/gem-razors-voice-o-graph"><img title="GEM Razors voice-o-graph" alt="GEM Razors voice-o-graph" width="140" height="140" class="mosaic-backdrop" src="//static.example.com/images/frontend/multimedia/Collectible/140x140.png" /></a>'
);

// Call with new function signature and text option and "optimal" parameters
$t->is(
  link_to_collectible($collectible, 'text', array(
    'link_to' => array(),
    'image_tag' => array('width' => 140, 'height' => 140, 'class' => 'mosaic-backdrop')
  )),
  '<a title="GEM Razors voice-o-graph" href="/collectible/1/gem-razors-voice-o-graph">GEM Razors voice-o-graph</a>'
);

// Call with text option and no additional parameters
$t->is(
  link_to_collectible($collectible, 'text'),
  '<a title="GEM Razors voice-o-graph" href="/collectible/1/gem-razors-voice-o-graph">GEM Razors voice-o-graph</a>'
);

// Call with image option and no additional parameters
$t->is(
  link_to_collectible($collectible, 'image'),
  '<a title="GEM Razors voice-o-graph" href="/collectible/1/gem-razors-voice-o-graph"><img title="GEM Razors voice-o-graph" alt="GEM Razors voice-o-graph" width="150" height="150" src="//static.example.com/images/frontend/multimedia/Collectible/150x150.png" /></a>'
);

$t->is(
  link_to_collectible($collectible, 'text', array(
    'width' => 140, 'height' => 140, 'max_height' => 100, 'max_width' => 80, 'class' => 'mosaic-backdrop'
  )),
  '<a title="GEM Razors voice-o-graph" class="mosaic-backdrop" href="/collectible/1/gem-razors-voice-o-graph">GEM Razors voice-o-graph</a>'
);

$t->is(
  link_to_collectible($collectible, 'image', array(
    'width' => 140, 'height' => 140, 'max_height' => 100, 'max_width' => 80, 'class' => 'mosaic-backdrop'
  )),
  '<a title="GEM Razors voice-o-graph" class="mosaic-backdrop" href="/collectible/1/gem-razors-voice-o-graph"><img title="GEM Razors voice-o-graph" alt="GEM Razors voice-o-graph" width="140" height="140" max_height="100" max_width="80" class="mosaic-backdrop" src="//static.example.com/images/frontend/multimedia/Collectible/140x140.png" /></a>'
);

$t->diag('link_to_collection()');

cqTest::resetClasses(array('Collection'));

$collection = cqTest::getModelObject('Collection', false);

// Simple call with text option and no additional parameters
$t->is(
  link_to_collection($collection, 'text'),
  '<a title="Voice-O-Graph Recordings" href="/collection/1/voice-o-graph-recordings">Voice-O-Graph Recordings</a>'
);

$t->diag('link_to_collection($collection, \'image\') => throws error');
/* we have an error here!
// Simple call with image option and no additional parameters
$t->is(
  link_to_collection($collection, 'image'),
  '<a title="Voice-O-Graph Recordings" href="/collection/1/voice-o-graph-recordings">Voice-O-Graph Recordings</a>'
);

// Simple call with text option and empty array parameter
$t->is(
  link_to_collection($collection, 'text', array()),
  '<a title="Voice-O-Graph Recordings" href="/collection/1/voice-o-graph-recordings">Voice-O-Graph Recordings</a>'
);

// Simple call with image option and empty array parameter
$t->is(
  link_to_collection($collection, 'image', array('width' => 140, 'height' => 140, 'class' => 'mosaic-backdrop')),
  '<a title="Voice-O-Graph Recordings" href="/collection/1/voice-o-graph-recordings">Voice-O-Graph Recordings</a>'
);
*/

$t->diag('link_to_blog_author()');

cqTest::resetClasses(array('wpUser'));

$blog_author = cqTest::getModelObject('wpUser', false);

// Simple call with no additional parameters
$t->is(
  link_to_blog_author($blog_author),
  '<a title="Collectors Quest" href="/blog/author/admin/">Collectors Quest</a>'
);

// Simple call with text option and no additional parameters
$t->is(
  link_to_blog_author($blog_author, 'text'),
  '<a title="Collectors Quest" href="/blog/author/admin/">Collectors Quest</a>'
);

// Simple call with image option and no additional parameters
$t->is(
  link_to_blog_author($blog_author, 'image'),
  '<a title="Collectors Quest" href="/blog/author/admin/"><img width="150" height="150" alt="Collectors Quest" title="Collectors Quest" src="//static.example.com/images/blog/avatar-collectors-quest" /></a>'
);

// Simple call with text option and empty array parameter
$t->is(
  link_to_blog_author($blog_author, 'text', array()),
  '<a title="Collectors Quest" href="/blog/author/admin/">Collectors Quest</a>'
);

// Simple call with image option and empty array parameter
$t->is(
  link_to_blog_author($blog_author, 'image', array()),
  '<a title="Collectors Quest" href="/blog/author/admin/"><img width="150" height="150" alt="Collectors Quest" title="Collectors Quest" src="//static.example.com/images/blog/avatar-collectors-quest" /></a>'
);

// call with text option and array parameter with link_to options
$t->is(
  link_to_blog_author($blog_author, 'text', array(
    'link_to' => array('title' => 'test_title', 'max_height' => 200)
  )),
  '<a title="test_title" href="/blog/author/admin/">Collectors Quest</a>'
);

// call with image option and array parameter with link_to options
$t->is(
  link_to_blog_author($blog_author, 'image', array(
    'link_to' => array('title' => 'test_title', 'max_height' => 200)
  )),
  '<a title="test_title" href="/blog/author/admin/"><img width="150" height="150" alt="Collectors Quest" title="Collectors Quest" src="//static.example.com/images/blog/avatar-collectors-quest" /></a>'
);

// call with text option and array parameter with image_tag options
$t->is(
  link_to_blog_author($blog_author, 'text', array(
    'image_tag' => array('title' => 'test_title', 'max_height' => 200, 'width' => '160', 'height' => '160')
  )),
  '<a title="Collectors Quest" href="/blog/author/admin/">Collectors Quest</a>'
);

// call with image option and array parameter with image_tag options
$t->is(
  link_to_blog_author($blog_author, 'image', array(
    'image_tag' => array('title' => 'test_title', 'max_height' => 200, 'width' => '160', 'height' => '160')
  )),
  '<a title="Collectors Quest" href="/blog/author/admin/"><img width="160" height="160" alt="Collectors Quest" title="test_title" max_height="200" src="//static.example.com/images/blog/avatar-collectors-quest" /></a>'
);

// call with text option and array parameter with all options
$t->is(
  link_to_blog_author($blog_author, 'text', array(
    'link_to' => array('title' => 'test_title', 'max_height' => 200, 'width' => '160', 'height' => '160'),
    'image_tag' => array('title' => 'test_title', 'max_height' => 200, 'width' => '160', 'height' => '160')
  )),
  '<a title="test_title" href="/blog/author/admin/">Collectors Quest</a>'
);

// call with image option and array parameter with all options
$t->is(
  link_to_blog_author($blog_author, 'image', array(
    'link_to' => array('title' => 'test_title', 'max_height' => 200, 'width' => '160', 'height' => '160'),
    'image_tag' => array('title' => 'test_title', 'max_height' => 200, 'width' => '160', 'height' => '160')
  )),
  '<a title="test_title" href="/blog/author/admin/"><img width="160" height="160" alt="Collectors Quest" title="test_title" max_height="200" src="//static.example.com/images/blog/avatar-collectors-quest" /></a>'
);

// call with text option and old function signature with one dimensional array
$t->is(
  link_to_blog_author($blog_author, 'text', array(
      'title' => 'test_title', 'max_height' => 200, 'width' => '160', 'height' => '160')
  ),
  '<a title="test_title" href="/blog/author/admin/">Collectors Quest</a>'
);

// call with image option and old function signature with one dimensional array
$t->is(
  link_to_blog_author($blog_author, 'image', array(
      'title' => 'test_title', 'max_height' => 200, 'width' => '160', 'height' => '160')
  ),
  '<a title="test_title" href="/blog/author/admin/"><img width="160" height="160" alt="Collectors Quest" title="test_title" max_height="200" src="//static.example.com/images/blog/avatar-collectors-quest" /></a>'
);

/*
$t->diag('link_to_content_category()');

cqTest::resetClasses(array('ContentCategory'));

$content_category = cqTest::getModelObject('ContentCategory', false);

// Simple call with text option and no additional parameters
$t->is(
  link_to_blog_author($content_category, 'text'),
  '<a title="Collectors Quest" href="/blog/author/admin/">Collectors Quest</a>'
);

// Simple call with image option and no additional parameters
$t->is(
  link_to_blog_author($content_category, 'image'),
  '<a title="Collectors Quest" href="/blog/author/admin/"><img width="150" height="150" alt="Collectors Quest" title="Collectors Quest" src="//static.example.com/images/blog/avatar-collectors-quest" /></a>'
);
*/

$t->diag('link_to_blog_post()');

cqTest::resetClasses(array('wpPost'));

$blog_post = cqTest::getModelObject('wpPost', false);

// Simple call with no additional parameters
$t->is(
  link_to_blog_post($blog_post),
  '<a title="VanRaalteWeddingFlounceBlack.jpg" href="http://example.com/blog/2006/02/20/vanraalteweddingflounceblackjpg">VanRaalteWeddingFlounceBlack.jpg</a>'
);


// Simple call with text option and no additional parameters
$t->is(
  link_to_blog_post($blog_post, 'text'),
  '<a title="VanRaalteWeddingFlounceBlack.jpg" href="http://example.com/blog/2006/02/20/vanraalteweddingflounceblackjpg">VanRaalteWeddingFlounceBlack.jpg</a>'
);

// Simple call with image option and no additional parameters
$t->is(
  link_to_blog_post($blog_post, 'image'),
  null
);

// call with text option and old function signature with one dimensional array
$t->is(
  link_to_blog_post($blog_post, 'text', array(
    'width' => '200', 'height' => '200', 'alt' => 'test_alt', 'title' => 'test_title'
  )),
  '<a title="test_title" href="http://example.com/blog/2006/02/20/vanraalteweddingflounceblackjpg">VanRaalteWeddingFlounceBlack.jpg</a>'
);

// call with text option and array parameter with all options
$t->is(
  link_to_blog_post($blog_post, 'text', array(
    'link_to' => array('title' => 'test_title', 'max_height' => 200, 'width' => '160', 'height' => '160'),
    'image_tag' => array('title' => 'test_title', 'max_height' => 200, 'width' => '160', 'height' => '160')
  )),
  '<a title="test_title" href="http://example.com/blog/2006/02/20/vanraalteweddingflounceblackjpg">VanRaalteWeddingFlounceBlack.jpg</a>'
);

$t->diag('link_to_collector()');

cqTest::resetClasses(array('Collector'));

$collector = cqTest::getModelObject('Collector', false);

// Simple call with no additional parameters
$t->is(
  link_to_collector($collector),
  '<a title="Poptart" href="http://www.example.org/collector/1/poptart">Poptart</a>'
);

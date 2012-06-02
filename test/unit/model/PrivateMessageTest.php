<?php

include(__DIR__.'/../../bootstrap/model.php');

$t = new lime_test(10, array('output' => new lime_output_color(), 'error_reporting' => true));

cqTest::resetClasses(array('PrivagteMessage'));

$t->diag('::preSave()');
  $message = new PrivateMessage();
  $message->save();
  $t->isa_ok($message->getThread(), 'string',
    '::preSave() sets a random thread identifier if none provided');
  $t->is(strlen($message->getThread()), 32,
    '::preSave() sets a random thread identifier if none provided');

  $message = new PrivateMessage();
  $message->setThread('testytestidentifieryeah');
  $message->save();
  $t->is('testytestidentifieryeah', $message->getThread(),
    '::preSave() does not overwrite the thread identifier if already set');


$t->diag('::getReplySubject()');

  $message = new PrivateMessage();

  $message->setSubject('Regarding your offer');
  $t->is($message->getReplySubject(), 'RE: Regarding your offer');

  $message->setSubject('RE: RE: Regarding your offer');
  $t->is($message->getReplySubject(), 'RE: Regarding your offer');

  $message->setSubject('RE: Re: Regarding your offer');
  $t->is($message->getReplySubject(), 'RE: Regarding your offer');

  $message->setIsRich(true);
  $message->setBody("some <html>HTML</html> tags and also\n\n some new lines to test.");
  $t->is($message->getBody(), "some <html>HTML</html> tags and also\n\n some new lines to test.");

  $message->setIsRich(false);
  $message->setBody("some <html>HTML</html> tags and also\n\n some new lines to test.", true);
  $t->is($message->getBody(), "some HTML tags and also<br />\n<br />\n some new lines to test.");

  $message->setSubject('Some <html>HTML</html> in here and there <p>');
  $t->is($message->getSubject(), 'Some HTML in here and there ');

  $body = "Welcome to Collectors Quest - the destination place for all collectors.\n\nGet started by <a href=\"http://www.collectorsquest.com/collection/create.html\">adding your own collection</a> or just click around to find some great <a href=\"http://www.collectorsquest.com/collection.html\">collections</a> and <a href=\"http://www.collectorsquest.com/community/collectors.html\">collectors</a>.\n\nWhat's in your collection?";
  $message->setIsRich(true);
  $message->setBody($body, false);
  $t->is($message->getBody(), $body);

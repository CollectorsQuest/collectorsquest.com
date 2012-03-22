<?php

require_once dirname(__FILE__) . '/../bootstrap/unit.php';

$t = new lime_test();
$t->diag('Testing cqEmailsPlugin/lib/cqEmailTemplate.class.php');

$name_guess = array(
    'Test'  => 'test.html.twig',
    'Test/test' => 'test/test.html.twig',
    'Test//test' => 'test/test.html.twig',
    'Test:test' => 'test/test.html.twig',
    'Test::test' => 'test/test.html.twig',
    'TestyTest/test' => 'testy_test/test.html.twig',
    'TestyTest/theTest' => 'testy_test/the_test.html.twig',
    'TestyTest/the_test' => 'testy_test/the_test.html.twig',
    'TestyTest/sub/test' => 'testy_test/sub/test.html.twig',
);

foreach ($name_guess as $name => $path)
{
  $t->is(cqEmailTemplate::guessPathFromName($name), $path, sprintf('::guessPathFromName() can guess %s -> %s', $name, $path));
}

$templ = new cqEmailTemplate('empty_test');

$t->is($templ->render(), 'test');
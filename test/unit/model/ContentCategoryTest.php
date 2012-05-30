<?php

include(__DIR__.'/../../bootstrap/model.php');

$t = new lime_test(null, array('output' => new lime_output_color(), 'error_reporting' => true));

// Reset all tables we will be working on
ContentCategoryPeer::doDeleteAll();

$root = new ContentCategory();
$root->makeRoot();
$root->setName('Root');
$root->save();


$t->diag('::postSave()');

$zazzy = new ContentCategory();
$zazzy->setName('Zazzy');
$zazzy->insertAsFirstChildOf(ContentCategoryQuery::create()->findRoot());
$zazzy->save();

$belly = new ContentCategory();
$belly->setName('Belly');
$belly->insertAsLastChildOf(ContentCategoryQuery::create()->findRoot());
$belly->save();

$names = ContentCategoryQuery::create()
  ->descendantsOf(ContentCategoryQuery::create()->findRoot())
  ->select('Name')
  ->findTree()->getArrayCopy();
$t->is_deeply($names, array('Belly', 'Zazzy'),
  'postSave() sorts siblings alphabetically, regardless of insert position');


$c = new ContentCategory();
$c->setName('Ally');
$c->insertAsFirstChildOF(ContentCategoryQuery::create()->findRoot());
$c->save();

$names = ContentCategoryQuery::create()
  ->descendantsOf(ContentCategoryQuery::create()->findRoot())
  ->select('Name')
  ->findTree()->getArrayCopy();
$t->is_deeply($names, array('Ally', 'Belly', 'Zazzy'),
  'postSave() sorts siblings alphabetically, regardless of insert position');

$c = new ContentCategory();
$c->setName('Zzzza');
$c->insertAsFirstChildOF(ContentCategoryQuery::create()->findRoot());
$c->save();

$names = ContentCategoryQuery::create()
  ->descendantsOf(ContentCategoryQuery::create()->findRoot())
  ->select('Name')
  ->findTree()->getArrayCopy();
$t->is_deeply($names, array('Ally', 'Belly', 'Zazzy', 'Zzzza'),
  'postSave() sorts siblings alphabetically, regardless of insert position');

$c = new ContentCategory();
$c->setName('Abby');
$c->insertAsLastChildOF(ContentCategoryQuery::create()->findRoot());
$c->save();

$c = new ContentCategory();
$c->setName('Xxxx');
$c->insertAsLastChildOF(ContentCategoryQuery::create()->findRoot());
$c->save();

$c = new ContentCategory();
$c->setName('Zzzzz');
$c->insertAsLastChildOF(ContentCategoryQuery::create()->findRoot());
$c->save();

$names = ContentCategoryQuery::create()
  ->descendantsOf(ContentCategoryQuery::create()->findRoot())
  ->select('Name')
  ->findTree()->getArrayCopy();
$t->is_deeply($names, array('Abby', 'Ally', 'Belly', 'Xxxx', 'Zazzy', 'Zzzza', 'Zzzzz'),
  'postSave() sorts siblings alphabetically, regardless of insert position');


ContentCategoryQuery::create()
  ->filterByName('Ally')
  ->update(array('Name'=>'Sally'), null, $forceIndividualSaves = true);

$names = ContentCategoryQuery::create()
  ->descendantsOf(ContentCategoryQuery::create()->findRoot())
  ->select('Name')
  ->findTree()->getArrayCopy();
$t->is_deeply($names, array('Abby', 'Belly', 'Sally', 'Xxxx', 'Zazzy', 'Zzzza', 'Zzzzz'),
  'postSave() sorts siblings alphabetically on name update');

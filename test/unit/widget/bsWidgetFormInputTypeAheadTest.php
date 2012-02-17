<?php
/**
 * File: bsWidgetFormInputTypeAheadTest.php
 *
 * @author zecho
 * @version $Id$
 *
 */

include(__DIR__ . '/../../bootstrap/unit.php');

$t = new lime_test(2, array(
  'output'          => new lime_output_color(),
  'error_reporting' => true
));

try
{
  $widget = new bsWidgetFormInputTypeAhead();
  $t->fail('widget has required option "source"');
} catch (RuntimeException $e)
{
  $t->pass('Option "source" is required');
}

try
{
  $widget = new bsWidgetFormInputTypeAhead(array(
    'source'=> array('test'),
    'items' => 10
  ));
} catch (RuntimeException $e)
{
  $t->fail();
}

$t->is(
  $widget->render('test'),
  '<input type="text" name="test" data-provide="typeahead" id="test" /><script type="text/javascript">jQuery("#test").typeahead({"items":10,"matcher":null,"sorter":null,"highlighter":null,"source":["test"]});</script>'
);

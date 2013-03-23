<?php

namespace CollectorsQuest\Test;

// tested classes
require_once get_root_dir() . '/lib/service/FindsSellersAnonymous.class.php';

// dependencies
require_once get_root_dir() . '/lib/vendor/symfony/symfony1/lib/util/sfToolkit.class.php';
require_once get_root_dir() . '/lib/vendor/symfony/symfony1/lib/util/sfInflector.class.php';

class FindsSellersAnonymousTest extends \PHPUnit_Framework_TestCase
{
  public function testForCollectiblesCallsTheProperGetters()
  {
    $collectible = $this->getMock('Collectible', array('getName', 'getDescription', 'getPrimaryKey'));
    $collectibles = array($collectible);

    \FindsSellersAnonymous::forCollectibles($collectibles);
  }

  public function testForObjectsReturnsOffendingStringsByThingId()
  {
    $class = $this->getMock('\FindsSellersAnonymous', array('isOffending'));
    $thing = $this->getMock('Thing', array('getName', 'getDescription', 'getPrimaryKey', 'getCamelCaseTest'));

    $thing->expects($this->exactly(2))
      ->method('getPrimaryKey')
      ->will($this->returnValue(123));
    $thing->expects($this->once())
      ->method('getDescription')
      ->will($this->returnValue('offending_description'));

    $class::staticExpects($this->exactly(3))
      ->method('isOffending')
      ->with($this->logicalOr(
          $this->equalTo(null),
          $this->equalTo('offending_description')
      ))
      ->will($this->returnValueMap(array(
          array(null, false),
          array('offending_description', true),
      )));

    $things = array($thing);
    $res = $class::forObjects($things, array('name', 'description', 'camel_case_test'));

    $this->assertEquals(array(
        123 => array(
            'object' => $thing,
            'offending_strings' => array('offending_description')
        )
    ), $res);
  }

  public function testForCollectiblesDelegatesToForObjects()
  {
    $class = $this->getMock('\FindsSellersAnonymous', array('forObjects'));
    $collectibles = array();

    $class::staticExpects($this->once())
      ->method('forObjects')
      ->with($collectibles, array('name', 'description'));

    $class::forCollectibles($collectibles);
  }

  public function testForCollectionsDelegatesToForObjects()
  {
    $class = $this->getMock('\FindsSellersAnonymous', array('forObjects'));
    $collections = array();

    $class::staticExpects($this->once())
      ->method('forObjects')
      ->with($collections, array('name', 'description'));

    $class::forCollections($collections);
  }

  public function testForCollectorsDelegatesToForObjects()
  {
    $class = $this->getMock('\FindsSellersAnonymous', array('forObjects'));
    $collectors = array();

    $class::staticExpects($this->once())
      ->method('forObjects')
      ->with($collectors, array('display_name'));

    $class::forCollectors($collectors);
  }

  /**
   * @dataProvider providerTestIsOffending
   */
  public function testIsOffending($string, $is_offending)
  {
    $this->assertEquals(\FindsSellersAnonymous::isOffending($string), $is_offending);
  }

  public function providerTestIsOffending()
  {
    return array(
        array('', false),
        array('for sale', true),
        array('FOR SALE', true),
        array('not for sale', false),
        array('selling', true),
        array('not selling', false),
        array('$12', true),
        array('$ 12', true),
        array('shipping', true),
        array('estate sale selling', false),
        array('estate gablargh sale selling', false),
        array('bought $12', false),
    );
  }
}
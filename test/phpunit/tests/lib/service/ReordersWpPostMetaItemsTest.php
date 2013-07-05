<?php

namespace CollectorsQuest\Test;

// tested classes
require_once get_root_dir() . '/lib/service/ReordersWpPostMetaItems.class.php';

// dependencies
require_once get_root_dir() . '/plugins/iceLibsPlugin/lib/IceFunctions.class.php';
require_once get_root_dir() . '/lib/collectorsquest/cqFunctions.class.php';

class ReordersWpPostMetaItems extends \PHPUnit_Framework_TestCase
{

  /**
   * @dataProvider providerTestReordersFeaturedItems
   */
  public function testReordersFeaturedItems($input, $reorder, $output)
  {
    $this->assertEquals(
      \ReordersWpPostMetaItems::getReorderedFeaturedItems($input, $reorder),
      $output
    );
  }

  public function providerTestReordersFeaturedItems()
  {
    return array(
        // some basic input tests
        array(null, null, ''),
        array('', '', ''),
        array('1,2,3', '1,2,3', '1, 2, 3'),
        array('1, 2, 3', '1, 2, 3', '1, 2, 3'),
        array('1, 2, 3', [1, 2, 3], '1, 2, 3'),

        // handle normal reorder
        array('1, 2, 3', '2, 1, 3', '2, 1, 3'),
        array('1, 2, 3', [2, 1, 3], '2, 1, 3'),

        // handle reorder with sizes
        array('1:1x2, 2, 3', '1, 2, 3', '1:1x2, 2, 3'),
        array('1:1x2, 2, 3', '2, 1, 3', '2, 1:1x2, 3'),
        array('1:1x2, 2, 3', [2, 1, 3], '2, 1:1x2, 3'),

        // handle reorder with excludes
        array('1:1x2, 2, 3, 4', '1, 2, 3', '1:1x2, 2, 3, 4'),
        array('1:1x2, 2, 3, 4', '2, 1, 3', '2, 1:1x2, 3, 4'),
        array('4, 1:1x2, 2, 3', '1, 2, 3', '1:1x2, 2, 3, 4'),
        array('4, 1:1x2, 2, 3', '2, 1, 3', '2, 1:1x2, 3, 4'),
        array('4, 1:1x2, 2, 3', [2, 1, 3], '2, 1:1x2, 3, 4'),
    );
  }

  /**
   * @dataProvider providerTestNormalizeFeaturedItemsArray
   */
  public function testNormalizeFeaturedItemsArray($input, $output)
  {
    $this->assertEquals(
      \ReordersWpPostMetaItems::normalizeFeaturedItemsArray($input),
      $output
    );
  }

  public function providerTestNormalizeFeaturedItemsArray()
  {
    return array(
      array([], []),
      array([1, 2], [1 => 1, 2 => 2]),
      array(['1:1x2', 2, '3'], [1 => '1:1x2', 2 => 2, 3 => 3])
    );
  }

}
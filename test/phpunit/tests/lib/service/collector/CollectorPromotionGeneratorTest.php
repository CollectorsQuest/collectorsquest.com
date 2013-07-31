<?php

// tested classes
require_once get_root_dir() . '/lib/service/collector/CollectorPromotionGenerator.class.php';

/**
 * @author      Ivan Plamenov Tanev aka CraftyShadow <vankata.t@gmail.com>
 */
class CollectorPromotionGeneratorTest extends sfWebTestCase
{
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        self::loadFixtureDirs(array(
            '01_test_collectors',
        ));
    }

    public function setUp()
    {
        $this->generator = new CollectorPromotionGenerator();
        $this->collector = $this->getMock('Collector');
        $this->collector->expects($this->any())
            ->method('getDisplayName')
            ->will($this->returnValue('Tester'));

    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function test_checks_valid_type()
    {
        $this->generator->generate($this->collector, 'invalid_type', 10);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function test_checks_valid_amount()
    {
        $this->generator->generate(
            $this->collector, PromotionPeer::AMOUNT_TYPE_FIX, 'textytext'
        );
    }

    public function test_promotion_generation()
    {
        /* @var $promo Promotion */
        $promo = $this->generator->generate(
            $this->collector, PromotionPeer::AMOUNT_TYPE_PERCENTAGE, 20
        );

        $this->assertInstanceOf('Promotion', $promo);
        $this->assertEquals('Percentage', $promo->getAmountType());
        $this->assertEquals(20, $promo->getAmount());
        $this->assertEquals(
            'Tester - 20% off ['.date('Y-m-d').']', $promo->getPromotionName()
        );
        $this->assertRegExp('/CQ-20\%OFF-\w{8}/', $promo->getPromotionCode());
        $this->assertNotNull($promo->getExpiryDate());
        $this->assertGreaterThan(new DateTime(), $promo->getExpiryDate(null));
        $this->assertEquals($promo->getNoOfTimeUsed(), 1);
    }

    public function test_promotion_naming_percent()
    {
        $promo_name = $this->generator->generatePromotionName(
            $this->collector, PromotionPeer::AMOUNT_TYPE_PERCENTAGE, 20, new DateTime('2013-05-05')
        );

        $this->assertEquals($promo_name, 'Tester - 20% off [2013-05-05]');
    }

    public function test_promotion_naming_fixed()
    {
        $promo_name = $this->generator->generatePromotionName(
            $this->collector, PromotionPeer::AMOUNT_TYPE_FIX, 20, new DateTime('2013-05-05')
        );
        $this->assertEquals($promo_name, 'Tester - 20$ off [2013-05-05]');

        $promo_name = $this->generator->generatePromotionName(
            $this->collector, PromotionPeer::AMOUNT_TYPE_FIX, 23.41, new DateTime('2013-05-05')
        );
        $this->assertEquals($promo_name, 'Tester - 23.41$ off [2013-05-05]');
    }

    public function test_promotion_code()
    {
        $promo_code = $this->generator->generatePromotionCode(
            $this->collector, PromotionPeer::AMOUNT_TYPE_FIX, 20, new DateTime('2013-05-05')
        );
        $this->assertRegExp('/CQ-20\$OFF-\w{8}/', $promo_code);

        $promo_code = $this->generator->generatePromotionCode(
            $this->collector, PromotionPeer::AMOUNT_TYPE_PERCENTAGE, 15, new DateTime('2013-05-05')
        );
        $this->assertRegExp('/CQ-15\%OFF-\w{8}/', $promo_code);
    }
}

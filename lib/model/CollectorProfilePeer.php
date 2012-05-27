<?php

require 'lib/model/om/BaseCollectorProfilePeer.php';

class CollectorProfilePeer extends BaseCollectorProfilePeer
{
  public static $collector_types = array(
    'casual' => 'Casual',
    'occasional' => 'Occasional',
    'serious' => 'Serious',
    'obsessive' => 'Obsessive',
    'expert' => 'Expert'
  );


  /**
   * @var array Fields handled by the ExtraProperties Propel behavior
   */
  public static $extraFieldNames = array (
    BasePeer::TYPE_PHPNAME => array (
        'AboutMe',
        'AboutCompany',
        'AboutCollections',
        'AboutWhatYouCollect',
        'AboutWhatYouSell',
        'AboutMostExpensiveItem',
        'AboutAnnuallySpend',
        'AboutPurchasesPerYear',
        'AboutNewItemEvery',
        'AboutInterests',
    ),
    BasePeer::TYPE_STUDLYPHPNAME => array (
        'aboutMe',
        'aboutCompany',
        'aboutCollections',
        'aboutWhatYouCollect',
        'aboutWhatYouSell',
        'aboutMostExpensiveItem',
        'aboutAnnuallySpend',
        'aboutPurchasesPerYear',
        'aboutNewItemEvery',
        'aboutInterests',
    ),
    BasePeer::TYPE_FIELDNAME => array (
        'about_me',
        'about_company',
        'about_collections',
        'about_what_you_collect',
        'about_what_you_sell',
        'about_most_expensive_item',
        'about_annually_spend',
        'about_purchases_per_year',
        'about_new_item_every',
        'about_interests',
    ),
  );

}

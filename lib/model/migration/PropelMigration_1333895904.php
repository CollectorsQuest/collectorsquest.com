<?php

class PropelMigration_1333895904
{

	public function preUp($manager)
	{
		// add the pre-migration code here
	}

	public function postUp()
	{
    $themes = array(
      0 => array(
        'collections' => array(57, 869, 1615, 263),
        'collectibles' => array(6662, 7714, 39980, 39414, 37797, 37711, 37045, 37007, 39043, 35784, 13481, 34599, 36088, 9770, 21974, 35932, 35871, 34582, 13483, 2761)
      ),
      1 => array(
        'collections' => array(1615, 263),
        'collectibles' => array(13483, 28853, 234, 23004, 21826, 7116, 1270, 245, 63, 1302, 11273, 6937, 32274, 7874, 7882, 12959, 24247, 24243, 19941, 35871)
      ),
      2 => array(
        'collections' => array(256, 180, 2228, 1535),
        'collectibles' => array(14777, 15913, 7951, 15851, 19767, 3515, 10876, 19961, 19941, 24568, 10754, 26030, 7800, 31060, 7731, 5957, 575, 397, 32432, 24243)
      ),
      3 => array(
        'collections' => array(1335, 1866, 256, 180),
        'collectibles' => array(13870, 241, 2761, 18650, 17582, 30195, 33222, 7258, 16591, 18115, 20012, 19596, 6170, 7725, 6684, 6433, 37960, 19961, 21986, 14777)
      ),
      4 => array(
        'collections' => array(2228, 1615, 1535, 1866),
        'collectibles' => array(8947, 37960, 21986, 689, 28975, 4483, 228, 35844, 669, 9526, 961, 22584, 19797, 13762, 25919, 8003, 34093, 6662, 19596, 397)
      ),
      5 => array(
        'collections' => array(1335, 1866, 256, 180),
        'collectibles' => array(13870, 16503, 2761, 18650, 17582, 52813, 33222, 12836, 16591, 15797, 20012, 19596, 15614, 7725, 36385, 6433, 37960, 19961, 8009, 14777)
      ),
      // Action Figures
      6 => array(
        'collections' => array(2679, 304, 2619, 3078),
        'collectibles' => array(56801, 56852, 56575, 45432, 36826, 11799, 11339, 8932, 7320, 6277, 556, 170, 30870, 30822, 61221, 61774, 58439, 56585, 2915, 3963)
      ),
      // Dinosaurs
      7 => array(
        'collections' => array(1058, 2607, 2189, 609),
        'collectibles' => array(47186, 36281, 35787, 14708, 34389, 12079, 12038, 11789, 9978, 9952, 9239, 8899, 6900, 4684, 4182, 4083, 3879, 3568, 42732, 12354)
      )
    );


    foreach ($themes as $i => $theme)
    {
      $wp_post = new wpPost();
      $wp_post->setPostAuthor(1);
      $wp_post->setPostDate(date("M d Y H:i:s", strtotime('2012-04-01')));
      $wp_post->setPostDateGmt(gmdate("M d Y H:i:s", strtotime('2012-04-01')));
      $wp_post->setPostContent(null);
      $wp_post->setPostTitle('Theme '. $i);
      $wp_post->setCommentStatus('closed');
      $wp_post->setPingStatus('closed');
      $wp_post->setPostName('theme-'. $i);
      $wp_post->setPostModified(date("M d Y H:i:s", strtotime('2012-04-01')));
      $wp_post->setPostModifiedGmt(gmdate("M d Y H:i:s", strtotime('2012-04-01')));
      $wp_post->setPostParent(0);
      $wp_post->setPostStatus('publish');
      $wp_post->setPostType('homepage_showcase');
      $wp_post->save();

      $wp_post->setGuid('http://'. sfConfig::get('app_www_domain').'/blog/?post_type=homepage_showcase&#038;p='. $wp_post->getId());
      $wp_post->save();

      $wp_post_meta = new wpPostMeta();
      $wp_post_meta->setwpPost($wp_post);
      $wp_post_meta->setMetaKey('_members_access_role');
      $wp_post_meta->setMetaValue('editorial');
      $wp_post_meta->save();

      $wp_post_meta = new wpPostMeta();
      $wp_post_meta->setwpPost($wp_post);
      $wp_post_meta->setMetaKey('cq_collection_ids');
      $wp_post_meta->setMetaValue(implode(', ', $theme['collections']));
      $wp_post_meta->save();

      $wp_post_meta = new wpPostMeta();
      $wp_post_meta->setwpPost($wp_post);
      $wp_post_meta->setMetaKey('cq_collectible_ids');
      $wp_post_meta->setMetaValue(implode(', ', $theme['collectibles']));
      $wp_post_meta->save();
    }

    return true;
	}

	public function preDown($manager)
	{
		// add the pre-migration code here
	}

	public function postDown($manager)
	{
		// add the post-migration code here
	}

	/**
	 * Get the SQL statements for the Up migration
	 *
	 * @return array list of the SQL strings to execute for the Up migration
	 *               the keys being the datasources
	 */
	public function getUpSQL()
	{
		return array();
	}

	/**
	 * Get the SQL statements for the Down migration
	 *
	 * @return array list of the SQL strings to execute for the Down migration
	 *               the keys being the datasources
	 */
	public function getDownSQL()
	{
		return array();
	}

}

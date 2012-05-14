<?php

class PropelMigration_1333888828
{

	public function preUp($manager)
	{
		// add the pre-migration code here
	}

	public function postUp($manager)
	{
    $capabilities = array (
      'read' => 'true',

      'publish_editorials' => true,
      'read_editorial' => true,
      'read_private_editorials' => true,

      'edit_editorial' => true,
      'edit_editorials' => true,
      'edit_published_editorials' => true,
      'edit_others_editorials' => true,
      'edit_private_editorials' => true,

      'delete_editorial' => true,
      'delete_editorials' => true,
      'delete_published_editorials' => true,
      'delete_private_editorials' => true,
      'delete_others_editorials' => true,
    );

    $wp_option = wpOptionQuery::create()->filterByOptionName('wp_user_roles')->findOne();
    $wp_user_roles = unserialize($wp_option->getOptionValue());

    foreach ($capabilities as $capability => $enabled)
    {
      $wp_user_roles['administrator']['capabilities'][$capability] = $enabled;
    }

    $wp_user_roles['editorial'] = array (
      'name' => 'Community Editorial',
      'capabilities' => $capabilities,
    );
    $wp_option->setOptionValue(serialize($wp_user_roles));
    $wp_option->save();

    $c = new Criteria();
    $c->setDistinct();
    $c->add(FeaturedPeer::FEATURED_TYPE_ID, FeaturedPeer::TYPE_FEATURED_WEEK);
    $c->add(FeaturedPeer::TREE_LEFT, 1);
    $c->add(FeaturedPeer::END_DATE, date('Y-m-d'), Criteria::LESS_EQUAL);
    $c->add(FeaturedPeer::IS_ACTIVE, true);
    $c->addDescendingOrderByColumn(FeaturedPeer::END_DATE);
    $c->setLimit(15);

    /** @var $featured_weeks Featured[] */
    $featured_weeks = FeaturedPeer::doSelect($c);

    foreach ($featured_weeks as $week)
    {
      $wp_post = new wpPost();
      $wp_post->setPostAuthor(1);
      $wp_post->setPostDate(date("M d Y H:i:s", strtotime('2012-04-01')));
      $wp_post->setPostDateGmt(gmdate("M d Y H:i:s", strtotime('2012-04-01')));
      $wp_post->setPostContent($week->homepage_text);
      $wp_post->setPostTitle($week->title);
      $wp_post->setCommentStatus('closed');
      $wp_post->setPingStatus('closed');
      $wp_post->setPostName(Utf8::slugify($week->title, '-', true, true));
      $wp_post->setPostModified(date("M d Y H:i:s", strtotime('2012-04-01')));
      $wp_post->setPostModifiedGmt(gmdate("M d Y H:i:s", strtotime('2012-04-01')));
      $wp_post->setPostParent(0);
      $wp_post->setPostType('homepage_carousel');
      $wp_post->save();

      $wp_post_meta = new wpPostMeta();
      $wp_post_meta->setwpPost($wp_post);
      $wp_post_meta->setMetaKey('_members_access_role');
      $wp_post_meta->setMetaValue('editorial');
      $wp_post_meta->save();

      /** @var $collectibles Collectible[] */
      $collectibles = $week->getHomepageCollectibles(10);

      $image = null;
      foreach ($collectibles as $collectible)
      {
        if (!$multimedia = $collectible->getPrimaryImage())
        {
          continue;
        }

        @list($width, $height) = $multimedia->getImageInfo('original');

        if ($width == 0 && $height == 0)
        {
          $original = sfConfig::get('sf_data_dir') .'/migrations/1333888828.jpg';
          $width = 520;
          $height = 310;
        }
        else
        {
          $original = $multimedia->getAbsolutePath('original');
        }

        if ($width >= 520 && $height >= 310)
        {
          if ($thumb = iceModelMultimediaPeer::makeThumb($original, '520x310', 'center', false))
          {
            $image = sfConfig::get('sf_upload_dir') . '/blog/2012/04/'. md5_file($thumb->getFilename()) .'.jpg';
            $thumb->saveAs($image, 'image/jpeg');

            break;
          }
        }
      }

      if ($image)
      {
        $wp_attachment = new wpPost();
        $wp_attachment->setPostAuthor(1);
        $wp_attachment->setPostDate(date("M d Y H:i:s", strtotime('2012-04-01')));
        $wp_attachment->setPostDateGmt(gmdate("M d Y H:i:s", strtotime('2012-04-01')));
        $wp_attachment->setPostContent(null);
        $wp_attachment->setPostTitle(basename($image));
        $wp_attachment->setPostStatus('inherit');
        $wp_attachment->setCommentStatus('closed');
        $wp_attachment->setPingStatus('closed');
        $wp_attachment->setPostModified(date("M d Y H:i:s", strtotime('2012-04-01')));
        $wp_attachment->setPostModifiedGmt(gmdate("M d Y H:i:s", strtotime('2012-04-01')));
        $wp_attachment->setPostParent($wp_post->getId());
        $wp_attachment->setGuid('/uploads/blog/2012/04/'. basename($image));
        $wp_attachment->setPostType('attachment');
        $wp_attachment->setPostMimeType('image/jpeg');
        $wp_attachment->save();

        $wp_post_meta = new wpPostMeta();
        $wp_post_meta->setwpPost($wp_attachment);
        $wp_post_meta->setMetaKey('_wp_attached_file');
        $wp_post_meta->setMetaValue('2012/04/'. basename($image));
        $wp_post_meta->save();

        $wp_post_meta = new wpPostMeta();
        $wp_post_meta->setwpPost($wp_attachment);
        $wp_post_meta->setMetaKey('_wp_attachment_metadata');
        $wp_post_meta->setMetaValue('a:6:{s:5:"width";s:3:"520";s:6:"height";s:3:"310";s:14:"hwstring_small";s:23:"height=\'76\' width=\'128\'";s:4:"file";s:39:"2012/04/'. basename($image) .'";s:5:"sizes";a:0:{}s:10:"image_meta";a:10:{s:8:"aperture";s:1:"0";s:6:"credit";s:0:"";s:6:"camera";s:0:"";s:7:"caption";s:0:"";s:17:"created_timestamp";s:1:"0";s:9:"copyright";s:0:"";s:12:"focal_length";s:1:"0";s:3:"iso";s:1:"0";s:13:"shutter_speed";s:1:"0";s:5:"title";s:0:"";}}');
        $wp_post_meta->save();

        $wp_post_meta = new wpPostMeta();
        $wp_post_meta->setwpPost($wp_post);
        $wp_post_meta->setMetaKey('_thumbnail_id');
        $wp_post_meta->setMetaValue($wp_attachment->getId());
        $wp_post_meta->save();

        $wp_post->setPostStatus('publish');
        $wp_post->save();
      }
      else
      {
        $wp_post->setPostStatus('draft');
        $wp_post->save();
      }
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
		return array('propel' => '
      # This is a fix for InnoDB in MySQL >= 4.1.x
      # It "suspends judgement" for fkey relationships until are tables are set.
      SET FOREIGN_KEY_CHECKS = 0;
      SET FOREIGN_KEY_CHECKS = 1;
    ');
	}

	/**
	 * Get the SQL statements for the Down migration
	 *
	 * @return array list of the SQL strings to execute for the Down migration
	 *               the keys being the datasources
	 */
	public function getDownSQL()
	{
    return array('propel' => '
      # This is a fix for InnoDB in MySQL >= 4.1.x
      # It "suspends judgement" for fkey relationships until are tables are set.
      SET FOREIGN_KEY_CHECKS = 0;
      SET FOREIGN_KEY_CHECKS = 1;
    ');
	}

}

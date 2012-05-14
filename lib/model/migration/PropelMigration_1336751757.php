<?php

class PropelMigration_1336751757
{

  /**
   * @param PropelMigrationManager $manager
   */
  public function preUp($manager)
  {
    // add the pre-migration code here
  }

  public function postUp($manager)
  {
    // add the post-migration code here
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
    return array(
      'blog'  => '
        REPLACE `wp_options` SET
          `blog_id` = 0,
          `option_name` = \'bcn_options\',
          `option_value` = \'a:105:{s:17:"bmainsite_display";b:0;s:15:"Smainsite_title";s:4:"Home";s:18:"Hmainsite_template";s:52:"<a title="Go to %title%." href="%link%">%htitle%</a>";s:28:"Hmainsite_template_no_anchor";s:8:"%htitle%";s:13:"bhome_display";b:1;s:11:"Shome_title";s:4:"Blog";s:14:"Hhome_template";s:52:"<a title="Go to %title%." href="%link%">%htitle%</a>";s:24:"Hhome_template_no_anchor";s:8:"%htitle%";s:13:"bblog_display";b:0;s:14:"Hblog_template";s:52:"<a title="Go to %title%." href="%link%">%htitle%</a>";s:24:"Hblog_template_no_anchor";s:8:"%htitle%";s:10:"hseparator";s:5:" â†’ ";s:17:"amax_title_length";i:0;s:20:"bcurrent_item_linked";b:0;s:19:"Hpost_page_template";s:52:"<a title="Go to %title%." href="%link%">%htitle%</a>";s:29:"Hpost_page_template_no_anchor";s:8:"%htitle%";s:15:"apost_page_root";s:1:"0";s:15:"Hpaged_template";s:13:"Page %htitle%";s:14:"bpaged_display";b:0;s:19:"Hpost_post_template";s:52:"<a title="Go to %title%." href="%link%">%htitle%</a>";s:29:"Hpost_post_template_no_anchor";s:8:"%htitle%";s:15:"apost_post_root";s:0:"";s:27:"bpost_post_taxonomy_display";b:1;s:24:"Spost_post_taxonomy_type";s:8:"category";s:25:"Hpost_attachment_template";s:52:"<a title="Go to %title%." href="%link%">%htitle%</a>";s:35:"Hpost_attachment_template_no_anchor";s:8:"%htitle%";s:13:"H404_template";s:8:"%htitle%";s:10:"S404_title";s:3:"404";s:16:"Hsearch_template";s:118:"Search results for &#39;<a title="Go to the first page of search results for %title%." href="%link%">%htitle%</a>&#39;";s:26:"Hsearch_template_no_anchor";s:37:"Search results for &#39;%htitle%&#39;";s:18:"Hpost_tag_template";s:69:"<a title="Go to the %title% tag archives." href="%link%">%htitle%</a>";s:28:"Hpost_tag_template_no_anchor";s:8:"%htitle%";s:16:"Hauthor_template";s:88:"Blogger: <a title="Go to the first page of posts by %title%." href="%link%">%htitle%</a>";s:26:"Hauthor_template_no_anchor";s:17:"Blogger: %htitle%";s:12:"Sauthor_name";s:12:"display_name";s:18:"Hcategory_template";s:74:"<a title="Go to the %title% category archives." href="%link%">%htitle%</a>";s:28:"Hcategory_template_no_anchor";s:8:"%htitle%";s:14:"Hdate_template";s:65:"<a title="Go to the %title% archives." href="%link%">%htitle%</a>";s:24:"Hdate_template_no_anchor";s:8:"%htitle%";s:33:"Hpost_wpcf7_contact_form_template";s:52:"<a title="Go to %title%." href="%link%">%htitle%</a>";s:43:"Hpost_wpcf7_contact_form_template_no_anchor";s:8:"%htitle%";s:40:"bpost_wpcf7_contact_form_archive_display";b:0;s:29:"apost_wpcf7_contact_form_root";i:0;s:41:"bpost_wpcf7_contact_form_taxonomy_display";b:0;s:38:"Spost_wpcf7_contact_form_taxonomy_type";s:4:"date";s:23:"Hpost_feedback_template";s:52:"<a title="Go to %title%." href="%link%">%htitle%</a>";s:33:"Hpost_feedback_template_no_anchor";s:8:"%htitle%";s:30:"bpost_feedback_archive_display";b:0;s:19:"apost_feedback_root";i:0;s:31:"bpost_feedback_taxonomy_display";b:0;s:28:"Spost_feedback_taxonomy_type";s:4:"date";s:23:"Hpost_cms_slot_template";s:52:"<a title="Go to %title%." href="%link%">%htitle%</a>";s:33:"Hpost_cms_slot_template_no_anchor";s:8:"%htitle%";s:30:"bpost_cms_slot_archive_display";b:0;s:19:"apost_cms_slot_root";i:0;s:31:"bpost_cms_slot_taxonomy_display";b:0;s:28:"Spost_cms_slot_taxonomy_type";s:4:"date";s:32:"Hpost_homepage_carousel_template";s:52:"<a title="Go to %title%." href="%link%">%htitle%</a>";s:42:"Hpost_homepage_carousel_template_no_anchor";s:8:"%htitle%";s:39:"bpost_homepage_carousel_archive_display";b:0;s:28:"apost_homepage_carousel_root";i:0;s:40:"bpost_homepage_carousel_taxonomy_display";b:0;s:37:"Spost_homepage_carousel_taxonomy_type";s:4:"date";s:32:"Hpost_homepage_showcase_template";s:52:"<a title="Go to %title%." href="%link%">%htitle%</a>";s:42:"Hpost_homepage_showcase_template_no_anchor";s:8:"%htitle%";s:39:"bpost_homepage_showcase_archive_display";b:0;s:28:"apost_homepage_showcase_root";i:0;s:40:"bpost_homepage_showcase_taxonomy_display";b:0;s:37:"Spost_homepage_showcase_taxonomy_type";s:4:"date";s:34:"Hpost_collectors_question_template";s:52:"<a title="Go to %title%." href="%link%">%htitle%</a>";s:44:"Hpost_collectors_question_template_no_anchor";s:8:"%htitle%";s:41:"bpost_collectors_question_archive_display";b:0;s:30:"apost_collectors_question_root";i:0;s:42:"bpost_collectors_question_taxonomy_display";b:0;s:39:"Spost_collectors_question_taxonomy_type";s:4:"date";s:34:"Hpost_marketplace_explore_template";s:52:"<a title="Go to %title%." href="%link%">%htitle%</a>";s:44:"Hpost_marketplace_explore_template_no_anchor";s:8:"%htitle%";s:41:"bpost_marketplace_explore_archive_display";b:0;s:30:"apost_marketplace_explore_root";i:0;s:42:"bpost_marketplace_explore_taxonomy_display";b:0;s:39:"Spost_marketplace_explore_taxonomy_type";s:4:"date";s:35:"Hpost_marketplace_featured_template";s:52:"<a title="Go to %title%." href="%link%">%htitle%</a>";s:45:"Hpost_marketplace_featured_template_no_anchor";s:8:"%htitle%";s:42:"bpost_marketplace_featured_archive_display";b:0;s:31:"apost_marketplace_featured_root";i:0;s:43:"bpost_marketplace_featured_taxonomy_display";b:0;s:40:"Spost_marketplace_featured_taxonomy_type";s:4:"date";s:34:"Hpost_collections_explore_template";s:52:"<a title="Go to %title%." href="%link%">%htitle%</a>";s:44:"Hpost_collections_explore_template_no_anchor";s:8:"%htitle%";s:41:"bpost_collections_explore_archive_display";b:0;s:30:"apost_collections_explore_root";i:0;s:42:"bpost_collections_explore_taxonomy_display";b:0;s:39:"Spost_collections_explore_taxonomy_type";s:4:"date";s:28:"Hpost_featured_week_template";s:52:"<a title="Go to %title%." href="%link%">%htitle%</a>";s:38:"Hpost_featured_week_template_no_anchor";s:8:"%htitle%";s:35:"bpost_featured_week_archive_display";b:0;s:24:"apost_featured_week_root";i:0;s:36:"bpost_featured_week_taxonomy_display";b:0;s:33:"Spost_featured_week_taxonomy_type";s:4:"date";s:31:"Hpost_seller_spotlight_template";s:52:"<a title="Go to %title%." href="%link%">%htitle%</a>";s:41:"Hpost_seller_spotlight_template_no_anchor";s:8:"%htitle%";s:38:"bpost_seller_spotlight_archive_display";b:0;s:27:"apost_seller_spotlight_root";i:0;s:39:"bpost_seller_spotlight_taxonomy_display";b:0;s:36:"Spost_seller_spotlight_taxonomy_type";s:4:"date";}\',
          `autoload` = \'yes\';
        REPLACE `wp_options` SET
          `blog_id` = 0,
          `option_name` = \'bcn_options_bk\',
          `option_value` = \'a:105:{s:17:"bmainsite_display";b:0;s:15:"Smainsite_title";s:4:"Home";s:18:"Hmainsite_template";s:52:"<a title="Go to %title%." href="%link%">%htitle%</a>";s:28:"Hmainsite_template_no_anchor";s:8:"%htitle%";s:13:"bhome_display";b:1;s:11:"Shome_title";s:4:"Blog";s:14:"Hhome_template";s:52:"<a title="Go to %title%." href="%link%">%htitle%</a>";s:24:"Hhome_template_no_anchor";s:8:"%htitle%";s:13:"bblog_display";b:0;s:14:"Hblog_template";s:52:"<a title="Go to %title%." href="%link%">%htitle%</a>";s:24:"Hblog_template_no_anchor";s:8:"%htitle%";s:10:"hseparator";s:5:" â†’ ";s:17:"amax_title_length";i:0;s:20:"bcurrent_item_linked";b:0;s:19:"Hpost_page_template";s:52:"<a title="Go to %title%." href="%link%">%htitle%</a>";s:29:"Hpost_page_template_no_anchor";s:8:"%htitle%";s:15:"apost_page_root";s:1:"0";s:15:"Hpaged_template";s:13:"Page %htitle%";s:14:"bpaged_display";b:0;s:19:"Hpost_post_template";s:52:"<a title="Go to %title%." href="%link%">%htitle%</a>";s:29:"Hpost_post_template_no_anchor";s:8:"%htitle%";s:15:"apost_post_root";s:0:"";s:27:"bpost_post_taxonomy_display";b:1;s:24:"Spost_post_taxonomy_type";s:8:"category";s:25:"Hpost_attachment_template";s:52:"<a title="Go to %title%." href="%link%">%htitle%</a>";s:35:"Hpost_attachment_template_no_anchor";s:8:"%htitle%";s:13:"H404_template";s:8:"%htitle%";s:10:"S404_title";s:3:"404";s:16:"Hsearch_template";s:118:"Search results for &#39;<a title="Go to the first page of search results for %title%." href="%link%">%htitle%</a>&#39;";s:26:"Hsearch_template_no_anchor";s:37:"Search results for &#39;%htitle%&#39;";s:18:"Hpost_tag_template";s:69:"<a title="Go to the %title% tag archives." href="%link%">%htitle%</a>";s:28:"Hpost_tag_template_no_anchor";s:8:"%htitle%";s:16:"Hauthor_template";s:92:"Articles by: <a title="Go to the first page of posts by %title%." href="%link%">%htitle%</a>";s:26:"Hauthor_template_no_anchor";s:21:"Articles by: %htitle%";s:12:"Sauthor_name";s:12:"display_name";s:18:"Hcategory_template";s:74:"<a title="Go to the %title% category archives." href="%link%">%htitle%</a>";s:28:"Hcategory_template_no_anchor";s:8:"%htitle%";s:14:"Hdate_template";s:65:"<a title="Go to the %title% archives." href="%link%">%htitle%</a>";s:24:"Hdate_template_no_anchor";s:8:"%htitle%";s:33:"Hpost_wpcf7_contact_form_template";s:52:"<a title="Go to %title%." href="%link%">%htitle%</a>";s:43:"Hpost_wpcf7_contact_form_template_no_anchor";s:8:"%htitle%";s:40:"bpost_wpcf7_contact_form_archive_display";b:0;s:29:"apost_wpcf7_contact_form_root";i:0;s:41:"bpost_wpcf7_contact_form_taxonomy_display";b:0;s:38:"Spost_wpcf7_contact_form_taxonomy_type";s:4:"date";s:23:"Hpost_feedback_template";s:52:"<a title="Go to %title%." href="%link%">%htitle%</a>";s:33:"Hpost_feedback_template_no_anchor";s:8:"%htitle%";s:30:"bpost_feedback_archive_display";b:0;s:19:"apost_feedback_root";i:0;s:31:"bpost_feedback_taxonomy_display";b:0;s:28:"Spost_feedback_taxonomy_type";s:4:"date";s:23:"Hpost_cms_slot_template";s:52:"<a title="Go to %title%." href="%link%">%htitle%</a>";s:33:"Hpost_cms_slot_template_no_anchor";s:8:"%htitle%";s:30:"bpost_cms_slot_archive_display";b:0;s:19:"apost_cms_slot_root";i:0;s:31:"bpost_cms_slot_taxonomy_display";b:0;s:28:"Spost_cms_slot_taxonomy_type";s:4:"date";s:32:"Hpost_homepage_carousel_template";s:52:"<a title="Go to %title%." href="%link%">%htitle%</a>";s:42:"Hpost_homepage_carousel_template_no_anchor";s:8:"%htitle%";s:39:"bpost_homepage_carousel_archive_display";b:0;s:28:"apost_homepage_carousel_root";i:0;s:40:"bpost_homepage_carousel_taxonomy_display";b:0;s:37:"Spost_homepage_carousel_taxonomy_type";s:4:"date";s:32:"Hpost_homepage_showcase_template";s:52:"<a title="Go to %title%." href="%link%">%htitle%</a>";s:42:"Hpost_homepage_showcase_template_no_anchor";s:8:"%htitle%";s:39:"bpost_homepage_showcase_archive_display";b:0;s:28:"apost_homepage_showcase_root";i:0;s:40:"bpost_homepage_showcase_taxonomy_display";b:0;s:37:"Spost_homepage_showcase_taxonomy_type";s:4:"date";s:34:"Hpost_collectors_question_template";s:52:"<a title="Go to %title%." href="%link%">%htitle%</a>";s:44:"Hpost_collectors_question_template_no_anchor";s:8:"%htitle%";s:41:"bpost_collectors_question_archive_display";b:0;s:30:"apost_collectors_question_root";i:0;s:42:"bpost_collectors_question_taxonomy_display";b:0;s:39:"Spost_collectors_question_taxonomy_type";s:4:"date";s:34:"Hpost_marketplace_explore_template";s:52:"<a title="Go to %title%." href="%link%">%htitle%</a>";s:44:"Hpost_marketplace_explore_template_no_anchor";s:8:"%htitle%";s:41:"bpost_marketplace_explore_archive_display";b:0;s:30:"apost_marketplace_explore_root";i:0;s:42:"bpost_marketplace_explore_taxonomy_display";b:0;s:39:"Spost_marketplace_explore_taxonomy_type";s:4:"date";s:35:"Hpost_marketplace_featured_template";s:52:"<a title="Go to %title%." href="%link%">%htitle%</a>";s:45:"Hpost_marketplace_featured_template_no_anchor";s:8:"%htitle%";s:42:"bpost_marketplace_featured_archive_display";b:0;s:31:"apost_marketplace_featured_root";i:0;s:43:"bpost_marketplace_featured_taxonomy_display";b:0;s:40:"Spost_marketplace_featured_taxonomy_type";s:4:"date";s:34:"Hpost_collections_explore_template";s:52:"<a title="Go to %title%." href="%link%">%htitle%</a>";s:44:"Hpost_collections_explore_template_no_anchor";s:8:"%htitle%";s:41:"bpost_collections_explore_archive_display";b:0;s:30:"apost_collections_explore_root";s:0:"";s:42:"bpost_collections_explore_taxonomy_display";b:0;s:39:"Spost_collections_explore_taxonomy_type";s:4:"date";s:28:"Hpost_featured_week_template";s:52:"<a title="Go to %title%." href="%link%">%htitle%</a>";s:38:"Hpost_featured_week_template_no_anchor";s:8:"%htitle%";s:35:"bpost_featured_week_archive_display";b:0;s:24:"apost_featured_week_root";i:0;s:36:"bpost_featured_week_taxonomy_display";b:0;s:33:"Spost_featured_week_taxonomy_type";s:4:"date";s:31:"Hpost_seller_spotlight_template";s:52:"<a title="Go to %title%." href="%link%">%htitle%</a>";s:41:"Hpost_seller_spotlight_template_no_anchor";s:8:"%htitle%";s:38:"bpost_seller_spotlight_archive_display";b:0;s:27:"apost_seller_spotlight_root";s:0:"";s:39:"bpost_seller_spotlight_taxonomy_display";b:0;s:36:"Spost_seller_spotlight_taxonomy_type";s:4:"date";}\',
          `autoload` = \'yes\';

        REPLACE `wp_options` SET
          `blog_id` = 0,
          `option_name` = \'wpseo_xml\',
          `option_value` = \'a:11:{s:26:"ignore_blog_public_warning";s:0:"";s:11:"ignore_tour";s:6:"ignore";s:20:"ignore_page_comments";s:0:"";s:16:"ignore_permalink";s:0:"";s:15:"ms_defaults_set";s:0:"";s:44:"post_types-wpcf7_contact_form-not_in_sitemap";s:2:"on";s:34:"post_types-feedback-not_in_sitemap";s:2:"on";s:34:"post_types-cms_slot-not_in_sitemap";s:2:"on";s:43:"post_types-homepage_carousel-not_in_sitemap";s:2:"on";s:43:"post_types-homepage_showcase-not_in_sitemap";s:2:"on";s:45:"post_types-collectors_question-not_in_sitemap";s:2:"on";}\',
          `autoload` = \'yes\';
        REPLACE `wp_options` SET
          `blog_id` = 0,
          `option_name` = \'wpseo_indexation\',
          `option_value` = \'a:7:{s:26:"ignore_blog_public_warning";s:0:"";s:11:"ignore_tour";s:6:"ignore";s:20:"ignore_page_comments";s:0:"";s:16:"ignore_permalink";s:0:"";s:15:"ms_defaults_set";s:0:"";s:11:"hidersdlink";s:2:"on";s:15:"hidewlwmanifest";s:2:"on";}\',
          `autoload` = \'yes\';
        REPLACE `wp_options` SET
          `blog_id` = 0,
          `option_name` = \'wpseo_social\',
          `option_value` = \'a:4:{s:9:"opengraph";N;s:9:"fb_pageid";N;s:10:"fb_adminid";N;s:8:"fb_appid";N;}\',
          `autoload` = \'yes\';
        REPLACE `wp_options` SET
          `blog_id` = 0,
          `option_name` = \'wpseo_internallinks\',
          `option_value` = \'a:13:{s:26:"ignore_blog_public_warning";s:0:"";s:11:"ignore_tour";s:6:"ignore";s:20:"ignore_page_comments";s:0:"";s:16:"ignore_permalink";s:0:"";s:15:"ms_defaults_set";s:0:"";s:18:"breadcrumbs-enable";s:2:"on";s:15:"breadcrumbs-sep";s:3:"â†’";s:16:"breadcrumbs-home";s:4:"Blog";s:18:"breadcrumbs-prefix";s:0:"";s:25:"breadcrumbs-archiveprefix";s:0:"";s:24:"breadcrumbs-searchprefix";s:0:"";s:20:"breadcrumbs-404crumb";s:0:"";s:23:"post_types-post-maintax";s:1:"0";}\',
          `autoload` = \'yes\';
        REPLACE `wp_options` SET
          `blog_id` = 0,
          `option_name` = \'wpseo_titles\',
          `option_value` = \'a:63:{s:26:"ignore_blog_public_warning";s:0:"";s:11:"ignore_tour";s:6:"ignore";s:20:"ignore_page_comments";s:0:"";s:16:"ignore_permalink";s:0:"";s:15:"ms_defaults_set";s:0:"";s:10:"title-home";s:0:"";s:13:"metadesc-home";s:0:"";s:12:"metakey-home";s:0:"";s:10:"title-post";s:0:"";s:13:"metadesc-post";s:0:"";s:12:"metakey-post";s:0:"";s:10:"title-page";s:0:"";s:13:"metadesc-page";s:0:"";s:12:"metakey-page";s:0:"";s:16:"title-attachment";s:0:"";s:19:"metadesc-attachment";s:0:"";s:18:"metakey-attachment";s:0:"";s:24:"title-wpcf7_contact_form";s:0:"";s:27:"metadesc-wpcf7_contact_form";s:0:"";s:26:"metakey-wpcf7_contact_form";s:0:"";s:14:"title-feedback";s:0:"";s:17:"metadesc-feedback";s:0:"";s:16:"metakey-feedback";s:0:"";s:14:"title-cms_slot";s:0:"";s:17:"metadesc-cms_slot";s:0:"";s:16:"metakey-cms_slot";s:0:"";s:23:"title-homepage_carousel";s:0:"";s:26:"metadesc-homepage_carousel";s:0:"";s:25:"metakey-homepage_carousel";s:0:"";s:23:"title-homepage_showcase";s:0:"";s:26:"metadesc-homepage_showcase";s:0:"";s:25:"metakey-homepage_showcase";s:0:"";s:25:"title-collectors_question";s:0:"";s:28:"metadesc-collectors_question";s:0:"";s:27:"metakey-collectors_question";s:0:"";s:25:"title-marketplace_explore";s:0:"";s:28:"metadesc-marketplace_explore";s:0:"";s:27:"metakey-marketplace_explore";s:0:"";s:26:"title-marketplace_featured";s:0:"";s:29:"metadesc-marketplace_featured";s:0:"";s:28:"metakey-marketplace_featured";s:0:"";s:25:"title-collections_explore";s:0:"";s:28:"metadesc-collections_explore";s:0:"";s:27:"metakey-collections_explore";s:0:"";s:19:"title-featured_week";s:0:"";s:22:"metadesc-featured_week";s:0:"";s:21:"metakey-featured_week";s:0:"";s:22:"title-seller_spotlight";s:0:"";s:25:"metadesc-seller_spotlight";s:0:"";s:24:"metakey-seller_spotlight";s:0:"";s:14:"title-category";s:0:"";s:17:"metadesc-category";s:0:"";s:16:"metakey-category";s:0:"";s:14:"title-post_tag";s:0:"";s:17:"metadesc-post_tag";s:0:"";s:16:"metakey-post_tag";s:0:"";s:12:"title-author";s:0:"";s:15:"metadesc-author";s:0:"";s:14:"metakey-author";s:0:"";s:13:"title-archive";s:0:"";s:16:"metadesc-archive";s:0:"";s:12:"title-search";s:0:"";s:9:"title-404";s:0:"";}\',
          `autoload` = \'yes\';
        REPLACE `wp_options` SET
          `blog_id` = 0,
          `option_name` = \'wpseo\',
          `option_value` = \'a:22:{s:26:"ignore_blog_public_warning";s:0:"";s:11:"ignore_tour";s:6:"ignore";s:20:"ignore_page_comments";s:0:"";s:16:"ignore_permalink";s:0:"";s:15:"ms_defaults_set";s:0:"";s:15:"usemetakeywords";s:2:"on";s:22:"hideeditbox-attachment";s:2:"on";s:30:"hideeditbox-wpcf7_contact_form";s:2:"on";s:20:"hideeditbox-feedback";s:2:"on";s:20:"hideeditbox-cms_slot";s:2:"on";s:29:"hideeditbox-homepage_carousel";s:2:"on";s:29:"hideeditbox-homepage_showcase";s:2:"on";s:31:"hideeditbox-collectors_question";s:2:"on";s:31:"hideeditbox-marketplace_explore";s:2:"on";s:32:"hideeditbox-marketplace_featured";s:2:"on";s:31:"hideeditbox-collections_explore";s:2:"on";s:25:"hideeditbox-featured_week";s:2:"on";s:28:"hideeditbox-seller_spotlight";s:2:"on";s:12:"googleverify";s:0:"";s:8:"msverify";s:0:"";s:11:"alexaverify";s:0:"";s:7:"version";s:5:"1.1.5";}\',
          `autoload` = \'yes\';
      '
    );
  }

  /**
   * Get the SQL statements for the Down migration
   *
   * @return array list of the SQL strings to execute for the Down migration
   *               the keys being the datasources
   */
  public function getDownSQL()
  {
    return array(
      'blog'  => '
        # This is a fix for InnoDB in MySQL >= 4.1.x
        # It "suspends judgement" for fkey relationships until are tables are set.
        SET FOREIGN_KEY_CHECKS = 0;

        # This restores the fkey checks, after having unset them earlier
        SET FOREIGN_KEY_CHECKS = 1;
      '
    );
  }

}

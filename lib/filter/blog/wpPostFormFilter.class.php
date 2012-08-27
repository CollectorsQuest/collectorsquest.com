<?php

/**
 * wpPost filter form.
 *
 * @package    CollectorsQuest
 * @subpackage filter
 * @author     Kiril Angov
 * @version    SVN: $Id: sfPropelFormFilterTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class wpPostFormFilter extends BasewpPostFormFilter
{
  /**
   * allowed values of post status as per:
   * http://codex.wordpress.org/Post_Status_Transitions
   */
  protected static $post_status_valus = array(
      '' => 'All statuses',
      'publish' => 'Published', // default selection
      'draft' => 'Draft',
  );

  /**
   * allowed values of post_type as per:
   * http://codex.wordpress.org/Function_Reference/wp_insert_post
   */
  protected static $post_type_values = array(
      '' => 'All types',
      'post' => 'Post', // default selection
      'page' => 'Page',
      'link' => 'Link',
      'nav_menu_item' => 'Navigation Menu Item',
      // and custom post types, but we won't handle those
  );


  public function configure()
  {
    $this->unsetFields();

    $this->setupPostStatusField();
    $this->setupPostTypeField();
    $this->setupPostDateField();

    $this->widgetSchema['post_author'] = new sfWidgetFormPropelChoice(array(
      'model' => 'wpUser', 'add_empty' => true,
      'query_methods' => array('orderByDisplayName')
    ));

    $this->widgetSchema->setLabels(array(
        'post_author' => 'Author',
        'post_status' => 'Status',
        'post_content' => 'Search body',
        'post_type' => 'Type',
        'post_title' => 'Search title',
    ));
  }

  protected function setupPostStatusField()
  {
    $this->widgetSchema['post_status'] = new sfWidgetFormChoice(array(
        'choices' => self::$post_status_valus,
    ));

    $this->validatorSchema['post_status'] = new sfValidatorChoice(array(
        'choices' => array_keys(self::$post_status_valus),
        'required' => false,
    ));
  }

  protected function setupPostTypeField()
  {
    $this->widgetSchema['post_type'] = new sfWidgetFormChoice(array(
        'choices' => self::$post_type_values,
    ));

    $this->validatorSchema['post_type'] = new sfValidatorChoice(array(
        'choices' => array_keys(self::$post_type_values),
        'required' => false,
    ));
  }

  protected function setupPostDateField()
  {
    $this->widgetSchema['post_date'] = new sfWidgetFormJQueryDateRange(array(
      'config' => '{}',
    ));
    $this->validatorSchema['post_date'] = new IceValidatorDateRange(array(
      'required' => false, 'from_date' => 'from', 'to_date' => 'to'
    ));
  }

  public function unsetFields()
  {
    unset(
      $this['post_date_gmt'],
      $this['post_excerpt'],
      $this['comment_status'],
      $this['ping_status'],
      $this['post_password'],
      $this['post_name'],
      $this['to_ping'],
      $this['pinged'],
      $this['post_modified'],
      $this['post_modified_gmt'],
      $this['post_content_filtered'],
      $this['post_parent'],
      $this['guid'],
      $this['menu_order'],
      $this['post_mime_type'],
      $this['comment_count']
    );
  }

}

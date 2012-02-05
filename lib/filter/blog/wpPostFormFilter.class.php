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
      'publish' => 'publish', // default selection
      'new' => 'new',
      'pending' => 'pending',
      'draft' => 'draft',
      'auto-draft' => 'auto-draft',
      'future' => 'future',
      'private' => 'private',
      'inherit' => 'inherit',
      'trash' => 'trash',
      '' => 'All statuses',
  );

  /**
   * allowed values of post_type as per:
   * http://codex.wordpress.org/Function_Reference/wp_insert_post
   */
  protected static $post_type_values = array(
      'post' => 'post', // default selection
      'page' => 'page',
      'link' => 'link',
      'nav_menu_item' => 'nav_menu_item',
      '' => 'All types',
      // and custom post types, but we won't handle those
  );


  public function configure()
  {
    $this->unsetFields();

    $this->setupPostStatusField();
    $this->setupPostTypeField();
    $this->setupPostDateField();

    $this->widgetSchema['post_author']->setOption('add_empty', true);
  }


  protected function setupPostStatusField()
  {
    $this->widgetSchema['post_status'] = new sfWidgetFormChoice(array(
        'choices' => self::$post_status_valus,
    ));

    $this->validatorSchema['post_status'] = new sfValidatorChoice(array(
        'choices' => array_values(self::$post_status_valus),
        'required' => false,
    ));
  }


  protected function setupPostTypeField()
  {
    $this->widgetSchema['post_type'] = new sfWidgetFormChoice(array(
        'choices' => self::$post_type_values,
    ));

    $this->validatorSchema['post_type'] = new sfValidatorChoice(array(
        'choices' => array_values(self::$post_type_values),
        'required' => false,
    ));
  }


  protected function setupPostDateField()
  {
      $this->widgetSchema['post_date'] = new sfWidgetFormFilterDate(array(
          'from_date' => new sfWidgetFormJQueryDate(),
          'to_date' => new sfWidgetFormJQueryDate(),
          'with_empty' => false,
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

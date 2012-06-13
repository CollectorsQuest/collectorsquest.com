<?php


/**
 * Adds CQ_Sub_Pages_widget widget.
 */
class cq_sub_pages_widget extends WP_Widget {

  /**
   * Register widget with WordPress.
   */
  public function __construct() {
    parent::__construct(
      'cq_sub_pages_widget', // Base ID
      'CQ Sub-Pages', // Name
      array( 'description' => __( 'This is the sub-page widget.', 'text_domain' ), ) // Args
    );
  }

  /**
   * Front-end display of widget.
   *
   * @see WP_Widget::widget()
   *
   * @param array $args     Widget arguments.
   * @param array $instance Saved values from database.
   */
  public function widget( $args, $instance ) { ?>

    <?php
    global $post;

    if (!is_page('Pages')) :

    extract( $args );
    $title = apply_filters( 'widget_title', $instance['title'] );

    echo $before_widget;

    if($post->post_parent):
      $title1 = get_the_title($post->post_parent);
    else :
      $title1 = get_the_title($post->ID);
    endif;

    if ( ! empty( $title ) ) :
      //echo $before_title . $title . $after_title;
    ?>
<!--
    <div class="row-fluid sidebar-title">
      <div class="span12">
        <h3 class="Chivo webfont" style="visibility: visible;"><?php echo $title1; ?></h3>
      </div>
    </div>
-->
      <?php else : ?>
<!--
    <div class="row-fluid sidebar-title">
      <div class="span12">
        <h3 class="Chivo webfont" style="visibility: visible;"><?php echo $title1; //Other News?></h3>
      </div>
    </div>
-->
      <?php endif; ?>

  <?php if($post->post_parent):

      $post_arch = get_top_ancestor($post->ID);

      ?>
    <?php $children = wp_list_pages('depth=4&title_li=&child_of='.$post_arch.'&echo=0'); ?>
    <?php else: ?>
    <?php $children = wp_list_pages('depth=2&title_li=&child_of='.$post->ID.'&echo=0'); ?>
    <?php endif; ?>
  <?php if ($children) : ?>
  <?php $children = str_replace('current_page_item', 'current_page_item active', $children); ?>
  <?php $children = str_replace('current_page_ancestor', 'current_page_ancestor active', $children); ?>
      <div class="tabbable tabs-right">
    <ul class="nav nav-tabs sub-page-list">
      <?php echo $children; ?>
    </ul>
      </div>
    <?php endif; ?>

  <?php
    echo $after_widget;

  endif;

  }

  /**
   * Sanitize widget form values as they are saved.
   *
   * @see WP_Widget::update()
   *
   * @param array $new_instance Values just sent to be saved.
   * @param array $old_instance Previously saved values from database.
   *
   * @return array Updated safe values to be saved.
   */
  public function update( $new_instance, $old_instance ) {
    $instance = array();
    $instance['title'] = strip_tags( $new_instance['title'] );

    return $instance;
  }

  /**
   * Back-end widget form.
   *
   * @see WP_Widget::form()
   *
   * @param array $instance Previously saved values from database.
   */
  public function form( $instance ) {
    if ( isset( $instance[ 'title' ] ) ) {
      $title = $instance[ 'title' ];
    }
    else {
      $title = __( 'Other Pages', 'text_domain' );
    }
    ?>
  <p>
    <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
  </p>
  <?php
  }

} // class CQ_Sub_Pages_widget

// register Foo_Widget widget
add_action( 'widgets_init', create_function( '', 'register_widget( "cq_sub_pages_widget" );' ) );

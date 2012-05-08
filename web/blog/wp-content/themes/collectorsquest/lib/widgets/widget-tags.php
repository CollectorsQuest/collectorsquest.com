<?php


/**
 * Adds CQ_Tags_widget widget.
 */
class CQ_Tags_widget extends WP_Widget {

  /**
   * Register widget with WordPress.
   */
  public function __construct() {
    parent::__construct(
      'cq_tags_widget', // Base ID
      'CQ Tags', // Name
      array( 'description' => __( 'This is the tag widget.', 'text_domain' ), ) // Args
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
    extract( $args );
    $title = apply_filters( 'widget_title', $instance['title'] );

    echo $before_widget;
    if ( ! empty( $title ) ) :
      //echo $before_title . $title . $after_title;
    ?>


    <div class="row-fluid sidebar-title">
      <div class="span8">
        <h3 class="Chivo webfont" style="visibility: visible;"><?php echo $title ?></h3>
      </div>
      <!-- <div class="span4 text-right">
        <a href="/blog" class="text-v-middle link-align">See all news »</a>&nbsp;
      </div>-->
    </div>

      <?php else : ?>

    <div class="row-fluid sidebar-title">
      <div class="span8">
        <h3 class="Chivo webfont" style="visibility: visible;">Tags</h3>
      </div>
      <!-- <div class="span4 text-right">
        <a href="/blog" class="text-v-middle link-align">See all news »</a>&nbsp;
      </div>-->
    </div>

      <?php endif; ?>
  <p><?php the_tags('<ul class="cf" style="list-style: none; padding: 0; margin: 0;"><li>','</li><li>','</li></ul>'); ?></p>

  <?php
    echo $after_widget;

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
      $title = __( 'Tags', 'text_domain' );
    }
    ?>
  <p>
    <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
  </p>
  <?php
  }

} // class CQ_Tags_widget

// register Foo_Widget widget
add_action( 'widgets_init', create_function( '', 'register_widget( "CQ_Tags_widget" );' ) );

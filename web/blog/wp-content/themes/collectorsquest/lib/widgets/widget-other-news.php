<?php


/**
 * Adds CQ_Other_News_widget widget.
 */
class CQ_Other_News_widget extends WP_Widget {

  /**
   * Register widget with WordPress.
   */
  public function __construct() {
    parent::__construct(
      'CQ_Other_News_widget', // Base ID
      'CQ In Other News', // Name
      array( 'description' => __( 'This is the Other News" widget.', 'text_domain' ), ) // Args
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

    <li id="widget-other-news" class="widget">

      <div class="row-fluid sidebar-title">
        <div class="span8">
          <h3 class="Chivo webfont" style="visibility: visible;"><?php echo $title ?></h3>
        </div>
        <div class="span4 text-right">
          <a href="/blog" class="text-v-middle link-align">See all posts »</a>&nbsp;
        </div>
      </div>

      <?php endif; ?>

      <?php if (is_single()) : $offset = 0; else : $offset = 7; endif; ?>
      <?php $postss = get_posts("showposts=3"); ?>

      <?php foreach($postss as $post) { setup_postdata($post); ?>
      <div class="row-fluid bottom-margin">
        <h4 style="margin-bottom: 5px;">
          <a href="<?php the_permalink() ?>"><?php the_title(); ?></a>
        </h4>
        <span class="content">
          <?php $length=100; $longString=get_the_excerpt('...more'); $truncated = substr($longString,0,strpos($longString,' ',$length)); echo $truncated.'... ' //.'... <a href="'.get_permalink().'">more</a>'; ?>
        </span>
        <small style="font-size: 80%">posted by <?php the_author_posts_link() ?> <span style="color: grey"><?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' ago'; ?></span></small>
      </div>
      <?php } ?>
    </li>

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
      $title = __( 'In Other News', 'text_domain' );
    }
    ?>
  <p>
    <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
  </p>
  <?php
  }

} // class CQ_Other_News_widget

// register Foo_Widget widget
add_action( 'widgets_init', create_function( '', 'register_widget( "CQ_Other_News_widget" );' ) );

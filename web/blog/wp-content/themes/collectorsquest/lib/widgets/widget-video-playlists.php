<?php


/**
 * Adds CQ_Video_Playlists_widget widget.
 */
class cq_video_playlists_widget extends WP_Widget {

  /**
   * Register widget with WordPress.
   */
  public function __construct() {
    parent::__construct(
      'cq_video_playlists_widget', // Base ID
      'Video Category', // Name
      array( 'description' => __( 'Video playlists widget.', 'text_domain' ), ) // Args
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
  public function widget( $args, $instance ) {
    echo $before_widget; ?>
    <div class="row-fluid sidebar-title">
      <div class="span8">
        <h3 class="Chivo webfont" style="visibility: visible;">Category</h3>
      </div>
    </div>
    <div class="playlists-container-sidebar">
      <ul class="nav nav-list">
        <?php echo str_replace('</a>', '<i class="icon-chevron-right"></i></a>',
          wp_list_categories(array('taxonomy' => 'playlist', 'hierarchical' => 0, 'title_li' => '', 'echo' => 0))); ?>
      </ul>
    </div>
    <?php
    echo $after_widget;

  }

} // class CQ_Video_Playlists_widget

// register Foo_Widget widget
add_action( 'widgets_init', create_function( '', 'register_widget( "cq_video_playlists_widget" );' ) );

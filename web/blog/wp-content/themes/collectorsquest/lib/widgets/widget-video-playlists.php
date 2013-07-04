<?php


/**
 * Adds CQ_Playlists_widget widget.
 */
class cq_playlists_widget extends WP_Widget {

  /**
   * Register widget with WordPress.
   */
  public function __construct() {
    parent::__construct(
      'cq_playlists_widget', // Base ID
      'Video Playlists', // Name
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
        <h3 class="Chivo webfont" style="visibility: visible;">Playlists</h3>
      </div>
    </div>


  <div class="playlists-container-sidebar">
    <ul>
      <li><a href="">Music</a></li>
      <li><a href="">Music</a></li>
      <li><a href="">Music</a></li>
      <li><a href="">Music</a></li>
      <li><a href="">Music</a></li>
      <li><a href="">Music</a></li>
      <li><a href="">Music</a></li>
    </ul>
  </div>

  <?php
    echo $after_widget;

  }



} // class CQ_Tags_widget

// register Foo_Widget widget
add_action( 'widgets_init', create_function( '', 'register_widget( "cq_playlists_widget" );' ) );

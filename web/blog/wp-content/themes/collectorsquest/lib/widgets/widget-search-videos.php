<?php
/**
 * Adds CQ_Search_Videos_widget widget.
 */
class cq_search_videos_widget extends WP_Widget {

  /**
   * Register widget with WordPress.
   */
  public function __construct() {
    parent::__construct(
      'cq_search_videos_widget', // Base ID
      'Search Videos Box', // Name
      array( 'description' => __( 'Input box for video search.', 'text_domain' ), ) // Args
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
        <h3 class="Chivo webfont" style="visibility: visible; white-space: nowrap">Search Videos</h3>
      </div>
    </div>
    <div class="search-videos-sidebar">
      <form id="searchform" action="<?php echo home_url( '/' ); ?>" method="get" class="form-search">
        <div class="input-append">
          <input type="text" class="span2 search-query" name="s" value="<?php the_search_query(); ?>"
                 placeholder="Enter a keyword">
          <button type="submit" class="btn">Go</button>
        </div>
       <input type="hidden" name="post_type" value="video" />
      </form>
    </div>
    <?php
    echo $after_widget;
  }

} // class CQ_Search_Videos_widget

// register Foo_Widget widget
add_action( 'widgets_init', create_function( '', 'register_widget( "cq_search_videos_widget" );' ) );

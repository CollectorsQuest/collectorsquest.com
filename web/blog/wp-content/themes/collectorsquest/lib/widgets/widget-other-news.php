<?php


/**
 * Adds CQ_Other_News_widget widget.
 */
class cq_other_news_widget extends WP_Widget {

  /**
   * Register widget with WordPress.
   */
  public function __construct() {
    parent::__construct(
      'cq_other_news_widget', // Base ID
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
    if (!empty($title)):
      //echo $before_title . $title . $after_title;
    ?>

      <div class="row-fluid sidebar-title">
        <div class="span8">
          <h3 class="Chivo webfont" style="visibility: visible;"><?php echo $title ?></h3>
        </div>
        <div class="span4 text-right">
          <a href="/blog" class="text-v-middle link-align">See all posts »</a>&nbsp;
        </div>
      </div>

    <?php else: ?>

      <div class="row-fluid sidebar-title">
        <div class="span8">
          <h3 class="Chivo webfont" style="visibility: visible;">In Other News</h3>
        </div>
        <div class="span4 text-right">
          <a href="/blog" class="text-v-middle link-align">See all posts »</a>&nbsp;
        </div>
      </div>

    <?php endif; ?>

    <?php
      global $post;
      // determine the ID of the post being displayed, we don't want to show the same one in widget
      $post_id = $post->ID;

      // find the tags associated with the post and construct tag string to match posts with
      $tags = array();

      if ($matching = get_the_terms($post_id, 'matching'))
      {
        foreach ($matching as $tag)
        {
          $tags[] = $tag->slug;
        }
        $tag_string = implode(',', $tags);
      }
      else if ($posttags = get_the_tags())
      {
        foreach ($posttags as $tag)
        {
          $tags[] = $tag->slug;
        }
        $tag_string = implode(',', $tags);
      }
      else
      {
        $tag_string = null;
      }

      // construct WP_Query to find posts based on tag matching
      $args = array(
        'post_type' => 'post',
        'post__not_in' => array($post->ID),
        'post_status' => 'publish',
        'tag' => $tag_string,
        'showposts' => 2,
        'caller_get_posts' => 1,
        'orderby' => 'post_date',
        'order' => 'DESC'
      );
      $the_query = new WP_Query($args);

      // display posts based on tag matching
    ?>
    <?php while($the_query->have_posts()): $the_query->the_post(); ?>
      <div class="row-fluid bottom-margin">
        <h4 style="margin-bottom: 5px;">
          <a href="<?php the_permalink() ?>"><?php the_title(); ?></a>
        </h4>
        <span class="content">
          <?php
            $length = 100;
            $longString = get_the_excerpt('...more');
            $truncated = substr($longString, 0, strpos($longString, ' ', $length));

            echo $truncated . '... ';
          ?>
        </span>
        <small style="font-size: 80%">
          <span style="color: grey">
            Posted by <?php the_author_posts_link() ?>
            <?= 'on '.get_the_date('M dS, Y'); ?>
          </span>
        </small>
      </div>
    <?php endwhile; ?>

    <?php
      /*
       * display posts based on category matching
       *
       * determine how many posts are left to display, ideally we want to display 1
       * if we did not manage to find posts by matching tags we display up to 3
       */
      $showposts = 3 - $the_query->post_count;

      // get category IDs of the post in a comma separated string
      $cats = get_the_category($post_id);
      $cats_array = array();
      if ($cats)
      {
        foreach ($cats as $cat)
        {
          $cats_array[] = $cat->ID;
        }
      }
      $cats = implode(',', $cats_array);

      // get the posts based on category matching
      $args = array(
        'showposts' => $showposts,
        'category' => $cats,
        'exclude' => $post_id,
        'orderby' => 'post_date',
        'order' => 'DESC'
      );
      $posts = get_posts( $args );
    ?>

    <?php foreach($posts as $post) { setup_postdata($post); ?>
      <div class="row-fluid bottom-margin">
        <h4 style="margin-bottom: 5px;">
          <a href="<?php the_permalink() ?>"><?php the_title(); ?></a>
        </h4>
        <span class="content">
          <?php
            $length=100;
            $longString=get_the_excerpt('...more');
            $truncated = substr($longString, 0, strpos($longString, ' ', $length));
            echo $truncated.'... ' //.'... <a href="'.get_permalink().'">more</a>';
          ?>
        </span>
        <small style="font-size: 80%">
          <span style="color: grey">
            Posted by <?php the_author_posts_link() ?>
            <?= 'on '.get_the_date('M dS, Y'); ?>
          </span>
        </small>
      </div>
    <?php } ?>

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
add_action( 'widgets_init', create_function( '', 'register_widget( "cq_other_news_widget" );' ) );

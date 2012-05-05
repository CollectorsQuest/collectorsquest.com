<?php


/**
 * Adds CQ_Our_Bloggers_widget widget.
 */
class CQ_Our_Bloggers_widget extends WP_Widget {

  /**
   * Register widget with WordPress.
   */
  public function __construct() {
    parent::__construct(
      'CQ_Our_Bloggers_widget', // Base ID
      'CQ Bloggers', // Name
      array( 'description' => __( 'This is the "Our Bloggers" widget.', 'text_domain' ), ) // Args
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
        <!--<div class="span4 text-right">
          <a href="/blog" class="text-v-middle link-align">See all news »</a>&nbsp;
        </div>-->
      </div>

    <?php else : ?>

    <div class="row-fluid sidebar-title">
      <div class="span8">
        <h3 class="Chivo webfont" style="visibility: visible;">Our Bloggers</h3>
      </div>
      <!--<div class="span4 text-right">
        <a href="/blog" class="text-v-middle link-align">See all news »</a>&nbsp;
      </div>-->
    </div>

    <?php endif; ?>

      <?php
      $display_admins = false;
      $order_by = 'display_name'; // 'nicename', 'email', 'url', 'registered', 'display_name', or 'post_count'
      $role = 'author'; // 'subscriber', 'contributor', 'editor', 'author' - leave blank for 'all'
      $avatar_size = 40;
      $hide_empty = true; // hides authors with zero posts

      if (!empty($display_admins)) {
        $blogusers = get_users('orderby=' . $order_by . '&role=' . $role);
      } else {
        $admins = get_users('role=administrator');
        $exclude = array();
        foreach ($admins as $ad) {
          $exclude[] = $ad->ID;
        }
        $exclude = implode(',', $exclude);
        $exclude = str_replace(",7", "", $exclude);
        $blogusers = get_users('exclude=' . $exclude . ',6,13,11');
      }
      $authors = array();
      foreach ($blogusers as $bloguser) {
        $user = get_userdata($bloguser->ID);
        if (!empty($hide_empty)) {
          $numposts = count_user_posts($user->ID);
          if ($numposts < 1) continue;
        }
        $authors[] = (array)$user;
      }

      echo '<ul class="author-list row-fluid">';
      foreach ($authors as $author) {
        $display_name = $author['data']->display_name;
        $avatar = get_avatar($author['ID'], $avatar_size);
        $author_posts_url = get_author_posts_url($author['ID']);
        $author_profile_url = get_the_author_meta('user_url', $author['ID']);
        $nice_name = get_the_author_meta('user_nicename', $author['ID']);
        //echo '<li><a href="', $author_profile_url, '">', $avatar, '</a><strong>' . $display_name . '</strong><br /><a href="/blog/people/', $nice_name, '" class="author-link">[Bio]</a> <a href="', $author_posts_url, '" class="contributor-link">[Articles]</a></li>';
        echo '<li class="row-fluid bottom-margin"><a href="', $author_posts_url, '">', $avatar, '<span class="author-name">' . $display_name . '</span></a></li>';
        echo '';

      }
      echo '</ul>';
      ?>


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
      $title = __( 'Our Bloggers', 'text_domain' );
    }
    ?>
  <p>
    <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
  </p>
  <?php
  }

} // class CQ_Our_Bloggers_widget

// register Foo_Widget widget
add_action( 'widgets_init', create_function( '', 'register_widget( "CQ_Our_Bloggers_widget" );' ) );

<?php


/**
 * Adds CQ_300x250ad_widget widget.
 */
class cq_300x250ad_widget extends WP_Widget {

  /**
   * Register widget with WordPress.
   */
  public function __construct() {
    parent::__construct(
      'cq_300x250ad_widget', // Base ID
      'CQ 300x250 Advertisement', // Name
      array( 'description' => __( 'This is the 300x250 ad unit.', 'text_domain' ), ) // Args
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
    extract( $args );
    $title = apply_filters( 'widget_title', $instance['title'] );

    echo $before_widget;
    if ( ! empty( $title ) )
      //echo $before_title . $title . $after_title;
    ?>

    <?php
    if (is_author() || is_single()) :
      $authvar = get_author_posts_url(get_the_author_meta('ID'));
      $authvar = explode('/',$authvar);
      end($authvar);
      $authvar = prev($authvar);
    endif;
    ?>


    <script>
      if (typeof window.dfp_ord == 'undefined') { window.dfp_ord = Math.random() * 10000000000000000; }
      if (typeof(window.dfp_tile) == 'undefined') { window.dfp_tile = 1; }

      var src = 'http://ad.doubleclick.net/adj/aetn.hist.cq/blog/<?php echo $authvar ?>;s1=blog;s2=<?php echo $authvar ?>;kw=;test=;aetn=ad;pos=sidebar;dcopt=;sz=300x250';
      if (window.dfp_tile === 1) {
        src = src.replace(/pos=\w+/i, 'pos=top');
        src = src.replace(/dcopt=;/i, 'dcopt=ist;');
      }
      src = src +';tile='+ (window.dfp_tile++) +';ord='+ window.dfp_ord +'?';

      document.write('<script language="JavaScript" src="'+ src +'" type="text/javascript"><\/script>');
    </script>
    <noscript>
      <a href="http://ad.doubleclick.net/jump/aetn.hist.cq/blog/<?php echo $authvar ?>;s1=blog;s2=<?php echo $authvar ?>;kw=;test=;aetn=ad;pos=sidebar;sz=300x250;tile=1;ord=123456789?" target="_blank">
        <img src="http://ad.doubleclick.net/adj/aetn.hist.cq/blog/<?php echo $authvar ?>;s1=blog;s2=<?php echo $authvar ?>;kw=;test=;aetn=ad;pos=sidebar;dcopt=;sz=300x250;tile=1;ord=123456789?" width="300" height="250" border="0" alt="">
      </a>
    </noscript>

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
      $title = __( 'Advertisement', 'text_domain' );
    }
    ?>
  <p>
    <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
  </p>
  <?php
  }

} // class CQ_300x250ad_widget

// register Foo_Widget widget
add_action( 'widgets_init', create_function( '', 'register_widget( "cq_300x250ad_widget" );' ) );

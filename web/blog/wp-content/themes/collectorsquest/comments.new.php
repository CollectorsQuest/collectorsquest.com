<?php // Do not delete these lines
	if ('comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

        if (!empty($post->post_password)) { // if there's a password
            if ($_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) {  // and it doesn't match the cookie
				?>

				<p class="nocomments">This post is password protected. Enter the password to view comments.<p>

				<?php
				return;
            }
        }

		/* This variable is for alternating comment background */
		$oddcomment = 'alt';
?>

<!-- You can start editing here. -->






<?php if ('open' == $post->comment_status) : ?>

  <!--<h3 align="left" id="respond"><b>Comment on</b></h3>-->
  <h3><?php comments_number('Comment', 'One Comment', '% Comments' );?> on &#8220;<?php the_title(); ?>&#8221;</h3>


  <?php if ( get_option('comment_registration') && !$user_ID ) : ?>
  <p>You must be <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php the_permalink(); ?>">logged in</a> to post a comment.</p>
  <?php else : ?>


  <div class="add-comment">
    <div class="post-comment">
      <form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">

        <?php if ( $user_ID ) : ?>

        <p>You are logged in as <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=logout" title="Log out of this account">Logout &raquo;</a></p>

        <?php else : ?>
        <div class="row-fluid comment-option-wrap">
          <p class="span4">
            <input class="span12" type="text" align="left" name="author" id="author" value="<?php echo $comment_author; ?>" size="22" tabindex="5" />
            <label for="author"><small>Name</small></label>
          </p>
          <p class="span4">
            <input class="span12" type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>"  />
            <label for="email"><small>Email (will not be published)</small></label>
          </p>
        </div>
        <?php endif; ?>

        <!--<p><small><strong>XHTML:</strong> You can use these tags: <?php echo allowed_tags(); ?></small></p>-->

        <textarea class="input-append" name="comment" id="c" rows="10" colspan="3" style="width: 494px; height: 18px;resize: none;"></textarea>
        <!-- <input class="input-append" type="text" id="c" data-provide="comment" autocomplete="off" name="comment">

        <!--<p><input name="submit" type="submit" id="submit" tabindex="5" value="Submit Comment" />-->
        <button type="submit" class="btn btn-large">Comment</button>

        <input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />

          <div class="comment-option-wrap">
        <?php do_action('comment_form', $post->ID); ?>
          </div>
        <div class="cf"></div>

      </form>
    </div>
  </div>

  <script>
    $(document).ready(function()
    {
      $('.post-comment textarea').focus(function()
      {
        $(this).css('height', '44px');
        $('.post-comment button').css('height', '60px');
        $('.comment-option-wrap').slideDown();
      });
    });
  </script>

  <?php if ( !$user_ID ) : ?>

    <?php endif ?>

  <?php endif; // If registration required and not logged in ?>








<?php if ($comments) : ?>

  <div class="user-comments">
    <div class="commentlist">
      <?php wp_list_comments('type=comment&callback=cq_comment&style=div&per_page=5'); ?>
    </div>
	</div>

  <?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
  <div id="<?php the_ID() ?>">
    <a id="load_comments" href="javascript:void(0);" class="btn btn-small gray-button see-more-full">See more</a>
  </div>
  <?php endif; ?>

 <?php else : // this is displayed if there are no comments so far ?>

  <?php if ('open' == $post->comment_status) : ?>
		<!-- If comments are open, but there are no comments. -->

	 <?php else : // comments are closed ?>
		<!-- If comments are closed. -->
		<p class="nocomments">Comments are closed.</p>

	<?php endif; ?>
<?php endif; ?>




<?php endif; // if you delete this the sky will fall on your head ?>

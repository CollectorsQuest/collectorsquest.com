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

  <!--<h3 align="left" id="respond"><b>Leave a Reply</b></h3>-->

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
            <label for="author"><small>Name <?php if ($req) echo "(required)"; ?></small></label>
          </p>
          <p class="span4">
            <input class="span12" type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>"  />
            <label for="email"><small>Mail (will not be published) <?php if ($req) echo "(required)"; ?></small></label>
          </p>
        </div>
        <?php endif; ?>

        <!--<p><small><strong>XHTML:</strong> You can use these tags: <?php echo allowed_tags(); ?></small></p>-->

        <textarea class="input-append" name="comment" id="c" rows="10" colspan="3" style="width: 476px; height: 23px;"></textarea>
        <!-- <input class="input-append" type="text" id="c" data-provide="comment" autocomplete="off" name="comment">

        <!--<p><input name="submit" type="submit" id="submit" tabindex="5" value="Submit Comment" />-->
        <button type="submit" class="btn btn-large">Comment</button>
        <a class="upload-photo" title="Add a photo">&nbsp;</a>

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
        $(this).css('height', '50px');
        $('.post-comment button').css('height', '60px');
        $('.comment-option-wrap').slideDown();
      });
    });
  </script>

  <?php if ( !$user_ID ) : ?>

    <?php endif ?>

  <?php endif; // If registration required and not logged in ?>








<?php if ($comments) : ?>
	<!-- <h3 id="comments"><?php comments_number('No Responses', 'One Response', '% Responses' );?> to &#8220;<?php the_title(); ?>&#8221;</h3> -->

  <div class="user-comments">

	<?php foreach ($comments as $comment) : ?>

		<!-- <li class="<?php echo $oddcomment; ?>" id="comment-<?php comment_ID() ?>"> -->
    <div class="row-fluid user-comment">
      <div class="span2 text-right">
        <a href="#">
          <?php echo get_avatar( $comment, 65 ); ?>
        </a>
      </div>
      <div class="span10">
        <p class="bubble left">
          <a href="#" class="username"><?php comment_author_link() ?></a>
          <?php if ($comment->comment_approved == '0') : ?>
          <em>Your comment is awaiting moderation.</em>
          <?php endif; ?>
          <br />
          <?php echo $comment->comment_content; ?>
          <span class="comment-time"><a href="#comment-<?php comment_ID() ?>" title=""><?php comment_date('F jS, Y') ?> at <?php comment_time() ?></a> <?php edit_comment_link('edit','',''); ?></span>
        </p>
      </div>
    </div>
		<!-- </li> -->

	<?php /* Changes every other comment to a different class */
		if ('alt' == $oddcomment) $oddcomment = '';
		else $oddcomment = 'alt';
	?>

	<?php endforeach; /* end for each comment */ ?>

	</div>

  <div class="see-more-under-image-set">
    <button class="btn btn-small gray-button see-more-full" id="see-more-comments">
      See all XX comments
    </button>
  </div>

 <?php else : // this is displayed if there are no comments so far ?>

  <?php if ('open' == $post->comment_status) : ?>
		<!-- If comments are open, but there are no comments. -->

	 <?php else : // comments are closed ?>
		<!-- If comments are closed. -->
		<p class="nocomments">Comments are closed.</p>

	<?php endif; ?>
<?php endif; ?>




<?php endif; // if you delete this the sky will fall on your head ?>

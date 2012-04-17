<div class="append-bottom list_view_post" style="margin-left: 20px;">
  <h4><?php echo link_to_blog_post($post); ?></h4>
  <blockquote><?php echo wpPostPeer::stripShortcodes(strip_tags($post->getPostExcerpt())); ?></blockquote>
</div>

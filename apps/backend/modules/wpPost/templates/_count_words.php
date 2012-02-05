<?php
  /* @var $wpPost wpPost */
  $wpPost;

  $post_text = strip_tags($wpPost->getPostContent());

  echo count(explode(' ', $post_text));
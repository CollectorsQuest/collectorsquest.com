<?php
  /* @var $wpPost wpPost */
  $wpPost;

  $post_text = strip_tags($wpPost->getPostContent());

  echo mb_strlen(str_replace(' ', '', $post_text), 'utf-8');
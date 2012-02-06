<?php
  /* @var $wpPost wpPost */
  $wpPost;

  echo link_to($wpPost->getPostTitle(), $wpPost->getPostUrl());
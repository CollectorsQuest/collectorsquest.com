<?php

require_once sfConfig::get('sf_lib_dir') . '/vendor/GravatarApi.class.php';

/**
 * Displays a gravatar image for a given email
 *
 * @author Clément Jobeili <clement.jobeili@gmail.com>
 * @see http://site.gravatar.com/site/implement#section_1_1
 *
 * @param  string   $email  Email of the gravatar
 * @param  integer  $size  Size of the gravatar
 * @param  string   $rating  Maximal rating of the gravatar
 * @param  string   $default
 * @param  string   $alt_text  Alternative text
 *
 * @return string
 */
function gravatar_image_tag($email, $size = 80, $rating = 'G', $default = '', $alt_text = 'Avatar')
{
  $avatar = image_tag(gravatar_image($email, $size, $rating, $default),
    array(
      'alt'    => $alt_text,
      'width'  => $size,
      'height' => $size,
      'class'  => 'gravatar_photo'
    ));

  return $avatar;
}

/**
 * Get the gravatar string for a given email
 *
 * @author Clément Jobeili <clement.jobeili@gmail.com>
 * @see http://site.gravatar.com/site/implement#section_1_1
 *
 * @param  string   $email  Email of the gravatar
 * @param  integer  $size  Size of the gravatar
 * @param  string   $rating  Maximal rating of the gravatar
 * @param  string   $default
 *
 * @return string
 */
function gravatar_image($email, $size = 80, $rating = null, $default = '')
{
  $gravatar = new GravatarApi();

  return $gravatar->get($email, $size, $rating, $default);
}

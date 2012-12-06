<?php

require 'lib/model/blog/om/BasewpPostPeer.php';

class wpPostPeer extends BasewpPostPeer
{
  public static function retrieveByTags($tags = array(), $limit = 0)
  {
    $c = new Criteria();
    $c->add(wpPostPeer::POST_TYPE, 'post');
    $c->addJoin(wpPostPeer::ID, wpTermRelationshipPeer::OBJECT_ID);
    $c->addJoin(wpTermRelationshipPeer::TERM_TAXONOMY_ID, wpTermTaxonomyPeer::TERM_TAXONOMY_ID);
    $c->addJoin(wpTermTaxonomyPeer::TERM_ID, wpTermPeer::TERM_ID);
    $c->add(wpTermTaxonomyPeer::TAXONOMY, 'post_tag');
    $c->add(wpTermPeer::NAME, $tags, Criteria::IN);
    $c->addGroupByColumn(wpPostPeer::ID);
    $c->addDescendingOrderByColumn(wpPostPeer::POST_DATE);
    $c->setLimit($limit);

    return wpPostPeer::doSelect($c);
  }

  /**
   * @static
   * @param  integer $limit
   *
   * @return array
   */
  public static function getLatestPosts($limit = 10)
  {
    $c = new Criteria();
    $c->add(wpPostPeer::POST_STATUS, 'publish');
    $c->add(wpPostPeer::POST_TYPE, 'post');
    $c->addDescendingOrderByColumn(wpPostPeer::POST_DATE);
    $c->setLimit($limit);

    return wpPostPeer::doSelect($c);
  }

  public static function stripShortcodes($content)
  {
    $tagnames = array('wp_caption', 'caption', 'gallery', 'embed', 'archives', 'audio');
    $tagregexp = join( '|', array_map('preg_quote', $tagnames) );

    $pattern =
      '\\['                              // Opening bracket
      . '(\\[?)'                           // 1: Optional second opening bracket for escaping shortcodes: [[tag]]
      . "($tagregexp)"                     // 2: Shortcode name
      . '\\b'                              // Word boundary
      . '('                                // 3: Unroll the loop: Inside the opening shortcode tag
      .     '[^\\]\\/]*'                   // Not a closing bracket or forward slash
      .     '(?:'
      .         '\\/(?!\\])'               // A forward slash not followed by a closing bracket
      .         '[^\\]\\/]*'               // Not a closing bracket or forward slash
      .     ')*?'
      . ')'
      . '(?:'
      .     '(\\/)'                        // 4: Self closing tag ...
      .     '\\]'                          // ... and closing bracket
      . '|'
      .     '\\]'                          // Closing bracket
      .     '(?:'
      .         '('                        // 5: Unroll the loop: Optionally, anything between the opening and closing shortcode tags
      .             '[^\\[]*+'             // Not an opening bracket
      .             '(?:'
      .                 '\\[(?!\\/\\2\\])' // An opening bracket not followed by the closing shortcode tag
      .                 '[^\\[]*+'         // Not an opening bracket
      .             ')*+'
      .         ')'
      .         '\\[\\/\\2\\]'             // Closing shortcode tag
      .     ')?'
      . ')'
      . '(\\]?)';                          // 6: Optional second closing brocket for escaping shortcodes: [[tag]]

    $strip_shortcode_tag = create_function('$m', 'if ( $m[1] == "[" && $m[6] == "]" ) { return substr($m[0], 1, -1); } return $m[1] . $m[6];');

    return preg_replace_callback("/$pattern/s", $strip_shortcode_tag, $content);
  }

  public static function sanitize($content)
  {
    // &hellip; , &#8230;
    $content = preg_replace('~\xC3\xA2\xE2\x82\xAC\xC2\xA6~', '&hellip;', $content);
    $content = preg_replace('~\xC3\x83\xC2\xA2\xC3\xA2\xE2\x80\x9A\xC2\xAC\xC3\x82\xC2\xA6~', '&hellip;', $content);
    $content = preg_replace('~\xD0\xB2\xD0\x82\xC2\xA6~', '&hellip;', $content);

    // &mdash; , &#8212;
    $content = preg_replace('~\xC3\xA2\xE2\x82\xAC\xE2\x80\x9D~', '&mdash;', $content);
    $content = preg_replace('~\xC3\x83\xC2\xA2\xC3\xA2\xE2\x80\x9A\xC2\xAC\xC3\xA2\xE2\x82\xAC\xC2\x9D~', '&mdash;', $content);
    $content = preg_replace('~\xD0\xB2\xD0\x82\xE2\x80\x9D~', '&mdash;', $content);

    // &ndash; , &#8211;
    $content = preg_replace('~\xC3\xA2\xE2\x82\xAC\xE2\x80\x9C~', '&ndash;', $content);
    $content = preg_replace('~\xC3\x83\xC2\xA2\xC3\xA2\xE2\x80\x9A\xC2\xAC\xC3\xA2\xE2\x82\xAC\xC5\x93~', '&ndash;', $content);
    $content = preg_replace('~\xD0\xB2\xD0\x82\xE2\x80\x9C~', '&ndash;', $content);

    // &rsquo; , &#8217;
    $content = preg_replace('~\xC3\xA2\xE2\x82\xAC\xE2\x84\xA2~', '&rsquo;', $content);
    $content = preg_replace('~\xC3\x83\xC2\xA2\xC3\xA2\xE2\x80\x9A\xC2\xAC\xC3\xA2\xE2\x80\x9E\xC2\xA2~', '&rsquo;', $content);
    $content = preg_replace('~\xD0\xB2\xD0\x82\xE2\x84\xA2~', '&rsquo;', $content);
    $content = preg_replace('~\xD0\xBF\xD1\x97\xD0\x85~', '&rsquo;', $content);

    // &lsquo; , &#8216;
    $content = preg_replace('~\xC3\xA2\xE2\x82\xAC\xCB\x9C~', '&lsquo;', $content);
    $content = preg_replace('~\xC3\x83\xC2\xA2\xC3\xA2\xE2\x80\x9A\xC2\xAC\xC3\x8B\xC5\x93~', '&lsquo;', $content);

    // &rdquo; , &#8221;
    $content = preg_replace('~\xC3\xA2\xE2\x82\xAC\xC2\x9D~', '&rdquo;', $content);
    $content = preg_replace('~\xC3\x83\xC2\xA2\xC3\xA2\xE2\x80\x9A\xC2\xAC\xC3\x82\xC2\x9D~', '&rdquo;', $content);
    $content = preg_replace('~\xD0\xB2\xD0\x82\xD1\x9C~', '&rdquo;', $content);

    // &ldquo; , &#8220;
    $content = preg_replace('~\xC3\xA2\xE2\x82\xAC\xC5\x93~', '&ldquo;', $content);
    $content = preg_replace('~\xC3\x83\xC2\xA2\xC3\xA2\xE2\x80\x9A\xC2\xAC\xC3\x85\xE2\x80\x9C~', '&ldquo;', $content);
    $content = preg_replace('~\xD0\xB2\xD0\x82\xD1\x9A~', '&ldquo;', $content);

    // &trade; , &#8482;
    $content = preg_replace('~\xC3\xA2\xE2\x80\x9E\xC2\xA2~', '&trade;', $content);
    $content = preg_replace('~\xC3\x83\xC2\xA2\xC3\xA2\xE2\x82\xAC\xC5\xBE\xC3\x82\xC2\xA2~', '&trade;', $content);

    // th
    $content = preg_replace('~t\xC3\x82\xC2\xADh~', 'th', $content);

    // .
    $content = preg_replace('~.\xD0\x92+~', '.', $content);
    $content = preg_replace('~.\xD0\x92~', '.', $content);

    // ,
    $content = preg_replace('~\x2C\xD0\x92~', ',', $content);

    // Â (http://stackoverflow.com/questions/1461907/html-encoding-issues-character-showing-up-instead-of-nbsp)
    $content = preg_replace('~Â ~', '&nbsp;', $content);

    return $content;
  }
}

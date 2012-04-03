<?php

require 'lib/model/blog/om/BasewpPostPeer.php';

class wpPostPeer extends BasewpPostPeer
{
  public static function retrieveByTags($tags = array(), $limit = 0)
  {
    $c = new Criteria();
    $c->setDistinct();
    $c->add(wpPostPeer::POST_TYPE, 'post');
    $c->addJoin(wpPostPeer::ID, wpTermRelationshipPeer::OBJECT_ID);
    $c->addJoin(wpTermRelationshipPeer::TERM_TAXONOMY_ID, wpTermTaxonomyPeer::TERM_TAXONOMY_ID);
    $c->addJoin(wpTermTaxonomyPeer::TERM_ID, wpTermPeer::TERM_ID);
    $c->add(wpTermTaxonomyPeer::TAXONOMY, 'post_tag');
    $c->add(wpTermPeer::NAME, $tags, Criteria::IN);
    $c->addDescendingOrderByColumn(wpPostPeer::POST_DATE);
    $c->setLimit($limit);

    return wpPostPeer::doSelect($c);
  }

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

}

<?php

class General
{
  public static function nameToUniq()
  {
    srand((double) microtime() * 1000000);

    $uniq = md5(uniqid(rand(), true));

    return $uniq;
  }

  public static function nameToSafe($name, $maxlen = 32)
  {
    $noalpha = 'ÁÉÍÓÚÝáéíóúýÂÊÎÔÛâêîôûÀÈÌÒÙàèìòùÄËÏÖÜäëïöüÿÃãÕõÅåÑñÇç@°ºªÞþÆæ';
    $alpha   = 'AEIOUYaeiouyAEIOUaeiouAEIOUaeiouAEIOUaeiouyAaOoAaNnCcaooaTtAa';

    $name = substr($name, 0, $maxlen);
    $name = strtr($name, $noalpha, $alpha);
    // not permitted chars are replaced with "_"
    return preg_replace('/[^a-zA-Z0-9,._\+\()\-]/', '_', $name);
  }

  public static function generate_password()
  {
    $config = array(
    	"C" => array('characters' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', 'minimum' => 4, 'maximum' => 6),
    	"N" => array('characters' => '1234567890', 'minimum' => 2, 'maximum' => 2)
    );

    // Create the meta-password
    $sMetaPassword = "";

    $ahPasswordGenerator = $config;
    foreach ($ahPasswordGenerator as $cToken => $ahPasswordSeed) {
      $sMetaPassword .= str_repeat($cToken, rand($ahPasswordSeed['minimum'], $ahPasswordSeed['maximum']));
    }

    $sMetaPassword = str_shuffle($sMetaPassword);

    // Create the real password
    $arBuffer = array();
    for ($i = 0; $i < strlen($sMetaPassword); $i ++) {
      $arBuffer[] = $ahPasswordGenerator[(string)$sMetaPassword[$i]]['characters'][rand(0, strlen($ahPasswordGenerator[$sMetaPassword[$i]]['characters']) - 1)];
    }

    return implode("", $arBuffer);
  }

  public static function getRSSLocation($html, $location)
  {
    if (!$html || !$location)
    {
      return false;
    }
    else
    {
      preg_match_all('/<link\s+(.*?)\s*\/?>/si', $html, $matches);
      $links = $matches[1];
      $final_links = array();
      $link_count = count($links);
      for ($n=0; $n<$link_count; $n++)
      {
        $attributes = preg_split('/\s+/s', $links[$n]);
        foreach($attributes as $attribute)
        {
          $att = preg_split('/\s*=\s*/s', $attribute, 2);
          if (isset($att[1]))
          {
            $att[1] = preg_replace('/([\'"]?)(.*)\1/', '$2', $att[1]);
            $final_link[strtolower($att[0])] = $att[1];
          }
        }
        $final_links[$n] = $final_link;
      }

      #now figure out which one points to the RSS file
      for ($n=0; $n<$link_count; $n++)
      {
        if (strtolower($final_links[$n]['rel']) == 'alternate')
        {
          $href = '';
          if (strtolower($final_links[$n]['type']) == 'application/rss+xml')
          {
            $href = $final_links[$n]['href'];
          }
          if(!$href && strtolower($final_links[$n]['type']) == 'text/xml')
          {
            #kludge to make the first version of this still work
            $href = $final_links[$n]['href'];
          }

          if($href)
          {
            if(strstr($href, "http://") !== false)
            {
              $full_url = $href;
            }
            else
            {
              $url_parts = parse_url($location);
              #only made it work for http:// links. Any problem with this?
              $full_url = "http://".$url_parts['host'];
              if (isset($url_parts['port'])){
                $full_url .= ":".$url_parts['port'];
              }
              if ($href{0} != '/')
              { #it's a relative link on the domain
                $full_url .= dirname($url_parts['path']);
                if(substr($full_url, -1) != '/')
                {
                  $full_url .= '/';
                }
              }
              $full_url .= $href;
            }
            return $full_url;
          }
        }
      }
      return false;
    }
  }

  public static function getBlogPosts($max = 5)
  {
    $cache = new sfArrayCache(sfConfig::get('sf_cache_dir'));
    $cache->setLifeTime(3600);

    $posts = $cache->get('blog_posts', 'global');
    if (!empty($posts)) {
      return $posts;
    }

    $feed = null;
    $feed_url = 'http://www.collectorsquest.com/blog/wp-rss2.php';
    $browser = new sfWebBrowser(array(
      'user_agent' => 'sfFeedReader/0.9',
      'timeout'    => 5
    ));

    $feed = sfFeedPeer::createFromWeb($feed_url);

    if (is_null($feed)) {
      return array();
    }

    $i = 0;
    $posts = array();
    foreach($feed->getItems() as $post)
    {
      $title = $post->getTitle();
      $url = $post->getLink();
      if (empty($title) || empty($url)) {
        continue;
      }
      $posts[] = array(
        'title' => $post->getTitle(),
        'description' => $post->getDescription(),
        'url' => $url,
        'author' => array('name' => $post->getAuthorName(), 'email' => $post->getAuthorEmail()),
        'date' => $post->getPubDate()
      );
      $i += ceil(strlen($title) / 50);
      if ($i >= $max) {
        break;
      }
    }

    $cache->set('blog_posts', 'global', $posts);

    return $posts;
  }

  public static function abs_url($path, $app = 'index')
  {
    return 'http://'.$_SERVER['HTTP_HOST'].'/'.$app.'.php/'.$path;
  }

  public static function linkify($text)
  {
    preg_match("/[A-Za-z]+:\/\/[A-Za-z0-9-_]+\.[A-Za-z0-9-_:%&\?\/.=]+/i", $text, $matches);
    foreach ($matches as $match)
    {
      if ($tinyurl = file_get_contents('http://tinyurl.com/api-create.php?url='.trim($match)))
      {
        $text = str_replace($match, sprintf('<a href="%1$s" target="_blank">%1$s</a>', $tinyurl), $text);
      }
    }

    return $text;
  }

  public static function shortenUrl($url, $length = 40)
  {
    $str_length = strlen($url);

    if($str_length > $length)
    {
    	$half = $length / 2;
	    $length = $str_length - $half;
	    $first = substr($url, 0, -$length);
	    $last = substr($url, -$half);
	    $url = $first."[ ... ]".$last;
    }

    return $url;
  }

  public static function getRandomProxy()
  {
  	if (is_readable(sfConfig::get('sf_data_dir').'/proxylist.txt')) {
  	  $proxies = file(sfConfig::get('sf_data_dir').'/proxylist.txt');
  	} else {
  		return array();
  	}

  	return explode('  ', $proxies[rand(0, count($proxies) - 1)]);
  }

  /**
   * @desc striptags,prevents xss attacks
   * @author ahmet ertek
   * @return filtered string
   *
   */
  public static function noXss($input)
  {
  	$input = strip_tags($input);

  	return $input;
  }

  public static function trimplural($word)
  {
  	if (strtolower($word) == 'star wars') {
  		return $word;
  	} else if (strtolower($word) == 'hot wheels') {
      return $word;
    } else if (strtolower($word) == 'clocks & watches') {
      return $word;
    }

    if (strtolower($word) == 'elvis') {
      return $word;
    }

    $plural_end='os'; $replace_singular='o'; if( substr($word, -2) ==
    $plural_end) {
      $word = substr($word, 0,strlen($word)-2). $replace_singular;
    }

    $plural_end='ies'; $replace_singular='y'; if( substr($word, -3) ==
    $plural_end) {
      $word = substr($word, 0,strlen($word)-3). $replace_singular;
    }


    $plural_end='xes'; $replace_singular='x'; if( substr($word, -3) ==
    $plural_end) {
      $word = substr($word, 0,strlen($word)-3). $replace_singular;
    }

    $plural_end='oes'; $replace_singular='o'; if( substr($word, -3) ==
    $plural_end) {
      $word = substr($word, 0,strlen($word)-3). $replace_singular;
    }

    $plural_end='ies'; $replace_singular='y'; if( substr($word, -3) ==
    $plural_end) {
      $word = substr($word, 0,strlen($word)-3). $replace_singular;
    }

    $plural_end='ves'; $replace_singular='fe'; if( substr($word, -3) ==
    $plural_end) {
      $word = substr($word, 0,strlen($word)-3). $replace_singular;
    }

    $plural_end='s'; $replace_singular=''; if( substr($word, -1) ==
    $plural_end && !(substr($word, -2) == 'ss') ) {
      $word = substr($word, 0,strlen($word)-1). $replace_singular;
    }

    return $word;
  }

  static public function slugify($text)
  {
    // replace all non letters or digits by -
    $text = preg_replace('/\W+/', '-', $text);

    // trim and lowercase
    $text = strtolower(trim($text, '-'));

    return $text;
  }

}

<?php

function cq_page_title($h1, $link = null, $options = array())
{
  $default = null !== $link ? array('left' => 8, 'right' => 4) : array();
  $options = array_merge($default, $options);

  if (null === $link)
  {
    $content = sprintf(<<<EAT
  <div class="span12">
    <h1 class="Chivo webfont">%s</h1>
  </div>
EAT
, $h1, $link);
  }
  else
  {
    $content = sprintf(<<<EAT
  <div class="span%d">
    <h1 class="Chivo webfont">%s</h1>
  </div>
  <div class="span%d text-right">%s</div>
EAT
, (int) $options['left'], $h1, (int) $options['right'], $link);
  }

  unset($options['left'], $options['right']);
  $options = array_merge(array('class' => 'row-fluid header-bar'), $options);

  echo content_tag('div', $content, $options);
}

function cq_section_title($h2, $link = null, $options = array())
{
  $default = null !== $link ? array('left' => 8, 'right' => 4) : array();
  $options = array_merge($default, $options);

  if (null === $link)
  {
$content = sprintf(<<<EAT
  <div class="span12">
    <h2 class="Chivo webfont">%s</h2>
  </div>
EAT
, $h2, $link);
  }
  else
  {
$content = sprintf(<<<EAT
  <div class="span%d">
    <h2 class="Chivo webfont">%s</h2>
  </div>
  <div class="span%d text-right">%s</div>
EAT
, (int) $options['left'], $h2, $options['right'], $link);
  }

  unset($options['left'], $options['right']);
  $options = array_merge(array('class' => 'row-fluid section-title spacer-top-35'), $options);

  echo content_tag('div', $content, $options);
}

function cq_sidebar_title($h3, $link = null, $options = array())
{
  $default = null !== $link ? array('left' => 9, 'right' => 3) : array();
  $options = array_merge($default, $options);

  if (null === $link)
  {
$content = sprintf(<<<EAT
  <div class="span12">
    <h3 class="Chivo webfont">%s</h3>
  </div>
EAT
, $h3, $link);
  }
  else
  {
$content = sprintf(<<<EAT
  <div class="span%d">
    <h3 class="Chivo webfont">%s</h3>
  </div>
  <div class="span%d text-right">%s&nbsp;</div>
EAT
, (int) $options['left'], $h3, $options['right'], $link);
  }

  unset($options['left'], $options['right']);
  $options = array_merge(array('class' => 'row-fluid sidebar-title'), $options);

  echo content_tag('div', $content, $options);
}


/**
 * @param PropelObjectCollection $collection must be ordered by branch
 */
function cq_nestedset_to_ul(PropelObjectCollection $collection, $print_method = '__toString')
{
  echo '<ul>' . "\n";

  foreach($collection as $object)
  {
    // should close levels ?
    if ($prev_object = $collection->getPrevious())
    {
      $close_levels = $prev_object->getLevel() - $object->getLevel();
    }
    else
    {
      $close_levels = false;
    }

    // reset the internal iterator to its original starting point,
    // because getPrevious() moves it back
    $collection->getInternalIterator()->next();

    if ($close_levels > 0)
    {
      echo str_repeat('</ul></li>', $close_levels) . "\n";
    }

    // print the
    echo '<li>' . call_user_func(array($object, $print_method));

    if ($object->hasChildren())
    {
      echo "\n" . '<ul>';
    }
    else
    {
      echo '</li>' . "\n";
    }
    ;
  }

  echo '</ul>';
}
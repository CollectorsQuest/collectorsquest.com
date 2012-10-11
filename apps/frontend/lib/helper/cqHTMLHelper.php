<?php

function cq_page_title($h1, $link = null, $options = array())
{
  $default = null !== $link ? array('left' => 8, 'right' => 4, 'itemprop' => '') : array('itemprop' => '');
  $options = array_merge($default, $options);

  if (null === $link)
  {
    $content = sprintf(<<<EAT
  <div class="span12">
    <h1 class="Chivo webfont" id="%s" %s>%s</h1>
  </div>
EAT
, @$options['id'] ?: cqStatic::getUniqueId(10), $options['itemprop'], $h1, $link);
  }
  else
  {
    $content = sprintf(<<<EAT
  <div class="span%d">
    <h1 class="Chivo webfont" id="%s" %s>%s</h1>
  </div>
  <div class="span%d text-right">%s</div>
EAT
, (int) $options['left'], @$options['id'] ?: cqStatic::getUniqueId(10), $options['itemprop'],  $h1, (int) $options['right'], $link);
  }

  unset($options['left'], $options['right'], $options['id'], $options['itemprop']);
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
function cq_nestedset_to_ul(PropelObjectCollection $collection, $print_method = '__toString', $id = null)
{
  echo '<ul class="menu" id="'. $id .'">' . "\n";

  foreach ($collection as $object)
  {
    // should close levels ?
    if ($prev_object = $collection->getPrevious())
    {
      $close_levels = $prev_object->getLevel() - $object->getLevel();
      // reset the internal iterator to its original starting point,
      // because getPrevious() moves it back
      $collection->getInternalIterator()->next();
    }
    else
    {
      $close_levels = false;
    }

    if ($close_levels > 0)
    {
      echo str_repeat('</ul></li>', $close_levels) . "\n";
    }

    // print the object name
    echo sprintf('<li class="%s">%s',
      $object->hasChildren() ? 'expanded' : 'leaf',
      sprintf(
        '<a href="javascript:void(0)" data-object-id="%d">%s</a>',
        $object->getId(), call_user_func(array($object, $print_method))
      )
    );

    if ($object->hasChildren())
    {
      echo "\n" . '<ul class="menu">';
    }
    else
    {
      echo '</li>' . "\n";
    }
    ;
  }

  echo '</ul>';
}

function cq_content_categories_to_ul(PropelObjectCollection $collections, $options = array())
{
  $options = array_merge(array(
      'class' => 'menu',
  ), $options);

  $html = '';
  foreach ($collections as $object)
  {
    // should close levels ?
    if ($prev_object = $collections->getPrevious())
    {
      $close_levels = $prev_object->getLevel() - $object->getLevel();
      // reset the internal iterator to its original starting point,
      // because getPrevious() moves it back
      $collections->getInternalIterator()->next();
    }
    else
    {
      $close_levels = false;
    }

    if ($close_levels > 0)
    {
      $close_str =
        '   <li class="leaf">
              <a href="javascript:void(0)" data-object-id="-1">Other</a>
            </li>
          </ul>
        </li>' . "\n";

      $html .= str_repeat($close_str, $close_levels) . "\n";
    }

    // print the object name
    $html .= sprintf('<li class="%s">%s',
      $object->hasChildren() ? 'expanded' : 'leaf',
      sprintf(
        '<a href="javascript:void(0)" data-object-id="%d">%s</a>',
        $object->getId(), $object->getName()
      )
    );

    if ($object->hasChildren())
    {
      $html .= "\n" . '<ul class="menu">';
    }
    else
    {
      $html .= '</li>' . "\n";
    }
  }

  echo content_tag('ul', $html, $options);
}

<?php

$total = count($items);
$i = 1;

if ($total > $i)
{
  echo '<div id="breadcrumbs" class="span-12" style="margin-top: -50px; padding: 5px;">';
  echo '<small>', __('Back to:'), '</small>&nbsp;';

  foreach ($items as $item)
  {
    $options = $item->getOptions();

    $text = $title = $item->getText();
    $uri = $item->getUri();

    if (isset($options['limit']) && is_numeric($options['limit']))
    {
      $text = cqStatic::reduceText($text, (int) $options['limit'], ' [...] ');
      unset($options['limit']);
    }

    if ($i < $total)
    {
      echo link_to_if(
        $uri, $text, $uri,
        array_merge(
          array('title' => sprintf(__('Back to %s'), $title)),
          $options
        )
      );

      if ($i < $total - 1)
      {
        echo '&nbsp;<small><b style="color: #000;">//</b></small>&nbsp;';
      }
    }
    else
    {
      $html_options = '';
      foreach ($options as $key => $value)
      {
        $html_options .= sprintf(' %s="%s"', $key, addslashes($value));
      }

      if (isset($options['class']) && $options['class'] == 'editable_h1')
      {
        echo '<div style="white-space: nowrap;">';
        echo '<span class="ui-icon ui-icon-pencil ui-icon-editable" style="margin-left: 15px; margin-top: 7px;"></span>';
        echo '<h1', $html_options, '>', $text, '</h1>';
        echo '</div>';
      }
      else
      {
        echo '<h1', $html_options, '>', $text, '</h1>';
      }
    }

    $i++;
  }

  echo '</div>';
}

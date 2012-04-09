<div id="wizard" class="row-fluid">
  <?php
    /**
     * @var  $steps   integer
     * @var  $active  boolean
     */
    for ($i = 1; $i <= count($steps); $i++)
    {
      $classes = array('wizard');

      $span = (count($steps) == 2 ? 5 : 3) + intval($active == $i);
      $classes[] = 'span'. $span;

      if ($active == $i) {
        $classes[] = 'active';
      }

      if (1 == $i) {
        $classes[] = 'first';
      } else if (count($steps) == $i){
        $classes[] = 'last';
      }

      $content = sprintf('&nbsp;&nbsp;Step %d: %s', $i, $steps[$i]);
      echo content_tag('div', $content, array('class' => implode(' ', $classes)));

      if ($i != count($steps)) {
        echo '<span class="span1 wizard_separator">&nbsp;</span>';
      }
    }
  ?>
</div>

<div class="well" style="padding: 8px 0; margin-top: 80px;">
  <ul class="nav nav-list">

    <li class="nav-header">sort by:</li>
    <li class="active"><a href="#"><i class="icon-ok"></i>&nbsp;Relevance</a></li>
    <li><a href="#">Most Recent</a></li>
    <li><a href="#">Most Popular</a></li>

    <li class="nav-header">Filter by:</li>
    <?php
      foreach ($types as $key => $params)
      {
        $name = $params['name'];

        if ($params['count'] >= 0)
        {
          $name .= ' ('. $params['count'] .')';
        }

        if ($params['active'] === true)
        {
          echo '<li class="active"><a href="#"><i class="icon-ok"></i>&nbsp;', $name,'</a></li>';
        }
        else
        {
          $link = link_to_if($params['count'] != 0, $name, $params['route']);
          echo '<li>', $link,'</li>';
        }
      }
    ?>
    <li>&nbsp;</li>
    <li class="divider"></li>
    <li>
      <a href="#" style="padding-left: 15px;">
        <i class="icon-info-sign"></i>
        Need help finding something? Click here!
      </a>
    </li>
  </ul>
</div>

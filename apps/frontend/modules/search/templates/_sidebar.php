<div class="row-fluid" style="margin: 35px auto 5px 15px;">
  <label for="canonical" class="span3" style="padding-top: 6px;">Direct Link:</label>
  <input class="span8" id="canonical" style="text" value="http://cq.me/search-results" onclick="this.select();">
</div>

<div class="well" style="padding: 8px 0;">
  <ul class="nav nav-list">
    <li class="nav-header">sort by:</li>
    <li class="active"><a href="#"><i class="icon-ok"></i>&nbsp;Relevance</a></li>
    <li><a href="#">Most Recent</a></li>
    <li><a href="#">Most Popular</a></li>
    <li class="nav-header">Filter by type:</li>
    <li>
      <?= link_to('All Types', '@search?q='. $sf_params->get('q')) ?>
    </li>
    <?php
      foreach ($types as $key => $params)
      {
        $name = $params['name'] .' ('. $params['count'] .')';

        if ($params['active'] === true)
        {
          echo '<li class="active"><a href="#"><i class="icon-ok"></i>&nbsp;', $name,'</a></li>';
        }
        else
        {
          $link = link_to_if($params['count'] > 0, $name, $params['route']);
          echo '<li>', $link,'</li>';
        }
      }
    ?>
    <li>&nbsp;</li>
    <li class="nav-header">Filter by category:</li>
    <?php foreach ($categories as $category): ?>
      <li>
        <a href="#" style="padding-left: 37px;">
          <?= $category->getName(); ?>
        </a>
      </li>
    <?php endforeach; ?>

    <li>&nbsp;</li>
    <li class="divider"></li>
    <li>
      <a href="#">
        <i class="icon-info-sign"></i>
        Need help finding something? Click here!
      </a>
    </li>
  </ul>
</div>

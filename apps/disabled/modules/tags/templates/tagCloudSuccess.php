<ul id="tag-cloud">
  <?php foreach ($tags as $tag => $count): ?>
    <li>
      <?= link_to($tag, $url.$tag, array('class' => 'tag_popularity_'. (is_array($count) ? $count['count']+3 : $count+3), 'rel' => 'tag', 'id' => 'clear')); ?>
      &nbsp; &nbsp;
    </li>
  <?php endforeach; ?>
</ul>

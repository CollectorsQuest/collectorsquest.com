<ul id="tag-cloud">
  <?php foreach ($tags as $tag => $count): ?>
    <li>
      <?= link_to($tag, '@search?tag='.$tag, array('class' => 'tag_popularity_'. ($count+3), 'rel' => 'tag', 'id' => 'clear')); ?>
      &nbsp; &nbsp;
    </li>
  <?php endforeach; ?>
</ul>

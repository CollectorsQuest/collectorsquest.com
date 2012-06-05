<div class="other-items-sidebar spacer-top-20">
  <span>Other collectibles in <?= link_to_collection($collection, 'text'); ?></span>
  <div class="thumbnails-inner">
    <ul class="thumbnails">
      <?php foreach ($collectibles as $c): ?>
      <li class="span3">
        <a href="<?= url_for_collectible($c) ?>"
           class="thumbnail <?= $collectible->getId() === $c->getId() ? 'active' : null; ?>">
          <?php
            echo image_tag_collectible(
              $c, '75x75', array('max_width' => 70, 'max_height' => 70)
            );
          ?>
        </a>
      </li>
      <?php endforeach; ?>
    </ul>
  </div>
</div>

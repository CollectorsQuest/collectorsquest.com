<?php use_stylesheet('backend/jquery.contextMenu.css'); ?>
<?php use_javascript('backend/jquery.contextMenu.js'); ?>

<?php if ($multimedia): ?>
<ul id="multimedia_list" style="margin-left: 125px;">
  <?php foreach ($multimedia as $m): ?>
    <li style="display: inline; cursor: move;">
      <?php
        echo image_tag_multimedia(
          $m, 'thumbnail',
          array('align' => 'left', 'style' => 'margin: 10px;', 'id'=> 'multimedia_'. $m->getId())
        );
      ?>
    </li>
  <?php endforeach; ?>
  </ul>
<?php endif; ?>

<ul id="multimedia_context_menu" class="contextMenu" style="width: 130px;">
  <li class="delete">
    <a href="#delete" style="text-decoration: none;">&nbsp;Delete Image</a>
  </li>
</ul>

<br clear="all"/>

<script type="text/javascript">
$(document).ready(function()
{
  $('#multimedia_list').sortable(
  {
    items: 'img', opacity: 0.6,
    update: function()
    {
      jQuery.post(
        '<?php echo url_for('@ajax_multimedia_reorder'); ?>',
        {
          items: $('#multimedia_list').sortable('serialize'),
          key: 'multimedia'
        }
      );
    }
  });

  $("#multimedia_list img").contextMenu(
  {
    menu: 'multimedia_context_menu'
  },
  function(action, el)
  {
    if ('delete' == action)
    {
      jQuery.ajax(
      {
        url: '<?php echo url_for('@ajax_multimedia_delete'); ?>',
        data: { id: $(el).attr('id').replace(/multimedia_/, '') },
        success: function()
        {
          $(el).fadeOut('slow');
        }
      });
    }
  });
});
</script>

<?php foreach ($form as $form_widget):
  if ($form_widget instanceof sfFormFieldSchema):
    foreach ($form_widget as $form_field): ?>

      <?php if (!$form_field->isHidden()): ?>
        <div class="span-5" style="text-align: right;">
          <?= $form_field->renderLabel(); ?>
        </div>
        <div class="prepend-1 span-12 last">
          <?= $form_field->renderError(); ?>
          <?= $form_field->render(array('convert' => $form_has_errors)); ?>
        </div>
        <br clear="all"/><br>
      <?php endif; ?>

    <?php endforeach;
  endif;
endforeach; ?>
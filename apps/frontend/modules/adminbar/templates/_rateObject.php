<?php foreach ($forms as $form): ?>
  <div class="row">
      <div class="span4"><?= $form->getObject()->getDimensionLabel() ?></div>
      <div class="span8">
        <?php include_partial('adminbar/rateForm', array('form' => $form, 'class' => $class, 'id' => $id)); ?>
      </div>
  </div>
<?php endforeach ?>
<script>
    $(document).ready(function()
    {
        $('.object_rate_form').each(function(){
            $(this).ajaxForm();
        });
        $('.object_rate_form input').live('change', function(){
           $(this).closest('form').submit();
        });
    });
</script>
